#!/usr/bin/env bash

# exit when any command fails
set -eo pipefail

composer install
nvm install 20 && nvm use 20
corepack enable
cd private && yarn && cd ..

echo "Setup complete."
