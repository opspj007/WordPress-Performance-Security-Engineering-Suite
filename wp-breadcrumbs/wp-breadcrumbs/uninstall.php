<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wpbread_separator' );
delete_option( 'wpbread_home_label' );
delete_option( 'wpbread_auto_insert' );
delete_option( 'wpbread_show_current' );
delete_option( 'wpbread_show_home' );
delete_option( 'wpbread_enable_schema' );
