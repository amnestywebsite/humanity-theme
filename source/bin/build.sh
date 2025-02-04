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

# refresh POMO, build zip
build_zip() {
  ./private/bin/lang.sh
  cd ./wp-content/themes/humanity-theme
  zip -r ../../../humanity-theme.zip ./*
}

# trap errors from any failing command
trap error_out 1 2 3 6

# build assets
cd ./private
yarn build
cd ..

# build archive on main only
case "$TRAVIS_BRANCH" in
  main) build_zip ;;
  *) ;;
esac

echo "Build complete."
