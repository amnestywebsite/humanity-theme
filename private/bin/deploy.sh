#!/usr/bin/env bash

# exit on error
set -e

# vaguely posix-compliant way of retrieving last command
last_command() {
  (fc -n -l -1 -1)
}

# output error message before throwing
error_out() {
  printf '%s failed with exit code %s.' "$(last_command)" "$?;"
}

# trap errors from any failing command
trap error_out 1 2 3 6

# check environment variable validity
if [ -z "$REMOTE_URL" ] || [ -z "$GITHUB_USERNAME" ] || [ -z "$GITHUB_EMAIL" ]; then
  printf "Environment variables not configured.\n"
  exit 1
fi

# known hosts setup
cat "$PWD/private/known_hosts" >> ~/.ssh/known_hosts

# directory setup
theme_dir="wp-content/themes/humanity-theme"
source_dir="${TRAVIS_BUILD_DIR:-$PWD}/${theme_dir}"
temp_dir="$(mktemp -d 2> /dev/null || mktemp -d -t "$TRAVIS_BRANCH")"
cd "$temp_dir"

# bring in repo from remote
git clone --depth=50 "$REMOTE_URL" "$temp_dir"
git config user.name "$GITHUB_USERNAME"
git config user.email "$GITHUB_EMAIL"

# ensure target dir exists for rsync
mkdir -p "$temp_dir/${theme_dir}"

# copy deployment files
rsync --delete -a "$source_dir/" "$temp_dir/${theme_dir}" --exclude='.git/'

# stage all changes
git add --all .

# nothing has changed, don't bother committing
if [ -z "$(git status --porcelain)" ]; then
  echo "Nothing to deploy..."
  exit 0
fi

# commit && deploy
commit_message="$(printf "Travis CI build number %s\n\nCommit range: %s" "$TRAVIS_BUILD_NUMBER" "$TRAVIS_COMMIT_RANGE")"
git commit --author="$GITHUB_USERNAME <$GITHUB_EMAIL>" -m "$commit_message"
git push origin master > /dev/null 2>&1 || exit 1

echo "Deployment complete."
