# TaskCheck SEO Agent — 24/7 daemon (Windows)
# Dubbelklik of: powershell -ExecutionPolicy Bypass -File start-daemon.ps1

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $Root

$Python = Join-Path $Root "venv\Scripts\python.exe"
if (-not (Test-Path $Python)) {
    Write-Host "venv niet gevonden. Eerst: python -m venv venv && .\venv\Scripts\pip install -r requirements.txt"
    exit 1
}

Write-Host "TaskCheck SEO Agent daemon starten (bot + scheduler + kans-alerts)..."
Write-Host "Stop met Ctrl+C. Voor echte 24/7: zet dit script in Windows Taakplanner of NSSM."

while ($true) {
    try {
        & $Python run.py daemon
        $code = $LASTEXITCODE
    } catch {
        $code = 1
        Write-Host "Crash: $_"
    }
    if ($code -eq 0) {
        Write-Host "Daemon gestopt (exit 0)."
        break
    }
    Write-Host "Herstart over 10 seconden..."
    Start-Sleep -Seconds 10
}
