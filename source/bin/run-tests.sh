#!/usr/bin/env sh

# Download CLI binary
curl -sL https://github.com/ghost-inspector/node-ghost-inspector/releases/latest/download/ghost-inspector-linux --output ./private/bin/ghost-inspector

# Make it executable
chmod +x ./private/bin/ghost-inspector

# Travis CI will handle setting its API Key as an environment variable

echo "Test setup complete."

./private/bin/ghost-inspector suite execute "$1" --errorOnFail --errorOnScreenshotFail
