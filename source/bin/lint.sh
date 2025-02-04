#!/usr/bin/env sh

# exit when any command fails
set -e

# vaguely posix-compliant way of retrieving last command
last_command() {
  (fc -n -l -1 -1)
}

# output error message before throwing
error_out() {
  printf '%s failed with exit code %s.' "$(last_command)" "$?"
}

# trap errors from any failing command
trap error_out 1 2 3 6

# lint all the things
composer lint
cd ./private
yarn lint:scripts
yarn lint:styles
cd ..

if which shfmt > /dev/null; then
  for file in ./private/bin/*.sh; do
    shfmt -i 2 -ln=bash -bn -ci -sr -kp -d "$file" > /dev/null
  done
fi

if which shellcheck > /dev/null; then
  shellcheck ./private/bin/*
fi

echo "Linting complete."
