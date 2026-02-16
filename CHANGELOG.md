### v3.0.2
Fixes:
- Missing inputs on advanced search filters
- Erroneous printf statement

### v3.0.1
Fixes:
- Flags on section block when it contains a background image
- Remove WooCommerce cart fragments mod
- Hero block layout styling in both editor and frontend
- Remove remaining WooCommerce FSE templates & pattern
- Skip hero block rendering entirely if there's no content to render
- Incorrect references to the custom select block
- Incorrect CSS selector target in download block

Localisation:
- Add Slovenian translations

Improvements:
- Enable download block button colour configuration
- Replace references to deprecated editor components
- Update print stylesheet baseline

Build:
- Bump node version to 22
- Upgrade build dependencies

### v3.0.0
**Breaking Changes**:
- Remove WooCommerce support
- Remove checkbox group implementation (superseded by https://github.com/amnestywebsite/humanity-custom-select)

Fixes:
- RWD issues on Hero block in both editor and frontend
- Appearance of checkboxes and radios in the editor
- Remove superfluous document padding
- Remove top offset on overlay
- Remove whitespace trimming on Hero block

Improvements:
- Update cache key hashing to use more performant algo
- Remove current language from list of entity translations
- Update Hero block to allow other InnerBlocks

### v2.2.0
Fixes:
- Apply filters to cached data correctly

Features:
- Introduce update callback for term images (#694)
- Add pattern for listing an entity's list of translations (via MultilingualPress)
- Add pattern for an entity's "Index Number"
- Add a "small" variant of the Details block
- Introduce concept of language tiers (translation comprehensiveness)

Improvements:
- Add support for `hreflang` on `<a>` tags

Localisation:
- Add (partial) support for several languages:
  - Azerbaijani
  - Belarusian
  - Georgian
  - Kazakh
  - Kyrgyz
  - Russian
  - Tajik
  - Turkmen
  - Ukrainian
  - Uzbek

Build:
- Bump js-yaml from 4.1.0 to 4.1.1 in /private
- Bump brace-expansion from 1.1.11 to 1.1.12 in /private

CI:
- Add task to compile language files (MO, l10n.php, JSON)

### v2.1.8
Fixes:
- Ensure non-null return type when translating taxonomy REST base to its slug

### v2.1.7
Fixes:
- Type checking issue on featured image caption
- Image credit on Petition List block

### v2.1.6
Fixes:
- Post List block:
  - Object selection for non-default post-type
  - Output of tag data if there is no tag text
  - Output when a taxonomy's slug doesn't match its REST base
- Search pagination
- Errors in patterns when MultilingualPress is not active
- Text decoration on single template
- Image metadata appearance in both editor and frontend

### v2.1.5
Fixes:
- Constrain iframe width in sidebars
- Prevent array access error in download block
- Typo in pattern slug
- Incorrect date output in document pattern
- Prevent loading of back link pattern if no category found

### v2.1.4
Fixes:
- Several issues related to loading remote data on PDF template

CI:
- Update build targets

### v2.1.3
Localisation:
- Update ZH translations

### v2.1.2
Fixes:
- PHP Warning on search if no posts found
- Incorrect pattern usage in petitions template

### v2.1.1
Fixes:
- Remove rogue text decoration

### v2.1.0
Fixes:
- Single article actions layout
- Anchor point for nav submenus in RTL
- Text decoration
- Grid breakpoints for Post List blocks
- Back button iconography
- Icon spritesheet path
- Slider navigation arrow visibility

Build:
- Introduce l10n.php compilation task
- Windows asset paths

Localisation:
- Introduce support for ZH locales

Improvements:
- Rebuild search templates
- Remove search page from search results
- Prettify search filter results
- Limit archive card tag output to primary category

### v2.0.14
Fixes:
- Filter out non-public sites from language selector
- Filter out non-public sites from language banner

### v2.0.13
Fixes:
- Set title tag fallback to blog name

Localisation:
- Translate some strings into Thai

Build:
- Bump build dependencies to latest minor version
- Bump @babel/runtime
- Bump serialize-javascript

CI:
- Migrate from Travis CI to GitHub Actions

### v2.0.12
Fixes:
- GTM initialisation when not running Consent Mode

CI:
- Remove old deployment targets

### v2.0.11
Fixes:
- Incorrect slug handling in Petition List block
- Petition List block title truncation

Localisation:
- Add support for Thai language

CI:
- Update deployment targets

### v2.0.10
Fixes:
- Correctly localise pagination strings in some patterns
- Adjust grid calculations in Post List and Petition List blocks
- Remove hook from Hero that disables featured image output

Improvements:
- Add patterns to mimic the Background Media block

Localisation:
- Add support for Slovenian language

CI:
- Update deployment targets

### v2.0.9
Fixes:
- Avoid PHP Warnings when expected menus aren't defined

Improvements:
- Remove width constraint from Custom Card patterns
- Specify image aspect ratio in Custom Card patterns

Chores:
- Bump cross-spawn from 7.0.3 to 7.0.6

Localisation:
- Update an AR translation string
- Update some ES translation strings
- Update some RU translation strings
- Refresh POT, PO, and MO files

### v2.0.8
Fixes:
- Always output the post title in the single template

### v2.0.7
Fixes:
- Conditions under which the sidebar renders
- Filtering of "report" style taxonomy terms
- Article title font sizes
- Post list block appearance in the block editor
- Image copyright appearance on frontend

### v2.0.6
Fixes:
- Better support internal SP plugin on search page

### v2.0.5
Fixes:
- Typo in content maximisation conditionals
- Search page taxonomy filters
- Missing page title when page has no Hero

### v2.0.4
Fixes:
- Regression on featured images attached via global media library when Multisite Global Media is active

### v2.0.3
Fixes:
- Links in site footer not rendering correctly
- Inability to click Social Icons links
- Buttons block spacing in post content

### v2.0.2
Fixes:
- Site footer content now pulls from legacy Theme Options by default

### v2.0.1
Fixes:
- Filters on search results not being correctly applied

### v2.0.0
Features:
- **Breaking change**: All PHP templates have been replaced with FSE templates
- **Breaking change**: Typography moved to theme.json
- **Breaking change**: Colour scheme moved to theme.json
- Add wp-env support for development
- Many block patterns have been created
- Archive Filters SSR block
- Archive Header SSR block
- Query Count block
- Search Form SSR block
- Search Header SSR block
- Sidebar block

Fixes:
- Line height in Counter block
- Rendering of posts on author template

Improvements:
- Block restrictions have been removed
- Introduce fluid typography
- **Breaking change**: Custom skip link has been removed in favour of WP core's
- Add support for core/cover block
- Add support for core/quote block

Deprecations:
- Background Media block
- Call To Action block
- Collapsable block
- Custom Card block
- Links With Icons block

### v1.2.0
Features:
- Add Call to Action block patterns
- Add Audio/Quote block patterns

Fixes:
- Rendering issue in README.md
- Missing spacing above Blockquote block
- Layout of Latest Posts block in Sidebars
- Erroneous whitespace below Hero block

Chores:
- Move deprecated blocks to correct directory
- Bump Webpack version
- Bump Micromatch version

### v1.1.2
Fixes:
- Add missing viewport meta tags

### v1.1.1
Fixes:
- Remove Pop-in from FSE header template
- Post List content fields rendering in custom Grid mode
- Hero CTA button link functionality

### v1.1.0
Features:
- Introduce full site editing support
- Add FSE 404 template
- New Hero block to replace Header/Banner
- Add optional output of video credit/caption on Hero-style blocks
- Introduce new block pattern categories
- Add many block patterns

Fixes:
- Miscellaneous rendering issues in Hero-style blocks
- Rendering of Post List grid items in editor
- Missing URI path fallback in redirect helper
- Width of blocks in Sidebar
- Language code collisions
- Theme script translations

Improvements:
- Deprecate Links With Icons block
- Migrate font family declarations to theme.json
- Normalise gutters across existing PHP templates
- Remove old Social Share block pattern
- Register old blocks in PHP for FSE support
- Move SVG sprite to CSS var
- Remove unused metadata options from pages in block editor
- Adjust sorting of terms in Term List block
- Improve rendering of site logo in header
- Update composer dev dependencies
- Re-enable all core blocks
- Improve appearance of details block
- Remove old, unused recipients block

### v1.0.3
Fixes:
- Missing theme screenshot
- Flourish Embed HTTPS enforcement
- Iframe Button URI embedding
- Linting of JSX files
- ESLint violations
- Missing URI path in taxonomy redirect

### v1.0.2
Fixes:
- Potential collision in language handling when languages share an ISO 639-1 code
- Translations packs not loading correctly in some circumstances
- Missing whitespace after formatted text in Collapsable block

### v1.0.1
Fixes:
- Prevent featured image output if post has a header block
- Add fallback path to search permalink helper to prevent PHP Warning
- Remove unnecessary margin on Links With Icons block
- Resolve rendering and editing issues with Post List block in editor
- Remove paragraph width constraint
- Remove width constraint from site logo
- Ignore accents when sorting terms in A-Z block
- Increase size of buttons on A-Z block to distribute more sensibly
- Introduce gutters on post single

### v1.0.0
Initial release
