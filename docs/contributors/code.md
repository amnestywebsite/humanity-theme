# Welcome to the Development Contribution Guide
Here you'll find information on how to get started with contributing code to the project.  
We also welcome non-code contributions, such as [documentation](https://github.com/amnestywebsite/humanity-theme/blob/main/docs/contributors/docs.md), [triage](https://github.com/amnestywebsite/humanity-theme/blob/main/docs/contributors/triage.md), and [testing](https://github.com/amnestywebsite/humanity-theme/blob/main/docs/contributors/a11y.md).  

## Getting Started
This guide assumes you have an available Virtual Host or Docker environment through which you can run the project locally.  

### Prerequisites
- [`PHP`](https://www.php.net/) v8.2.*  
- [`Composer`](https://getcomposer.org/) v2+  
- [`Node`](https://nodejs.org/en/) v22+  
- [`Yarn`](https://yarnpkg.com/) v4+  
- [`Docker`](https://docs.docker.com/engine/install/) Optional, in case you would like to use `@wordpress/env` to run a local environment.

#### Setting Up
- Clone the repo into your chosen directory.  
- Using [WP CLI](https://developer.wordpress.org/cli/commands/), install WordPress into the same directory as the repo: `wp core download --skip-content`.  
- Install PHP dependencies: `composer install`  
  This step installs our [PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer) toolchain, and will allow you to scan your code changes for any stylistic incompatibilities. It's important to run PHPCS (`composer lint`) prior to creating any Pull Requests, as PRs are auto-rejected if any issues are found.  
- Install and build the theme assets: `cd ./private && yarn && yarn build`  
  This project requires node v22+; you may need to enable corepack first: `corepack enable`.  
- Download any Required or Recommended Plugins into the `./wp-content/plugins` directory, or add them to `.wp-env.json` if using `@wordpress/env` as a development environment. Follow plugin instructions for installation steps.

#### Local development environment with `@wordpress/env`
If you choose to, you can use `@wordpress/env` which will provision a local development environment using Docker. 
To get started run `yarn env start` from within the `./private` directory.

The site will be available at http://localhost:8888
You can login at http://localhost:8888/wp-admin with username `admin` and password `password`.

> Note: `yarn env` is an alias for commands available with `@wordpress/env`.
> For more information, [read here](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/).

### Submitting Issues or Feature Requests
When submitting tickets, please give a detailed description of the issue in question along with where the issue was found; steps to replicate; and, where possible, a screenshot of the issue.  

When requesting a feature, create a discussion in the same way you would an issue, minus the steps to replicate and screenshot; a detailed description of the feature, and your expectations, is required.  

## Working with Issues
When working on an issue, assign yourself to the ticket, and be sure to update the status of the ticket so that it moves along the project board e.g. (To Do, In Progress, PR Created etc).  

### Branching
Branches should have short, relevant names, and be prefixed with their type. e.g. `feature/main-nav`, `hotfix/menu-z-index`, `chore/package-update`.  
All branches should be taken from `main`.  
All branches should be Pull Requested into `develop`.  
No code should be committed directly to `main`, `staging`, or `develop`.  

Primary branches are listed in the [branch model](#branch-model) section.  

### Developing Code
Once you have the project installed and set up, and you have branched off from `main` onto your task branch, you're ready to start coding. There are few things we'd appreciate you bearing in mind whilst writing code, and we provide tools which should help you with that.  
We have strict stylistic requirements for SCSS, JavaScript, and PHP, with Shell Script requirements on the roadmap. To make these requirements easier to implement, we provide automated linting of SCSS and JavaScript as part of our build process. Unfortunately PHP linting is more manual but, once you have the config installed (see [Before Setup](#before-setup)), you can automate this through configuring your code editor.  
When working with SCSS or JavaScript, we have several commands you can run from within the `./private` directory for compilation, which will also warn you about any stylistic inconsistencies:  
- `yarn build:dev` will compile all assets once (CSS, JS, etc.) in development mode, which includes good sourcemapping and unminified code  
- `yarn build:prod` will compile all assets once in production mode, which includes fast sourcemapping and minified code  
- `yarn build:static` will compile only static assets (assets that do not become part of larger bundles, sourced from `./private/src/static`) once in production mode  
- `yarn build` is an alias for `yarn build:prod`  
- `yarn watch:dev` will compile all assets in development mode, and will "watch" source files for changes, which will trigger automatic recompilation. This can be cancelled with `Ctrl+c`  
- `yarn watch:prod` will compile all assets in production mode, and will "watch" source files for changes, which will trigger automatic recompilation. This can be cancelled with `Ctrl+c`  
- `yarn watch` is an alias for `yarn watch:dev`  
- `yarn lint:scripts` will check all source JavaScript files for stylistic (or other) errors  
- `yarn lint:styles` will check all source SCSS files for stylistic (or other) errors  
- `yarn lint` will trigger `yarn lint:scripts`, and `yarn lint:styles`  

### Before Committing
Prior to committing any code, please ensure that both `yarn lint` and `composer lint` report no errors. This will save time in the long run.  

#### Commit Messages
We follow the commit message standards outlined in detail in [this excellent post](https://cbea.ms/git-commit/) by CBEAMS.  

### Creating Pull Requests
Pull Requests should always be made to `develop`.  
Please include the following detail:  
- A link to the GitHub Issue  
- A brief description of what the code in the PR does  
- Information on how to test the changes made by the PR  
- Video demonstrating the code  

## Branch Model

### `main`
The "source of truth". Any and all branches should be created using `main` as a base.  
This branch auto-deploys to GitHub Releases (draft).  
Only branches that meet all of the following criteria should be merged into `main`:  
- Pull Request has been approved  
- Internal Testing has passed  
- UAT has passed  
- Release Candidate has been signed off  

### `staging`
Only branches that meet all of the following criteria should be merged into `staging`:  
- Pull Request has been approved  
- Internal Testing has passed  

### `develop`
Target branch for Pull Requests.  
Only branches that have met all of the following criteria should be merged into `develop`:  
- Successful local environment test by code owner  
- Pull Request has been approved  

## Releases

As mentioned in the [Branch Model](#branch-model) section, commits to [`main`](#main) trigger a build in Travis CI that creates a draft release in GitHub. Only one draft can be present at a time, so new commits will overwrite the existing draft.  

When creating a new release, the final commit to `main` should contain the appropriate version bump in [`/wp-content/themes/humanity-theme/style.css`](https://github.com/amnestywebsite/humanity-theme/blob/main/wp-content/themes/humanity-theme/style.css), and an update to the changelog at [`/CHANGELOG.md`](https://github.com/amnestywebsite/humanity-theme/blob/main/CHANGELOG.md).  

The tag for the release — and the release title — should match the version specified in both the changelog and stylesheet. The release notes should match those in the changelog. See existing releases for examples.  
