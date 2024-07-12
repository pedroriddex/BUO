# BUO Plugin Development Guidelines

## Introduction
BUO (Better User Organization) is a collection of WordPress plugins designed to enhance Google connectivity and simplify various aspects of web development with WordPress. This README provides comprehensive guidelines for developing plugins within the BUO ecosystem.

## Table of Contents
1. [Key Concepts](#key-concepts)
2. [Getting Started](#getting-started)
3. [Plugin Structure](#plugin-structure)
4. [BUO Integration](#buo-integration)
5. [Google Cloud Integration](#google-cloud-integration)
6. [Settings Management](#settings-management)
7. [Version Control and Updates](#version-control-and-updates)
8. [Localization](#localization)
9. [Asset Management](#asset-management)
10. [Security Considerations](#security-considerations)
11. [Performance Optimization](#performance-optimization)
12. [Testing](#testing)
13. [Documentation](#documentation)
14. [Submission Process](#submission-process)
15. [Best Practices](#best-practices)
16. [Troubleshooting](#troubleshooting)

## Key Concepts
- BUO plugins are hosted on GitHub and are external to the WordPress plugin directory.
- The core BUO plugin manages the integration for all plugins in the BUO collection.
- Each BUO plugin must adhere to specific naming conventions and structural requirements to integrate seamlessly with the BUO ecosystem.
- BUO provides centralized configuration for Google Cloud connection, which is utilized by all BUO plugins.
- BUO plugins are responsible for ensuring that the core BUO plugin is always up to date.
- Plugins should be capable of operating both within the BUO ecosystem and independently.

## Getting Started
1. Ensure you have a GitHub account and are familiar with Git version control.
2. Clone the BUO core repository to understand its structure and functionality.
3. Set up a local WordPress development environment.
4. Install the BUO core plugin in your local environment.

## Plugin Structure
```
your-plugin-name/
├── includes/
│   ├── class-your-plugin-main.php
│   ├── class-your-plugin-settings.php
│   └── [other PHP files]
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── templates/
├── languages/
├── your-plugin-name.php
└── README.md
```

## BUO Integration

### Main Plugin File (your-plugin-name.php)
```php
<?php
/**
 * Plugin Name: Your BUO Plugin Name
 * Plugin URI: https://github.com/your-repo/your-plugin
 * Description: A brief description of your plugin.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: your-plugin-textdomain
 * Domain Path: /languages
 * BUO: true
 */

if (!defined('ABSPATH')) {
    exit;
}

// BUO compatibility and update check
if (!class_exists('BUO_Core')) {
    add_action('admin_notices', 'your_plugin_buo_missing_notice');
    return;
} else {
    add_action('admin_init', 'your_plugin_check_buo_update');
}

function your_plugin_buo_missing_notice() {
    echo '<div class="error"><p>' . __('This plugin requires the BUO Core plugin to be installed and activated.', 'your-plugin-textdomain') . '</p></div>';
}

function your_plugin_check_buo_update() {
    // Implementation for checking BUO updates
}

// Plugin initialization
require_once plugin_dir_path(__FILE__) . 'includes/class-your-plugin-main.php';
function run_your_plugin() {
    $plugin = new Your_Plugin_Main();
    $plugin->run();
}
run_your_plugin();
```

## Google Cloud Integration
BUO provides a centralized connection to Google Cloud. Your plugin should use this connection when available:

```php
function your_plugin_get_google_client() {
    if (class_exists('BUO_Core') && BUO_Core::is_active()) {
        return BUO_Core::get_google_client();
    } else {
        $client = new Google_Client();
        // Configure client with plugin-specific settings
        return $client;
    }
}
```

## Settings Management

### class-your-plugin-settings.php
```php
class Your_Plugin_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'maybe_add_settings_page'));
        add_action('buo_render_plugin_settings', array($this, 'render_settings'));
    }

    public function maybe_add_settings_page() {
        if (!class_exists('BUO_Core') || !BUO_Core::is_active()) {
            add_options_page(
                'Your Plugin Settings',
                'Your Plugin',
                'manage_options',
                'your-plugin-settings',
                array($this, 'render_settings_page')
            );
        }
    }

    public function render_settings_page() {
        echo '<div class="wrap">';
        echo '<h1>Your Plugin Settings</h1>';
        $this->render_settings();
        echo '</div>';
    }

    public function render_settings() {
        // Common settings rendering logic
        $this->render_common_settings();
        
        if (!class_exists('BUO_Core') || !BUO_Core::is_active()) {
            $this->render_google_connection_settings();
        }
    }

    private function render_common_settings() {
        // Render your plugin's common settings here
    }

    private function render_google_connection_settings() {
        // Render Google connection settings here
    }
}
```

## Version Control and Updates
- Use semantic versioning (MAJOR.MINOR.PATCH).
- Implement a daily check to compare the plugin's version with the GitHub repository.

```php
function your_plugin_check_for_updates() {
    $github_version = your_plugin_get_github_version();
    $current_version = get_plugin_data(__FILE__)['Version'];
    
    if (version_compare($github_version, $current_version, '>')) {
        add_action('admin_notices', 'your_plugin_update_notice');
    }
}
add_action('admin_init', 'your_plugin_check_for_updates');

function your_plugin_update_notice() {
    echo '<div class="notice notice-warning"><p>' . __('An update is available for Your Plugin. Please update to the latest version.', 'your-plugin-textdomain') . '</p></div>';
}
```

## Localization
- Use WordPress localization functions for all user-facing strings.
- Include translation files in the `languages/` directory.

```php
function your_plugin_load_textdomain() {
    load_plugin_textdomain('your-plugin-textdomain', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'your_plugin_load_textdomain');
```

## Asset Management
- Store all CSS, JS, and image files in the `assets/` directory.
- Enqueue these files properly using WordPress functions.

```php
function your_plugin_enqueue_assets() {
    wp_enqueue_style('your-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
    wp_enqueue_script('your-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'your_plugin_enqueue_assets');
```

## Security Considerations
- Validate and sanitize all user inputs.
- Use nonces for form submissions.
- Implement proper capability checks for admin actions.

```php
// Example of secure form handling
function your_plugin_handle_form_submission() {
    if (!isset($_POST['your_plugin_nonce']) || !wp_verify_nonce($_POST['your_plugin_nonce'], 'your_plugin_action')) {
        wp_die('Security check failed');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }

    $safe_value = sanitize_text_field($_POST['user_input']);
    // Process $safe_value
}
```

## Performance Optimization
- Optimize database queries.
- Cache expensive operations.
- Use transients for temporary data storage.

```php
function your_plugin_get_expensive_data() {
    $cached_data = get_transient('your_plugin_expensive_data');
    if (false === $cached_data) {
        $cached_data = // Your expensive operation here
        set_transient('your_plugin_expensive_data', $cached_data, HOUR_IN_SECONDS);
    }
    return $cached_data;
}
```

## Testing
- Implement unit tests for your plugin's core functionality.
- Test your plugin with different WordPress versions and popular themes.
- Ensure compatibility with both BUO-integrated and independent modes.

## Documentation
- Maintain clear, comprehensive documentation in your plugin's README.md file.
- Include installation instructions, usage guide, and any dependencies.
- Document any hooks, filters, or APIs that your plugin provides.

## Submission Process
1. Develop your plugin following these guidelines.
2. Host your plugin on GitHub.
3. Submit a pull request to the BUO repository for review.
4. Once approved, your plugin will be added to the BUO ecosystem.

## Best Practices
- Follow WordPress coding standards.
- Use meaningful prefixes for all function names, class names, and constants to avoid conflicts.
- Implement proper error handling and logging.
- Regularly update your plugin to maintain compatibility with the latest WordPress and BUO versions.
- Provide clear upgrade paths for users when making significant changes.

## Troubleshooting
- Implement proper error logging.
- Provide debug mode for advanced users.
- Include common troubleshooting steps in your plugin's documentation.

```php
function your_plugin_log_error($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Your Plugin Error: ' . $message);
    }
}
```

For any questions or support, please contact the BUO development team or refer to the official BUO documentation.

