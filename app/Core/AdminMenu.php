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
		add_menu_page(
			esc_attr__( 'Maintenias', 'maintenias' ),
			esc_attr__( 'Maintenias', 'maintenias' ),
			'manage_options',
			MAINTENIAS_SLUG,
			[ $this, 'plugin_page' ],
			'dashicons-privacy',
			50
		);
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
