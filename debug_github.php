<?php
/**
 * GitHub Build Diagnostic v2 - DELETE AFTER USE
 * Access: https://webtooapk.com/debug_github.php
 */
header('Content-Type: text/plain');

require_once 'config.php';

$token = GITHUB_TOKEN;
$owner = GITHUB_OWNER;

function ghApi($url, $method = 'GET', $data = null) {
    global $token;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json',
            'User-Agent: WebToAPK-Diag',
            'Content-Type: application/json',
        ],
        CURLOPT_CUSTOMREQUEST => $method,
    ]);
    if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, json_decode($resp, true)];
}

echo "=== GitHub Diagnostic v2 ===\n\n";
echo "Token: " . substr($token,0,8) . "... (len=" . strlen($token) . ")\n";
echo "Owner: $owner\n\n";

// Check most recent apk-* repo content
echo "--- Most recent apk-* repo content check ---\n";
[$c, $repos] = ghApi("https://api.github.com/search/repositories?q=user:{$owner}+apk-+in:name&sort=created&order=desc&per_page=3");
if (isset($repos['items'][0])) {
    $repo = $repos['items'][0];
    $rname = $repo['name'];
    echo "Checking: $rname (created: " . $repo['created_at'] . ")\n";
    [$c2, $contents] = ghApi("https://api.github.com/repos/{$owner}/{$rname}/contents/");
    if (is_array($contents) && isset($contents[0]['name'])) {
        echo "Files in root: " . implode(', ', array_column($contents, 'name')) . "\n";
    } elseif (isset($contents['message'])) {
        echo "Content error: " . $contents['message'] . "\n";
    }
    [$c3, $runs] = ghApi("https://api.github.com/repos/{$owner}/{$rname}/actions/runs?per_page=3");
    echo "Actions runs: " . ($runs['total_count'] ?? 'error') . "\n";
    if (!empty($runs['workflow_runs'])) {
        foreach ($runs['workflow_runs'] as $run) {
            echo "  - #" . $run['run_number'] . " " . $run['status'] . "/" . $run['conclusion'] . " " . $run['html_url'] . "\n";
        }
    }
} else {
    echo "No repos found or search failed\n";
}
echo "\n";

// Test new approach: auto_init=true + blobs
echo "--- Test NEW approach (auto_init=true + blobs) ---\n";
$testRepo = 'apk-diag-' . time();

// Create repo with auto_init=true
[$c, $result] = ghApi('https://api.github.com/user/repos', 'POST', [
    'name' => $testRepo, 'private' => false, 'auto_init' => true,
]);
echo "Create repo HTTP: $c\n";
if (!isset($result['id'])) { echo "FAILED: " . json_encode($result) . "\n"; exit; }
echo "Repo created: " . $result['full_name'] . "\n";

sleep(1); // Wait for GitHub to initialize

// Get HEAD ref
[$c, $headRef] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/ref/heads/main");
echo "HEAD ref HTTP: $c\n";
if (!isset($headRef['object']['sha'])) { 
    // try master
    [$c, $headRef] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/ref/heads/master");
    echo "HEAD ref (master) HTTP: $c\n";
}
$parentSha = $headRef['object']['sha'] ?? null;
echo "Parent SHA: " . ($parentSha ? substr($parentSha,0,8).'...' : 'NOT FOUND') . "\n";

if ($parentSha) {
    // Create blob for a text file
    [$c, $blob] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/blobs", 'POST', [
        'content' => base64_encode('# Test Android Project'), 'encoding' => 'base64',
    ]);
    echo "Blob HTTP: $c, SHA: " . ($blob['sha'] ?? 'FAILED: ' . json_encode($blob)) . "\n";

    // Create blob for a binary-like file
    [$c, $blob2] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/blobs", 'POST', [
        'content' => base64_encode(str_repeat("\x00\xFF\xAB\xCD", 100)), 'encoding' => 'base64',
    ]);
    echo "Binary blob HTTP: $c, SHA: " . ($blob2['sha'] ?? 'FAILED: ' . json_encode($blob2)) . "\n";

    if (isset($blob['sha']) && isset($blob2['sha'])) {
        // Create tree
        [$c, $tree] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/trees", 'POST', [
            'tree' => [
                ['path' => 'README.md', 'mode' => '100644', 'type' => 'blob', 'sha' => $blob['sha']],
                ['path' => 'test.bin', 'mode' => '100644', 'type' => 'blob', 'sha' => $blob2['sha']],
            ]
        ]);
        echo "Tree HTTP: $c, SHA: " . ($tree['sha'] ?? 'FAILED: ' . json_encode($tree)) . "\n";

        if (isset($tree['sha'])) {
            // Create commit
            [$c, $commit] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/commits", 'POST', [
                'message' => 'Add Android project', 'tree' => $tree['sha'], 'parents' => [$parentSha],
            ]);
            echo "Commit HTTP: $c, SHA: " . ($commit['sha'] ?? 'FAILED: ' . json_encode($commit)) . "\n";

            if (isset($commit['sha'])) {
                // PATCH ref
                [$c, $ref] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}/git/refs/heads/main", 'PATCH', [
                    'sha' => $commit['sha'], 'force' => false,
                ]);
                echo "PATCH ref HTTP: $c\n";
                if (isset($ref['ref'])) {
                    echo "\n✅ FULL PUSH SUCCESS!\n";
                    echo "Repo: https://github.com/{$owner}/{$testRepo}\n";
                    echo "Actions will trigger on next push: https://github.com/{$owner}/{$testRepo}/actions\n";
                } else {
                    echo "Ref PATCH FAILED: " . json_encode($ref) . "\n";
                }
            }
        }
    }
}

// Cleanup
[$c] = ghApi("https://api.github.com/repos/{$owner}/{$testRepo}", 'DELETE');
echo "\nCleanup (delete test repo) HTTP: $c " . ($c===204?'(OK)':'(FAILED)') . "\n";
echo "\n=== Done ===\n";

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
