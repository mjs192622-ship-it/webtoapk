<?php
/**
 * GitHub Build Diagnostic - DELETE AFTER USE
 * Access: https://webtooapk.com/debug_github.php
 */
header('Content-Type: text/plain');

require_once 'config.php';

$token = GITHUB_TOKEN;
$owner = GITHUB_OWNER;

echo "=== GitHub Build Diagnostic ===\n\n";
echo "Token present: " . (!empty($token) ? 'YES (length=' . strlen($token) . ')' : 'NO - EMPTY!') . "\n";
echo "Token starts with: " . substr($token, 0, 8) . "...\n";
echo "Owner: " . $owner . "\n\n";

// Test 1: Verify token works
echo "--- Test 1: Verify token (GET /user) ---\n";
$ch = curl_init('https://api.github.com/user');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.github.v3+json',
        'User-Agent: WebToAPK-Builder',
    ],
]);
$resp = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\n";
if (isset($resp['login'])) {
    echo "Authenticated as: " . $resp['login'] . "\n";
    echo "Token scopes OK\n\n";
} else {
    echo "AUTH FAILED: " . json_encode($resp) . "\n\n";
}

// Test 2: Check token scopes
echo "--- Test 2: Token scopes ---\n";
$ch = curl_init('https://api.github.com/user');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_HEADER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.github.v3+json',
        'User-Agent: WebToAPK-Builder',
    ],
]);
$fullResp = curl_exec($ch);
curl_close($ch);
if (preg_match('/x-oauth-scopes:\s*(.+)/i', $fullResp, $m)) {
    echo "Scopes: " . trim($m[1]) . "\n";
} else {
    echo "Could not read scopes header\n";
}
echo "\n";

// Test 3: List existing apk-* repos
echo "--- Test 3: Existing apk-* repos ---\n";
$ch = curl_init("https://api.github.com/user/repos?per_page=10&sort=created&direction=desc");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.github.v3+json',
        'User-Agent: WebToAPK-Builder',
    ],
]);
$repos = json_decode(curl_exec($ch), true);
curl_close($ch);
if (is_array($repos)) {
    $apkRepos = array_filter($repos, fn($r) => strpos($r['name'], 'apk-') === 0);
    echo "Total recent repos: " . count($repos) . "\n";
    echo "APK repos: " . count($apkRepos) . "\n";
    foreach ($apkRepos as $r) {
        echo "  - " . $r['name'] . " (" . $r['created_at'] . ") actions: " . $r['html_url'] . "/actions\n";
    }
} else {
    echo "Failed to list repos\n";
}
echo "\n";

// Test 4: Try creating a test repo
echo "--- Test 4: Create test repo ---\n";
$testRepo = 'apk-test-' . time();
$ch = curl_init('https://api.github.com/user/repos');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode([
        'name' => $testRepo,
        'description' => 'Test repo - delete me',
        'private' => false,
        'auto_init' => false,
    ]),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.github.v3+json',
        'User-Agent: WebToAPK-Builder',
        'Content-Type: application/json',
    ],
]);
$result = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $httpCode\n";
if (isset($result['id'])) {
    echo "Repo created: " . $result['full_name'] . "\n";
    echo "URL: " . $result['html_url'] . "\n\n";
    
    // Test 5: Push a file to it
    echo "--- Test 5: Push file to repo ---\n";
    $ch = curl_init("https://api.github.com/repos/{$owner}/{$testRepo}/git/blobs");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'content' => base64_encode('Hello World'),
            'encoding' => 'base64',
        ]),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json',
            'User-Agent: WebToAPK-Builder',
            'Content-Type: application/json',
        ],
    ]);
    $blob = json_decode(curl_exec($ch), true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Blob HTTP: $httpCode\n";
    if (isset($blob['sha'])) {
        echo "Blob SHA: " . $blob['sha'] . "\n";
        
        // Create tree
        $ch = curl_init("https://api.github.com/repos/{$owner}/{$testRepo}/git/trees");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'tree' => [['path' => 'README.md', 'mode' => '100644', 'type' => 'blob', 'content' => '# Test']],
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/vnd.github.v3+json',
                'User-Agent: WebToAPK-Builder',
                'Content-Type: application/json',
            ],
        ]);
        $tree = json_decode(curl_exec($ch), true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "Tree HTTP: $httpCode, SHA: " . ($tree['sha'] ?? 'FAILED: ' . json_encode($tree)) . "\n";

        if (isset($tree['sha'])) {
            // Create commit
            $ch = curl_init("https://api.github.com/repos/{$owner}/{$testRepo}/git/commits");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode([
                    'message' => 'Initial commit',
                    'tree' => $tree['sha'],
                    'parents' => [],
                ]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/vnd.github.v3+json',
                    'User-Agent: WebToAPK-Builder',
                    'Content-Type: application/json',
                ],
            ]);
            $commit = json_decode(curl_exec($ch), true);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo "Commit HTTP: $httpCode, SHA: " . ($commit['sha'] ?? 'FAILED: ' . json_encode($commit)) . "\n";

            if (isset($commit['sha'])) {
                // Create ref
                $ch = curl_init("https://api.github.com/repos/{$owner}/{$testRepo}/git/refs");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 15,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'ref' => 'refs/heads/main',
                        'sha' => $commit['sha'],
                    ]),
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $token,
                        'Accept: application/vnd.github.v3+json',
                        'User-Agent: WebToAPK-Builder',
                        'Content-Type: application/json',
                    ],
                ]);
                $ref = json_decode(curl_exec($ch), true);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                echo "Ref HTTP: $httpCode\n";
                if (isset($ref['ref'])) {
                    echo "FULL PUSH SUCCESS! Repo live at: https://github.com/{$owner}/{$testRepo}\n";
                } else {
                    echo "Ref FAILED: " . json_encode($ref) . "\n";
                }
            }
        }
    } else {
        echo "Blob FAILED: " . json_encode($blob) . "\n";
    }
    
    // Cleanup: delete test repo
    echo "\n--- Cleanup: Delete test repo ---\n";
    $ch = curl_init("https://api.github.com/repos/{$owner}/{$testRepo}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json',
            'User-Agent: WebToAPK-Builder',
        ],
    ]);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Delete HTTP: $httpCode " . ($httpCode === 204 ? "(OK)" : "(FAILED)") . "\n";
} else {
    echo "Repo creation FAILED: " . json_encode($result) . "\n";
}

echo "\n=== Done ===\n";
