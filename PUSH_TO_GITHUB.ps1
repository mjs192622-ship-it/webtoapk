# ============================================================
# WebTooAPK - GitHub Push Script
# Sari files commit ho chuki hain - sirf PAT paste karein
# ============================================================

$PAT = Read-Host "mjs192622-ship-it GitHub Personal Access Token paste karein"

if ([string]::IsNullOrWhiteSpace($PAT)) {
    Write-Host "ERROR: Token khali nahi ho sakta!" -ForegroundColor Red
    exit 1
}

Set-Location "D:\all desktop project\website to apk"
git config --global --add safe.directory "D:/all desktop project/website to apk"

# All files already committed (2773af5) - just push
git push "https://x-access-token:${PAT}@github.com/mjs192622-ship-it/webtoapk.git" main --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "SUCCESS! GitHub pe push ho gaya!" -ForegroundColor Green
    Write-Host "Render 2-3 mins mein auto-deploy karega - webtooapk.com live ho jayegi" -ForegroundColor Cyan
} else {
    Write-Host "FAILED! Naya token banao:" -ForegroundColor Red
    Write-Host "  github.com/settings/tokens -> Generate new token (classic) -> 'repo' tick karein" -ForegroundColor Yellow
}

Read-Host "Enter dabao bahar jaane ke liye"
