param(
    [int]$Port = 8080,
    [string]$ListenHost = "127.0.0.1",
    [string]$SiteTitle = "GlobalNews Media",
    [string]$AdminUser = "admin",
    [string]$AdminPassword = "",
    [string]$AdminEmail = "admin@globalnewsmedia.com"
)

# Resolve admin password: env var > parameter > prompt
if (-not $AdminPassword) { $AdminPassword = $env:GLOBALNEWS_ADMIN_PASSWORD }
if (-not $AdminPassword) {
    $AdminPassword = Read-Host -Prompt "Enter admin password" -AsSecureString
    $BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($AdminPassword)
    $AdminPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
    [System.Runtime.InteropServices.Marshal]::ZeroFreeBSTR($BSTR)
}

$ErrorActionPreference = "Stop"

$ProjectRoot = $PSScriptRoot
$PhpPath = Join-Path $ProjectRoot "globalnews-dev\php\php.exe"
$WordPressPath = Join-Path $ProjectRoot "globalnews-dev\wordpress"
$RouterPath = Join-Path $ProjectRoot "router.php"

if (-not (Test-Path $PhpPath)) { Write-Error "PHP not found at $PhpPath"; exit 1 }
if (-not (Test-Path (Join-Path $WordPressPath "index.php"))) { Write-Error "WordPress not found at $WordPressPath"; exit 1 }

# Check if port is in use
$existing = netstat -ano | Select-String "${ListenHost}:${Port}"
if ($existing) {
    Write-Host "Port $Port is in use. Stopping existing process..."
    $existingPid = ($existing -split '\s+')[-1]
    Stop-Process -Id $existingPid -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  GlobalNews Media - Automated Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Clean previous database for fresh install
$dbDir = Join-Path $WordPressPath "wp-content\database"
if (Test-Path $dbDir) { Remove-Item $dbDir -Recurse -Force; Write-Host "Removed old database" -ForegroundColor Gray }

# Start PHP server
Write-Host "Starting PHP server..." -ForegroundColor Yellow
$serverLog = Join-Path $ProjectRoot "server.log"
$serverErr = Join-Path $ProjectRoot "server_err.log"
Push-Location -LiteralPath $WordPressPath
$phpServer = Start-Process -NoNewWindow -PassThru -FilePath $PhpPath -ArgumentList "-S ${ListenHost}:${Port} `"$RouterPath`"" -WorkingDirectory $WordPressPath -RedirectStandardOutput $serverLog -RedirectStandardError $serverErr
Start-Sleep -Seconds 3

# Wait for server
$serverReady = $false
for ($i = 0; $i -lt 15; $i++) {
    try {
        $wc = New-Object System.Net.WebClient
        $testContent = $wc.DownloadString("http://${ListenHost}:${Port}/")
        if ($testContent -match "WordPress.*Installation|install\.php") { $serverReady = $true; break }
    } catch {}
    Start-Sleep -Seconds 1
}
if (-not $serverReady) { Write-Error "PHP server failed to start"; Stop-Process -Id $phpServer.Id -Force; exit 1 }
Write-Host "✓ PHP server running at http://${ListenHost}:${Port}" -ForegroundColor Green

# Install WordPress
Write-Host "Installing WordPress..." -ForegroundColor Yellow
$postBody = "weblog_title=" + [System.Net.WebUtility]::UrlEncode($SiteTitle) + "&" +
    "user_name=" + [System.Net.WebUtility]::UrlEncode($AdminUser) + "&" +
    "admin_password=" + [System.Net.WebUtility]::UrlEncode($AdminPassword) + "&" +
    "admin_password2=" + [System.Net.WebUtility]::UrlEncode($AdminPassword) + "&" +
    "admin_email=" + [System.Net.WebUtility]::UrlEncode($AdminEmail) + "&" +
    "pw_weak=1&language=&Submit=" + [System.Net.WebUtility]::UrlEncode("Install WordPress")

try {
    $wc = New-Object System.Net.WebClient
    $wc.Headers.Add("Content-Type", "application/x-www-form-urlencoded")
    $response = $wc.UploadString("http://${ListenHost}:${Port}/wp-admin/install.php?step=2", "POST", $postBody)
    if ($response -match "Success|Log In|Already Installed") {
        Write-Host "✓ WordPress installed!" -ForegroundColor Green
    }
} catch { if ($_.Exception.Response.StatusCode -eq 302) { Write-Host "✓ WordPress installed" -ForegroundColor Green } }

Start-Sleep -Seconds 2

# Activate GlobalNews Media theme (direct DB update)
Write-Host "Activating GlobalNews Media theme..." -ForegroundColor Yellow
$dbPath = Join-Path $WordPressPath "wp-content\database\.ht.sqlite"
$phpCode = @'
$dbPath = '{DBPATH}';
try {
    $db = new PDO("sqlite:$dbPath");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("UPDATE wp_options SET option_value = 'globalnews-media' WHERE option_name IN ('template', 'stylesheet')");
    echo "Theme activated\n";
} catch (PDOException $e) { echo "Error: " . $e->getMessage() . "\n"; }
'@ -replace '{DBPATH}', $dbPath

$phpCodeFile = Join-Path $ProjectRoot "_activate.php"
Set-Content -Path $phpCodeFile -Value $phpCode
& $PhpPath -f $phpCodeFile
Remove-Item $phpCodeFile -Force

Write-Host "✓ GlobalNews Media theme activated!" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Site:     http://${ListenHost}:${Port}/" -ForegroundColor Green
Write-Host "Admin:    http://${ListenHost}:${Port}/wp-admin/" -ForegroundColor Green
Write-Host "Username: $AdminUser" -ForegroundColor White
Write-Host "Password: $AdminPassword" -ForegroundColor White
Write-Host ""
Write-Host "PHP PID: $($phpServer.Id)" -ForegroundColor Gray
Write-Host "Run 'start.ps1' to start again later." -ForegroundColor Gray
Write-Host ""
Write-Host "Press any key to stop the server..." -ForegroundColor Yellow

$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
Write-Host "Stopping server..." -ForegroundColor Yellow
Stop-Process -Id $phpServer.Id -Force
Write-Host "Done." -ForegroundColor Cyan
