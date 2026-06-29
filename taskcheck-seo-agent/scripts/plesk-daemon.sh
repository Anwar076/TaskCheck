#!/usr/bin/env bash
# Start SEO agent 24/7 op Plesk (git clone, niet httpdocs)
set -euo pipefail

# Pas aan naar jouw Plesk pad:
REPO_ROOT="${GIT_REPO_ROOT:-$HOME/git/laravel_e55cd2}"
AGENT_DIR="$REPO_ROOT/taskcheck-seo-agent"

if [[ ! -d "$REPO_ROOT/.git" ]]; then
  echo "Geen git repo op: $REPO_ROOT"
  echo "Zet GIT_REPO_ROOT naar je Plesk git map (bijv. ~/git/laravel_e55cd2)"
  exit 1
fi

if [[ ! -d "$AGENT_DIR" ]]; then
  echo "taskcheck-seo-agent niet gevonden in $REPO_ROOT"
  echo "Voer uit: cd $REPO_ROOT && git pull origin main"
  exit 1
fi

cd "$AGENT_DIR"

if [[ ! -d venv ]]; then
  echo "venv aanmaken..."
  curl -sS -o virtualenv.pyz https://bootstrap.pypa.io/virtualenv.pyz
  python3 virtualenv.pyz venv
  source venv/bin/activate
  pip install -r requirements.txt
else
  source venv/bin/activate
fi

# Zorg dat .env GIT_REPO_ROOT heeft
if ! grep -q "^GIT_REPO_ROOT=" .env 2>/dev/null; then
  echo "GIT_REPO_ROOT=$REPO_ROOT" >> .env
fi

export GIT_REPO_ROOT="$REPO_ROOT"
export PUBLISH_MODE="${PUBLISH_MODE:-git_only}"

echo "Start SEO agent — repo: $REPO_ROOT"
exec python run.py daemon
