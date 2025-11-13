## Resources Custom Post Type

A lean, extensible plugin that registers a `Resources` custom post type and exposes a `[latest_resources]` shortcode to render the latest items in a responsive grid. Built with senior-level patterns: modular includes, activation/deactivation safety, environment-aware assets, escape/sanitize rigor, and developer hooks for customization.

### Highlights

- **Custom Post Type**: `resources` with title, excerpt, featured image, and REST support.
- **Shortcode**: `[latest_resources limit="5"]` with responsive markup and lazy-loaded images.
- **Fallback Image**: Admin-selectable image used when a resource lacks a featured image.
- **Performance**: Uses `.min.css` in production and `filemtime`-based cache-busting in debug.
- **Extensibility**: Multiple filters to adjust args, classes, item data, and empty states.
- **Standards**: Escaping, sanitization, and clear separation of concerns.

---

## Requirements

- WordPress 5.8+ (REST and media enhancements assumed)
- PHP 7.4+ (tested against newer versions)

---

## Installation

1. Zip the plugin directory and upload via:

- WP Admin → Plugins → Add New → Upload Plugin → Choose zip → Install → Activate

or

2. Manual:

- Copy the folder to `/wp-content/plugins/resources-cpt/`
- Activate via WP Admin → Plugins

Activation flushes rewrite rules for the CPT; deactivation flushes them again.

---

## Configuration

### Fallback image

- Go to WP Admin → Resources → Settings.
- Select a fallback image from the media library.
- Saved as option: `resources_cpt_fallback_image_id`.
- Used automatically when a `Resource` doesn’t have a featured image; if not set, a bundled SVG placeholder is used.

---

## Usage

### Create resources

1. WP Admin → Resources → Add New
2. Provide Title, Featured Image (optional), and Excerpt (optional)
3. Publish

### Display resources

Place the shortcode in any post/page/block:

```
[latest_resources limit="5"]
```

Parameters:

- `limit` (int, optional): number of posts to render (default: 5)

Examples:

```
[latest_resources]
[latest_resources limit="3"]
[latest_resources limit="12"]
```

---

## Developer Notes

### Plugin structure

```
resources-cpt/
├── resources-cpt.php                  # bootstrap: constants, i18n, hooks, includes
├── includes/
│   ├── cpt.php                        # CPT registration + filters
│   ├── assets.php                     # env-aware enqueue (minified in production)
│   ├── shortcode.php                  # query, render, shortcode handler
│   └── admin.php                      # settings submenu + media picker
├── assets/
│   ├── css/
│   │   ├── resources-style.css        # readable styles
│   │   └── resources-style.min.css    # minified for production
│   ├── images/
│   │   └── placeholder.svg            # ultimate fallback if no admin selection
│   └── js/
│       └── admin-settings.js          # media picker wiring for fallback image
└── README.md
```

### Filters

- `resources_cpt_register_args` — filter CPT registration args.
- `resources_cpt_query_args` — filter query args for the latest resources.
- `resources_cpt_item_data` — filter prepared item data prior to render.
- `resources_cpt_container_class` — filter outer container class.
- `resources_cpt_grid_class` — filter grid class.
- `resources_cpt_empty_message` — filter empty state text.
- `resources_cpt_fallback_image_src` — filter ultimate fallback URL (when no selection exists).

### Assets

- Always loads `resources-style.min.css` for best performance.
- Versions via `filemtime()` when available to avoid stale caches without sacrificing minification.

### Markup and accessibility

- Uses semantic `article`, headings, and links.
- Images are `loading="lazy"` and include `alt`. Fallback images inherit a sensible `alt` based on the title.
- Responsive layout via CSS Grid; images use `sizes` and WP-generated `srcset` when possible.

### Security

- All user-provided input is sanitized (e.g., `absint`).
- All output is escaped (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).
- Direct access is guarded (`ABSPATH` check).

---

## Changelog

- 1.1.01

  - Split code into `includes/` (CPT, assets, shortcode, admin).
  - Added settings page to select a fallback image.
  - Responsive images (`sizes`) and lazy-loading.
  - Minified CSS for production; cache-busting in debug.
  - Developer filters for high extensibility.

- 1.0.0
  - Initial release with CPT and shortcode.

---

## License

GPL v2 or later
