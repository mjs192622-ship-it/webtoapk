#!/bin/bash
###############################################################################
# APK Build Server Setup Script
# Run this on your Ubuntu VPS to install all build dependencies
#
# Usage: sudo bash setup_build_server.sh
###############################################################################

set -e

echo "============================================"
echo "  APK Build Server Setup"
echo "============================================"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "ERROR: Please run as root (sudo bash setup_build_server.sh)"
    exit 1
fi

# Get the web server user
WEB_USER="www-data"
BUILD_HOME="/opt/android-build"

echo "[1/7] Updating system packages..."
apt update -y
apt upgrade -y

echo ""
echo "[2/7] Installing Java JDK 17..."
apt install -y openjdk-17-jdk unzip wget
java -version
echo "JAVA_HOME: $(dirname $(dirname $(readlink -f $(which java))))"

echo ""
echo "[3/7] Creating build directories..."
mkdir -p "$BUILD_HOME"
mkdir -p "$BUILD_HOME/gradle"
mkdir -p "$BUILD_HOME/android-sdk"
mkdir -p "$BUILD_HOME/android-sdk/cmdline-tools"

echo ""
echo "[4/7] Installing Android SDK Command Line Tools..."
cd /tmp
CMDLINE_TOOLS_URL="https://dl.google.com/android/repository/commandlinetools-linux-11076708_latest.zip"
wget -q --show-progress "$CMDLINE_TOOLS_URL" -O cmdline-tools.zip
unzip -o cmdline-tools.zip -d "$BUILD_HOME/android-sdk/cmdline-tools/"
# Move to 'latest' subdirectory as expected by SDK
if [ -d "$BUILD_HOME/android-sdk/cmdline-tools/cmdline-tools" ]; then
    rm -rf "$BUILD_HOME/android-sdk/cmdline-tools/latest"
    mv "$BUILD_HOME/android-sdk/cmdline-tools/cmdline-tools" "$BUILD_HOME/android-sdk/cmdline-tools/latest"
fi
rm -f cmdline-tools.zip

echo ""
echo "[5/7] Installing Gradle 8.11.1..."
cd /tmp
GRADLE_URL="https://services.gradle.org/distributions/gradle-8.11.1-bin.zip"
wget -q --show-progress "$GRADLE_URL" -O gradle.zip
unzip -o gradle.zip -d "$BUILD_HOME/gradle/"
rm -f gradle.zip

# Set up environment variables
JAVA_HOME_PATH=$(dirname $(dirname $(readlink -f $(which java))))
GRADLE_HOME="$BUILD_HOME/gradle/gradle-8.11.1"
ANDROID_SDK="$BUILD_HOME/android-sdk"
SDK_TOOLS="$ANDROID_SDK/cmdline-tools/latest/bin"

export JAVA_HOME="$JAVA_HOME_PATH"
export ANDROID_HOME="$ANDROID_SDK"
export ANDROID_SDK_ROOT="$ANDROID_SDK"
export PATH="$PATH:$GRADLE_HOME/bin:$SDK_TOOLS:$ANDROID_SDK/platform-tools"

echo ""
echo "[6/7] Installing Android SDK packages..."
yes | "$SDK_TOOLS/sdkmanager" --sdk_root="$ANDROID_SDK" --licenses 2>/dev/null || true
"$SDK_TOOLS/sdkmanager" --sdk_root="$ANDROID_SDK" \
    "platforms;android-35" \
    "build-tools;35.0.0" \
    "platform-tools"

echo ""
echo "[7/7] Setting permissions..."
chown -R "$WEB_USER:$WEB_USER" "$BUILD_HOME"
chmod -R 755 "$BUILD_HOME"

# Create environment file for the web server
cat > /etc/profile.d/android-build.sh << EOF
export JAVA_HOME="$JAVA_HOME_PATH"
export ANDROID_HOME="$ANDROID_SDK"
export ANDROID_SDK_ROOT="$ANDROID_SDK"
export GRADLE_HOME="$GRADLE_HOME"
export PATH="\$PATH:\$GRADLE_HOME/bin:\$ANDROID_SDK/cmdline-tools/latest/bin:\$ANDROID_SDK/platform-tools"
EOF

chmod +x /etc/profile.d/android-build.sh

# Create a config file the PHP app can read
cat > "$BUILD_HOME/build_env.conf" << EOF
JAVA_HOME=$JAVA_HOME_PATH
ANDROID_HOME=$ANDROID_SDK
ANDROID_SDK_ROOT=$ANDROID_SDK
GRADLE_HOME=$GRADLE_HOME
PATH=$GRADLE_HOME/bin:$SDK_TOOLS:$ANDROID_SDK/platform-tools:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
EOF

echo ""
echo "============================================"
echo "  Setup Complete!"
echo "============================================"
echo ""
echo "Installed:"
echo "  Java:       $(java -version 2>&1 | head -1)"
echo "  Gradle:     $($GRADLE_HOME/bin/gradle --version 2>/dev/null | grep 'Gradle ' | head -1)"
echo "  Android SDK: $ANDROID_SDK"
echo "  Build Tools: 35.0.0"
echo "  Platform:    android-35"
echo ""
echo "Paths:"
echo "  JAVA_HOME    = $JAVA_HOME_PATH"
echo "  ANDROID_HOME = $ANDROID_SDK"
echo "  GRADLE_HOME  = $GRADLE_HOME"
echo ""
echo "Next Steps:"
echo "  1. Update config.php:"
echo "     - Set ENABLE_APK_BUILD to true"
echo "     - Set ENABLE_GITHUB_BUILD to false"
echo "     - Set ANDROID_SDK_PATH to '$ANDROID_SDK'"
echo "     - Set JAVA_HOME to '$JAVA_HOME_PATH'"
echo "     - Set GRADLE_HOME to '$GRADLE_HOME'"
echo ""
echo "  2. Test the build environment:"
echo "     php diagnostic.php (from command line)"
echo ""
