# ============================================================
# WebTooAPK - GitHub Push Script
# Run this from PowerShell as Administrator
# ============================================================

$PAT = Read-Host "Apna GitHub Personal Access Token paste karein (mjs192622-ship-it account ka)"

if ([string]::IsNullOrWhiteSpace($PAT)) {
    Write-Host "ERROR: Token khali nahi ho sakta!" -ForegroundColor Red
    exit 1
}

Set-Location "D:\all desktop project\website to apk"

# Fix safe directory issue
git config --global --add safe.directory "D:/all desktop project/website to apk"

# Stage all changes
git add -A

# Check if there's anything to commit
$status = git status --short
if ($status) {
    git commit -m "Fix: Dockerfile + Render HTTPS redirect + free mode enabled"
    Write-Host "Commit complete!" -ForegroundColor Green
} else {
    Write-Host "Already up to date, pushing existing commit..." -ForegroundColor Yellow
}

# Push using PAT
$repoUrl = "https://x-access-token:${PAT}@github.com/mjs192622-ship-it/webtoapk.git"
git push $repoUrl main --force

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "SUCCESS! GitHub pe push ho gaya!" -ForegroundColor Green
    Write-Host "Render automatically deploy start karega - 2-3 minutes mein site live ho jayegi." -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "FAILED! Token check karein (repo scope hona chahiye)" -ForegroundColor Red
    Write-Host "Token banane ke liye: https://github.com/settings/tokens/new" -ForegroundColor Yellow
    Write-Host "Scopes: 'repo' tick karein" -ForegroundColor Yellow
}

Read-Host "Enter dabayein bahar jaane ke liye"
