<?php

namespace Arafatkn\Maintenias\Core;

/**
 * Asset Manager class.
 *
 * Responsible for managing all the assets (CSS, JS, Images, Locales).
 */
class AssetManager {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_all_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Register all scripts and styles.
	 *
	 * @return void
	 */
	public function register_all_scripts() {
		$this->register_styles( $this->get_styles() );
		$this->register_scripts( $this->get_scripts() );
	}

	/**
	 * Get all styles.
	 *
	 * @return array
	 */
	public function get_styles(): array {
		return [
			'maintenias-css' => [
				'src'     => MAINTENIAS_BUILD . '/index.css',
				'version' => time(), // MAINTENIAS_VERSION,
				'deps'    => [],
			],
		];
	}

	/**
	 * Get all scripts.
	 *
	 * @return array
	 */
	public function get_scripts(): array {
		$dependency = require_once MAINTENIAS_DIR . '/build/index.asset.php';

		return [
			'maintenias-app' => [
				'src'       => MAINTENIAS_BUILD . '/index.js',
				'version'   => $dependency['version'],
				'deps'      => $dependency['dependencies'],
				'in_footer' => true,
			],
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles( array $styles ) {
		foreach ( $styles as $handle => $style ) {
			wp_register_style( $handle, $style['src'], $style['deps'], $style['version'] );
		}
	}

	/**
	 * Register scripts.
	 *
	 * @return void
	 */
	public function register_scripts( array $scripts ) {
		foreach ( $scripts as $handle =>$script ) {
			wp_register_script( $handle, $script['src'], $script['deps'], $script['version'], $script['in_footer'] );
		}
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @return void
	 */
	public function enqueue_admin_assets() {
		// Check if we are on the admin page and page=maintenias.
		if ( ! is_admin() || ! isset( $_GET['page'] ) || sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== 'maintenias' ) {
			return;
		}

		wp_enqueue_style( 'maintenias-css' );
		wp_enqueue_script( 'maintenias-app' );
	}
}