# Humanity Theme
test PR
This is the home of Amnesty International's WordPress theme, which is currently in production use on over 30 sites.  
If you'd like to use it yourself, see the [usage](#usage) section.  
If you'd like to contribute to the theme, see the [contributing](#contributing).  

## Minimum Requirements
This theme requires:
- WordPress 5.8+
- PHP 8.2+ with the Intl extension

## Required Plugins  
We currently rely upon CMB2 and CMB2 extensions for settings management, but our eventual goal is to remove these dependencies.  
Our full list of dependencies is below:  
- [CMB2](https://github.com/CMB2/CMB2)  
- [CMB2 Attached Posts](https://github.com/CMB2/cmb2-attached-posts)  
- [CMB2 Message Field](https://github.com/amnestywebsite/cmb2-message-field)  
- [CMB2 Password Field](https://github.com/amnestywebsite/cmb2-password-field)  
- [CMB2 Sort Field](https://github.com/jonmcp/cmb2-field-order)  

## Plugin Integrations
This theme hooks into the following plugins, should they be available:
- [WordPress SEO](https://wordpress.org/plugins/wordpress-seo/)
- [MultilingualPress](https://multilingualpress.org/)
- [Multisite Global Media](https://github.com/bueltge/multisite-global-media/)

## Companion Plugins  
Plugins which can be used to extend the theme with additional functionality, originally designed specifically for Amnesty International.  

### Donations  
The [Donations](https://github.com/amnestywebsite/humanity-donations) plugin works in conjunction with WooCommerce to provide the capability to accept one-off and recurring donations.  

### Petitions  
The [Petitions](https://github.com/amnestywebsite/humanity-petitions) plugin provides the capability to create, curate, and manage petitions.  

### Image Credit
The [Image Credit](https://github.com/amnestywebsite/image-credit) plugin adds support to the theme for automatic output of media copyright information (from the image description field) on the site frontend. The plugin uses a lookup table, which can be pre-populated using WP CLI, to make the image lookup from its URI blisteringly fast, instead of the much slower meta lookup.

### Media Copyright  
The [Media Copyright](https://github.com/amnestywebsite/media-copyright) plugin will ensure that images that do not have copyright attribution are not allowed to display on the site.  

## Usage
The quickest way to get started using the theme is to download the zip of the [latest release](https://github.com/amnestywebsite/humanity-theme/releases/latest), and install it via upload directly within WP Admin -> Themes.  
We recommend your site be configured as a [multisite](https://wordpress.org/support/article/create-a-network/), both for future-proofing, and for more granular user permissions control. Many of the theme's customisation options make more sense at the network-level, too.

## Governance
See [GOVERNANCE.md](GOVERNANCE.md) for project governance information.  

## Changelog  
See [CHANGELOG.md](CHANGELOG.md) or [Releases page](https://github.com/amnestywebsite/humanity-theme/releases) for full changelogs.

## Contributing
For information on how to contribute to the project, or to get set up locally for development, please see the documentation in [CONTRIBUTING.md](CONTRIBUTING.md).  

### Special Thanks
We'd like to say a special thank you to these lovely folks:

| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Cure53](https://cure53.de)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[WP Engine](https://wpengine.com)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |
| --- | --- |
| ![Cure53](./docs/static/cure_53_logo.svg) | ![WP Engine](./docs/static/wpengine_logo.svg) |

### Want to know more about the work in other Amnesty GitHub accounts?  

You can find repositories from other teams such as [Amnesty Web Ops](https://github.com/amnestywebsite), [Amnesty Crisis](https://github.com/amnesty-crisis-evidence-lab), [Amnesty Tech](https://github.com/AmnestyTech), and [Amnesty Research](https://github.com/amnestyresearch/) in their GitHub accounts

![AmnestyWebsiteFooter](https://wordpresstheme.amnesty.org/wp-content/uploads/2024/02/footer.gif)
