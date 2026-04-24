# Local Setup Instructions

## Status
Your local config.php has been updated with correct GitHub credentials.

## Required Steps

### 1. Copy Template Folder from Google Cloud VM
Your local `template/` folder is empty. You need to download the working template from your VM:

**Using WinSCP or FileZilla:**
- Connect to: 34.27.233.78
- Navigate to: `/var/www/html/template/`
- Download entire `template` folder to: `c:\Users\hp\Desktop\website to apk\template\`

**Or using SCP command in PowerShell:**
```powershell
scp -r username@34.27.233.78:/var/www/html/template/* "c:\Users\hp\Desktop\website to apk\template\"
```

### 2. Create Required Folders
```powershell
New-Item -ItemType Directory -Force -Path ".\uploads"
New-Item -ItemType Directory -Force -Path ".\output"
New-Item -ItemType Directory -Force -Path ".\builds"
```

### 3. Set Folder Permissions
Make sure these folders are writable:
- `uploads/`
- `output/`
- `builds/`

### 4. Install XAMPP or Local PHP Server
If you want to test locally:

**Option A: XAMPP**
1. Download from: https://www.apachefriends.org/
2. Copy entire project folder to `C:\xampp\htdocs\website-to-apk\`
3. Start Apache from XAMPP Control Panel
4. Access: http://localhost/website-to-apk/

**Option B: PHP Built-in Server**
```powershell
cd "c:\Users\hp\Desktop\website to apk"
php -S localhost:8000
```
Access: http://localhost:8000

### 5. Test GitHub Connection
```powershell
php test_generate.php
```

## What's Already Configured

✅ **config.php updated with:**
- GITHUB_TOKEN: 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
- GITHUB_OWNER: abigail24223
- ENABLE_GITHUB_BUILD: true

## Important Notes

⚠️ **GitHub Build Requirement:**
- `shell_exec()` must be enabled in PHP
- This works on your Google Cloud VM (34.27.233.78)
- May NOT work on Windows/shared hosting without shell access

⚠️ **For Production Use:**
Keep using your Google Cloud VM at: http://34.27.233.78/

## Testing Checklist

- [ ] Template folder copied from VM
- [ ] Folders created (uploads, output, builds)
- [ ] PHP server running
- [ ] Can access the website
- [ ] Test generation creates ZIP file
- [ ] GitHub repo creation works (if shell_exec available)

## Troubleshooting

**Error: "shell_exec() is disabled"**
→ This is normal on Windows. Either:
1. Enable it in php.ini (remove from disable_functions)
2. Use Google Cloud VM for production

**Error: "Failed to create directory"**
→ Check folder permissions (Right-click → Properties → Security)

**Error: "GitHub API error"**
→ Verify token is valid: https://github.com/settings/tokens
