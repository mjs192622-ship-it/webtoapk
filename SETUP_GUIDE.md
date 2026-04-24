# 🚀 WebToAPK Pro - Server Setup Guide

## 📋 Requirements for APK Generation

To generate APK files directly on your server, you need:

### Minimum Server Requirements:
- **VPS/Dedicated Server** (Shared hosting won't work)
- **Ubuntu 20.04+** or similar Linux
- **2GB RAM minimum** (4GB recommended)
- **10GB disk space** (for Android SDK)
- **Root/sudo access**

### Recommended VPS Providers:
| Provider | Price | Link |
|----------|-------|------|
| DigitalOcean | $6/mo | https://digitalocean.com |
| Vultr | $5/mo | https://vultr.com |
| Linode | $5/mo | https://linode.com |
| Hetzner | €4/mo | https://hetzner.com |

---

## 🔧 Installation Steps

### Step 1: Install Java JDK 17

```bash
sudo apt update
sudo apt install openjdk-17-jdk -y

# Verify installation
java -version
```

### Step 2: Install Android SDK Command Line Tools

```bash
# Create SDK directory
mkdir -p ~/android-sdk/cmdline-tools
cd ~/android-sdk/cmdline-tools

# Download command line tools
wget https://dl.google.com/android/repository/commandlinetools-linux-9477386_latest.zip

# Extract
unzip commandlinetools-linux-9477386_latest.zip
mv cmdline-tools latest

# Clean up
rm commandlinetools-linux-9477386_latest.zip
```

### Step 3: Set Environment Variables

Add to `~/.bashrc` (for user) or `/etc/environment` (system-wide):

```bash
echo 'export ANDROID_HOME=$HOME/android-sdk' >> ~/.bashrc
echo 'export ANDROID_SDK_ROOT=$HOME/android-sdk' >> ~/.bashrc
echo 'export PATH=$PATH:$ANDROID_HOME/cmdline-tools/latest/bin:$ANDROID_HOME/platform-tools' >> ~/.bashrc

# Apply changes
source ~/.bashrc
```

### Step 4: Install SDK Components

```bash
# Accept licenses
yes | sdkmanager --licenses

# Install required components
sdkmanager "platforms;android-34" "build-tools;34.0.0" "platform-tools"
```

### Step 5: Install Gradle

```bash
# Install Gradle
sudo apt install gradle -y

# Or install specific version
wget https://services.gradle.org/distributions/gradle-8.0-bin.zip
sudo unzip gradle-8.0-bin.zip -d /opt/gradle
echo 'export PATH=$PATH:/opt/gradle/gradle-8.0/bin' >> ~/.bashrc
source ~/.bashrc

# Verify
gradle -v
```

### Step 6: Configure Web Server Permissions

```bash
# Add www-data user to your user group
sudo usermod -a -G www-data $USER

# Give web server access to Android SDK
sudo chown -R www-data:www-data ~/android-sdk
sudo chmod -R 755 ~/android-sdk

# Or run PHP as your user (recommended for this use case)
```

### Step 7: Enable APK Building in config.php

Edit `config.php`:

```php
// Change this to true
define('ENABLE_APK_BUILD', true);

// Update paths if needed
define('ANDROID_SDK_PATH', '/home/YOUR_USER/android-sdk');
define('JAVA_HOME', '/usr/lib/jvm/java-17-openjdk-amd64');
```

---

## 🐳 Alternative: Docker Setup (Easier)

If you prefer Docker:

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Pull Android SDK image
docker pull thyrlian/android-sdk:latest

# Test build
docker run --rm -v /path/to/project:/project thyrlian/android-sdk:latest /bin/bash -c "cd /project && gradle assembleDebug"
```

---

## ✅ Verify Installation

Run this command to check everything:

```bash
# Check Java
java -version

# Check Gradle
gradle -v

# Check Android SDK
sdkmanager --list

# Check SDK location
echo $ANDROID_HOME
ls $ANDROID_HOME
```

---

## 🔒 Security Recommendations

1. **Rate Limiting**: Add rate limiting to prevent abuse
2. **File Size Limits**: Limit upload sizes
3. **Cleanup Job**: Add cron job to delete old builds

```bash
# Add to crontab (delete builds older than 24 hours)
0 */6 * * * find /var/www/html/output -mmin +1440 -delete
0 */6 * * * find /var/www/html/uploads -mmin +1440 -delete
```

---

## 🆘 Troubleshooting

### Error: "SDK location not found"
```bash
export ANDROID_HOME=/home/YOUR_USER/android-sdk
export ANDROID_SDK_ROOT=$ANDROID_HOME
```

### Error: "Gradle daemon disappeared"
```bash
gradle --stop
gradle assembleDebug --no-daemon
```

### Error: "Permission denied"
```bash
sudo chown -R www-data:www-data /var/www/html/output
sudo chmod -R 775 /var/www/html/output
```

### Error: "Out of memory"
Add to `gradle.properties`:
```
org.gradle.jvmargs=-Xmx1536m
```

---

## 🆓 FREE Alternative: GitHub Actions Build

If you don't want to set up a VPS, you can use **GitHub Actions** to build APKs for FREE!

### Step 1: Create GitHub Personal Access Token

1. Go to https://github.com/settings/tokens
2. Click "Generate new token (classic)"
3. Give it a name like "WebToAPK Builder"
4. Select scopes: `repo`, `workflow`
5. Click "Generate token"
6. **COPY THE TOKEN** (you won't see it again!)

### Step 2: Configure in config.php

```php
define('ENABLE_GITHUB_BUILD', true);
define('GITHUB_TOKEN', 'ghp_your_token_here');
define('GITHUB_OWNER', 'your-github-username');
```

### Step 3: How It Works

1. User submits URL and app details
2. Android project is generated on your server
3. Project is pushed to a new GitHub repository
4. GitHub Actions automatically builds the APK
5. APK is available in GitHub Releases

### Limitations:

- GitHub Actions has 2000 free minutes/month
- Each APK build takes ~3-5 minutes
- So you can build ~400-600 APKs/month for FREE!

---

## 📞 Need Help?

If you're unable to set up the build server, the website will still work - it will generate source code ZIP files that can be opened in Android Studio to build APK manually.
