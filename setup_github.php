<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Setup Guide - WebToAPK Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { max-width: 800px; margin: 0 auto; }
        
        h1 { 
            font-size: 32px; 
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        h1 i { color: #6366f1; }
        
        .subtitle {
            color: #94a3b8;
            margin-bottom: 40px;
        }
        
        .card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            font-weight: 700;
            margin-right: 15px;
        }
        
        .step-title {
            display: flex;
            align-items: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
        
        .step-content {
            padding-left: 55px;
            color: #94a3b8;
            line-height: 1.8;
        }
        
        .step-content a {
            color: #6366f1;
            text-decoration: none;
        }
        .step-content a:hover {
            text-decoration: underline;
        }
        
        .code-block {
            background: #0f172a;
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        
        .code-block .comment { color: #64748b; }
        .code-block .string { color: #10b981; }
        .code-block .keyword { color: #f59e0b; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }
        
        .warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 10px;
            padding: 15px 20px;
            margin: 15px 0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .warning i { color: #f59e0b; font-size: 20px; margin-top: 2px; }
        .warning-text { color: #fbbf24; }
        
        .success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 10px;
            padding: 15px 20px;
            margin: 15px 0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .success i { color: #10b981; font-size: 20px; margin-top: 2px; }
        .success-text { color: #34d399; }
        
        .screenshot {
            background: #1e293b;
            border-radius: 10px;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
        }
        .screenshot img {
            max-width: 100%;
            border-radius: 8px;
        }
        
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        li { margin: 8px 0; }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            text-decoration: none;
            margin-bottom: 30px;
        }
        .back-link:hover { color: white; }
        
        .status-box {
            background: #0f172a;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .status-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .status-item:last-child { border-bottom: none; }
        .status-icon.ok { color: #10b981; }
        .status-icon.error { color: #ef4444; }
        .status-icon.pending { color: #f59e0b; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to App
        </a>
        
        <h1><i class="fab fa-github"></i> GitHub Actions Setup</h1>
        <p class="subtitle">FREE APK Building using GitHub Actions - Setup in 5 minutes!</p>
        
        <!-- Current Status -->
        <div class="card">
            <h2 style="margin-bottom: 20px;">Current Status</h2>
            <div class="status-box">
                <?php require_once 'config.php'; ?>
                <div class="status-item">
                    <i class="fas fa-<?php echo ENABLE_GITHUB_BUILD ? 'check-circle status-icon ok' : 'times-circle status-icon error'; ?>"></i>
                    <span>GitHub Build: <?php echo ENABLE_GITHUB_BUILD ? 'Enabled' : 'Disabled'; ?></span>
                </div>
                <div class="status-item">
                    <i class="fas fa-<?php echo !empty(GITHUB_TOKEN) ? 'check-circle status-icon ok' : 'times-circle status-icon error'; ?>"></i>
                    <span>GitHub Token: <?php echo !empty(GITHUB_TOKEN) ? 'Configured' : 'Not Set'; ?></span>
                </div>
                <div class="status-item">
                    <i class="fas fa-<?php echo !empty(GITHUB_OWNER) ? 'check-circle status-icon ok' : 'times-circle status-icon error'; ?>"></i>
                    <span>GitHub Username: <?php echo !empty(GITHUB_OWNER) ? GITHUB_OWNER : 'Not Set'; ?></span>
                </div>
            </div>
        </div>
        
        <!-- Step 1 -->
        <div class="card">
            <div class="step-title">
                <span class="step-number">1</span>
                Create GitHub Account (if needed)
            </div>
            <div class="step-content">
                <p>If you don't have a GitHub account, create one for free:</p>
                <br>
                <a href="https://github.com/signup" target="_blank" class="btn">
                    <i class="fab fa-github"></i> Create GitHub Account
                </a>
            </div>
        </div>
        
        <!-- Step 2 -->
        <div class="card">
            <div class="step-title">
                <span class="step-number">2</span>
                Create Personal Access Token
            </div>
            <div class="step-content">
                <p>Go to GitHub and create a Personal Access Token:</p>
                <br>
                <a href="https://github.com/settings/tokens/new" target="_blank" class="btn">
                    <i class="fas fa-key"></i> Create Token
                </a>
                
                <br><br>
                <p><strong>Settings to use:</strong></p>
                <ul>
                    <li><strong>Note:</strong> WebToAPK Builder</li>
                    <li><strong>Expiration:</strong> 90 days (or "No expiration")</li>
                    <li><strong>Scopes:</strong> Check these boxes:
                        <ul>
                            <li>✅ <code>repo</code> (Full control of private repositories)</li>
                            <li>✅ <code>workflow</code> (Update GitHub Action workflows)</li>
                        </ul>
                    </li>
                </ul>
                
                <div class="warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="warning-text">
                        <strong>Important!</strong> Copy the token immediately after creation. You won't be able to see it again!
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Step 3 -->
        <div class="card">
            <div class="step-title">
                <span class="step-number">3</span>
                Update config.php
            </div>
            <div class="step-content">
                <p>Open <code>config.php</code> and update these lines:</p>
                
                <div class="code-block">
<span class="comment">// Enable GitHub build</span>
<span class="keyword">define</span>(<span class="string">'ENABLE_GITHUB_BUILD'</span>, <span class="keyword">true</span>);

<span class="comment">// Your token from Step 2</span>
<span class="keyword">define</span>(<span class="string">'GITHUB_TOKEN'</span>, <span class="string">'ghp_xxxxxxxxxxxxxxxxxxxx'</span>);

<span class="comment">// Your GitHub username</span>
<span class="keyword">define</span>(<span class="string">'GITHUB_OWNER'</span>, <span class="string">'your-username'</span>);
                </div>
                
                <div class="success">
                    <i class="fas fa-check-circle"></i>
                    <div class="success-text">
                        <strong>That's it!</strong> After saving config.php, the website will automatically build APKs using GitHub Actions.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- How it works -->
        <div class="card">
            <div class="step-title">
                <span class="step-number"><i class="fas fa-cogs"></i></span>
                How It Works
            </div>
            <div class="step-content">
                <ol style="padding-left: 20px;">
                    <li>User submits URL and app details on your website</li>
                    <li>Your server generates Android project files</li>
                    <li>Project is automatically pushed to a new GitHub repository</li>
                    <li>GitHub Actions workflow starts building the APK</li>
                    <li>APK is uploaded to GitHub Releases (ready in ~3-5 min)</li>
                    <li>User downloads APK from GitHub Releases</li>
                </ol>
                
                <br>
                <p><strong>Benefits:</strong></p>
                <ul>
                    <li>✅ Completely FREE (2000 build minutes/month)</li>
                    <li>✅ No VPS or Android SDK required</li>
                    <li>✅ Automatic - no manual steps needed</li>
                    <li>✅ APKs are hosted on GitHub (reliable)</li>
                </ul>
            </div>
        </div>
        
        <!-- FAQ -->
        <div class="card">
            <div class="step-title">
                <span class="step-number"><i class="fas fa-question"></i></span>
                FAQ
            </div>
            <div class="step-content">
                <p><strong>Q: Is this really free?</strong></p>
                <p>Yes! GitHub Actions offers 2000 free minutes per month. Each APK build takes ~3-5 minutes, so you can build 400-600 APKs per month for free.</p>
                
                <br>
                <p><strong>Q: Why does it take 3-5 minutes?</strong></p>
                <p>GitHub needs to set up Android SDK, download dependencies, and compile the app. This is a one-time process for each build.</p>
                
                <br>
                <p><strong>Q: Will the APK repos be public?</strong></p>
                <p>By default yes (free tier). For private repos, you need GitHub Pro ($4/month).</p>
                
                <br>
                <p><strong>Q: Can I delete old repos?</strong></p>
                <p>Yes! You can delete old APK repositories anytime from your GitHub account to keep things clean.</p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="btn">
                <i class="fas fa-arrow-left"></i> Back to App Generator
            </a>
        </div>
    </div>
</body>
</html>
