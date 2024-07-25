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
