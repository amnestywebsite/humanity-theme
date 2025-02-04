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

# download phpDocumentorx
download_phpdoc() {
  wget -q https://github.com/phpDocumentor/phpDocumentor/releases/latest/download/phpDocumentor.phar
}

# generate the documentation using phpDocumentor
generate_docs() {
  php phpDocumentor.phar \
    --cache-folder="$HOME/phpdoc-cache" \
    --extensions=php \
    --defaultpackagename=Humanity \
    --directory ./wp-content/themes/humanity-theme \
    --target ./php-api-developer-documentation
}

# package up the documentation
zip_docs() {
  zip -r ./php-api-developer-documentation.zip ./php-api-developer-documentation
  rm -r ./php-api-developer-documentation/
}

# run the build
build_docs() {
  download_phpdoc
  generate_docs
  zip_docs
}

# trap errors from any failing command
trap error_out 1 2 3 6

# build archive on main only
case "$TRAVIS_BRANCH" in
  main) build_docs ;;
  *) ;;
esac

echo "Documentation generation complete."
