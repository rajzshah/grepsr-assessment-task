# Resources Custom Post Type Plugin

A WordPress plugin that creates a custom post type for "Resources" and provides a shortcode to display the latest resources in a responsive grid/list layout.

## Features

- **Custom Post Type**: "Resources" with support for:
  - Title
  - Featured Image
  - Short Description/Excerpt
  
- **Shortcode**: `[latest_resources limit="5"]` to display resources anywhere on your site
  - Responsive grid layout
  - Clean, modern design
  - Fully accessible

- **WordPress Best Practices**:
  - Follows WordPress coding standards
  - Proper use of hooks and filters
  - All output sanitized and escaped for security
  - Gutenberg/Block Editor support
  - Translation ready

## Installation

### Method 1: Upload as ZIP (Recommended for Assessment)

1. Zip the entire plugin folder (resources-cpt.php and assets folder)
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the ZIP file and click "Install Now"
4. Activate the plugin

### Method 2: Manual Installation

1. Upload the entire plugin folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

### Method 3: Add to Theme (Alternative)

If you prefer to add this to your theme instead of a plugin:

1. Copy `resources-cpt.php` to your theme's `functions.php` or create a separate file and include it
2. Copy the `assets` folder to your theme directory
3. Update the CSS path in the `resources_cpt_enqueue_styles()` function to match your theme structure

## Usage

### Creating Resources

1. After activating the plugin, you'll see a new "Resources" menu item in your WordPress admin
2. Click "Add New" to create a new resource
3. Add a title, featured image, and excerpt/short description
4. Publish the resource

### Displaying Resources

Use the shortcode anywhere on your site:

```
[latest_resources limit="5"]
```

**Parameters:**
- `limit` (optional): Number of resources to display (default: 5)

**Examples:**
```
[latest_resources]
[latest_resources limit="10"]
[latest_resources limit="3"]
```

## File Structure

```
resources-cpt/
├── resources-cpt.php          # Main plugin file
├── assets/
│   └── css/
│       └── resources-style.css # Stylesheet for responsive display
└── README.md                   # This file
```

## Security Features

- All user input is sanitized using `absint()` for numeric values
- All output is escaped using `esc_html()`, `esc_url()`, `esc_attr()`, and `wp_kses_post()`
- Direct file access is prevented with `ABSPATH` check
- Follows WordPress nonce and capability best practices

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design works on all screen sizes
- Mobile-friendly grid layout

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Author

Created for WordPress Developer Assessment

## License

GPL v2 or later

