param(
    [int]$Port = 8080,
    [string]$ListenHost = "127.0.0.1"
)

$ProjectRoot = $PSScriptRoot
$PhpPath = Join-Path $ProjectRoot "globalnews-dev\php\php.exe"
$WordPressPath = Join-Path $ProjectRoot "globalnews-dev\wordpress"
$RouterPath = Join-Path $ProjectRoot "router.php"

if (-not (Test-Path $PhpPath)) { Write-Error "PHP not found at $PhpPath"; exit 1 }
if (-not (Test-Path (Join-Path $WordPressPath "index.php"))) { Write-Error "WordPress not found at $WordPressPath"; exit 1 }

# Check if port is in use
$existing = netstat -ano | Select-String "${ListenHost}:${Port}"
if ($existing) {
    Write-Host "Warning: Port $Port is in use." -ForegroundColor Yellow
    $existingPid = ($existing -split '\s+')[-1]
    Write-Host "  PID $existingPid is using it." -ForegroundColor Yellow
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  GlobalNews Media - Local Development" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Site:     http://${ListenHost}:${Port}/" -ForegroundColor Green
Write-Host "Admin:    http://${ListenHost}:${Port}/wp-admin/" -ForegroundColor Green
Write-Host "Username: admin" -ForegroundColor White
if ($env:GLOBALNEWS_ADMIN_PASSWORD) { Write-Host "Password: $($env:GLOBALNEWS_ADMIN_PASSWORD)" -ForegroundColor White }
else { Write-Host "Password: (set via `$env:GLOBALNEWS_ADMIN_PASSWORD)" -ForegroundColor Gray }
Write-Host ""
Write-Host "Press Ctrl+C to stop the server." -ForegroundColor Yellow
Write-Host ""

# Use WordPress as document root with router for URL rewriting
Push-Location -LiteralPath $WordPressPath; try { & $PhpPath -S "${ListenHost}:${Port}" "$RouterPath" } finally { Pop-Location }
