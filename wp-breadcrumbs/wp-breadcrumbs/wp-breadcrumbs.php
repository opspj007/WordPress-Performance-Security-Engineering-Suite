<?php
/**
 * Plugin Name: WP Breadcrumbs
 * Plugin URI: https://github.com/opspj007/WordPress-Performance-Security-Engineering-Suite
 * Description: Lightweight, SEO-friendly breadcrumbs for WordPress with Schema.org JSON-LD, shortcode, automatic insertion, and WooCommerce support.
 * Version: 1.0.0
 * Author: Om Prakash Chaudhary
 * Author URI: https://github.com/opspj007
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-breadcrumbs
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPBREAD_VERSION', '1.0.0' );
define( 'WPBREAD_FILE', __FILE__ );
define( 'WPBREAD_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPBREAD_URL', plugin_dir_url( __FILE__ ) );

require_once WPBREAD_DIR . 'includes/class-wpbread.php';
require_once WPBREAD_DIR . 'includes/class-wpbread-admin.php';

register_activation_hook( __FILE__, array( 'WPBREAD', 'activate' ) );

WPBREAD::init();
if ( is_admin() ) {
	WPBREAD_Admin::init();
}
