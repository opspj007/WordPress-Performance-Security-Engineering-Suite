<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPBREAD_Admin {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'settings' ) );
	}

	public static function menu() {
		add_options_page(
			'WP Breadcrumbs',
			'WP Breadcrumbs',
			'manage_options',
			'wp-breadcrumbs',
			array( __CLASS__, 'page' )
		);
	}

	public static function settings() {
		register_setting(
			'wpbread_settings',
			'wpbread_separator',
			array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '›' )
		);
		register_setting(
			'wpbread_settings',
			'wpbread_home_label',
			array( 'sanitize_callback' => 'sanitize_text_field', 'default' => 'Home' )
		);
		register_setting(
			'wpbread_settings',
			'wpbread_auto_insert',
			array( 'sanitize_callback' => 'absint', 'default' => 1 )
		);
		register_setting(
			'wpbread_settings',
			'wpbread_show_current',
			array( 'sanitize_callback' => 'absint', 'default' => 1 )
		);
		register_setting(
			'wpbread_settings',
			'wpbread_show_home',
			array( 'sanitize_callback' => 'absint', 'default' => 1 )
		);
		register_setting(
			'wpbread_settings',
			'wpbread_enable_schema',
			array( 'sanitize_callback' => 'absint', 'default' => 1 )
		);
	}

	public static function page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1>WP Breadcrumbs</h1>
			<p>Lightweight SEO-friendly breadcrumbs for WordPress.</p>

			<form method="post" action="options.php">
				<?php settings_fields( 'wpbread_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="wpbread_home_label">Home label</label></th>
						<td><input id="wpbread_home_label" name="wpbread_home_label" type="text" value="<?php echo esc_attr( get_option( 'wpbread_home_label', 'Home' ) ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="wpbread_separator">Separator</label></th>
						<td><input id="wpbread_separator" name="wpbread_separator" type="text" value="<?php echo esc_attr( get_option( 'wpbread_separator', '›' ) ); ?>" class="small-text"></td>
					</tr>
					<tr>
						<th scope="row">Automatic insertion</th>
						<td><label><input type="checkbox" name="wpbread_auto_insert" value="1" <?php checked( get_option( 'wpbread_auto_insert', 1 ), 1 ); ?>> Insert before post/page content</label></td>
					</tr>
					<tr>
						<th scope="row">Show Home</th>
						<td><label><input type="checkbox" name="wpbread_show_home" value="1" <?php checked( get_option( 'wpbread_show_home', 1 ), 1 ); ?>> Display Home item</label></td>
					</tr>
					<tr>
						<th scope="row">Show current page</th>
						<td><label><input type="checkbox" name="wpbread_show_current" value="1" <?php checked( get_option( 'wpbread_show_current', 1 ), 1 ); ?>> Display current item</label></td>
					</tr>
					<tr>
						<th scope="row">Breadcrumb Schema</th>
						<td><label><input type="checkbox" name="wpbread_enable_schema" value="1" <?php checked( get_option( 'wpbread_enable_schema', 1 ), 1 ); ?>> Output Schema.org BreadcrumbList JSON-LD</label></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<hr>
			<h2>Shortcode</h2>
			<p>Use <code>[wp_breadcrumbs]</code> anywhere you want to display breadcrumbs.</p>
			<p>Optional custom CSS class: <code>[wp_breadcrumbs class="my-breadcrumbs"]</code></p>
		</div>
		<?php
	}
}
