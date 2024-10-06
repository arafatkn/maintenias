<?php

namespace Arafatkn\Maintenias\Core;

/**
 * Admin AdminMenu class.
 *
 * Responsible for managing admin menus.
 */
class AdminMenu {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'init_menu' ] );
	}

	/**
	 * Init AdminMenu.
	 *
	 * @return void
	 */
	public function init_menu() {
		global $submenu;

		$slug          = MAINTENIAS_SLUG;
		$menu_position = 50;
		$capability    = 'manage_options';
		$logo_icon     = 'dashicons-privacy';

		add_menu_page( esc_attr__( 'Maintenias', 'maintenias' ), esc_attr__( 'Maintenias', 'maintenias' ), $capability, $slug, [ $this, 'plugin_page' ], $logo_icon, $menu_position );

		if ( current_user_can( $capability ) ) {
			$submenu[ $slug ][] = [ esc_attr__( 'Dashboard', 'maintenias' ), $capability, 'admin.php?page=' . $slug . '#/' ];
			$submenu[ $slug ][] = [ esc_attr__( 'Settings', 'maintenias' ), $capability, 'admin.php?page=' . $slug . '#/settings' ];
		}
	}

	/**
	 * Render the plugin page.
	 *
	 * @return void
	 */
	public function plugin_page() {
		require_once MAINTENIAS_TEMPLATE_PATH . '/app.php';
	}
}
