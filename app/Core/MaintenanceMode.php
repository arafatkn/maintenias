<?php

namespace Arafatkn\Maintenias\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

use WP_Query;

/**
 * MaintenanceMode class.
 *
 * Handles frontend maintenance rendering for non-admin visitors.
 */
class MaintenanceMode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', [ $this, 'handle_maintenance_mode' ], 1 );
		add_action( 'template_redirect', [ $this, 'handle_template_preview' ], 0 );
	}

	/**
	 * Handle template preview for admins.
	 *
	 * @return void
	 */
	public function handle_template_preview() {
		if ( ! isset( $_GET['maintenias_preview'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to preview templates.', 'maintenias' ) );
		}

		$template_slug = sanitize_key( wp_unslash( $_GET['maintenias_preview'] ) );

		if ( ! in_array( $template_slug, RestApi::BUILTIN_TEMPLATES, true ) ) {
			wp_die( esc_html__( 'Invalid template.', 'maintenias' ) );
		}

		nocache_headers();
		$this->render_template( $template_slug );
		exit;
	}

	/**
	 * Render maintenance page when mode is enabled.
	 *
	 * @return void
	 */
	public function handle_maintenance_mode() {
		if ( ! $this->should_intercept_request() ) {
			return;
		}

		$settings       = get_option( RestApi::OPTION_KEY, [] );
		$enabled        = ! empty( $settings['enabled'] );
		$page_id        = isset( $settings['pageId'] ) ? absint( $settings['pageId'] ) : 0;
		$selection_type = isset( $settings['selectionType'] ) ? sanitize_key( $settings['selectionType'] ) : 'page';
		$template_slug  = isset( $settings['templateSlug'] ) ? sanitize_key( $settings['templateSlug'] ) : 'classic';

		if ( ! $enabled ) {
			return;
		}

		if ( 'template' === $selection_type ) {
			status_header( 503 );
			nocache_headers();
			$this->render_template( $template_slug );
			exit;
		}

		if ( ! $page_id ) {
			return;
		}

		if ( is_page( $page_id ) ) {
			return;
		}

		$page = get_post( $page_id );
		if ( ! $page || 'publish' !== $page->post_status || 'page' !== $page->post_type ) {
			return;
		}

		global $wp_query;
		$wp_query = new WP_Query(
			[
				'page_id'        => $page_id,
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
			]
		);

		if ( ! $wp_query->have_posts() ) {
			wp_reset_postdata();
			return;
		}

		status_header( 503 );
		nocache_headers();

		include get_query_template( 'page' );
		exit;
	}

	/**
	 * Render built in template output.
	 *
	 * @param string $template_slug Template slug.
	 *
	 * @return void
	 */
	private function render_template( string $template_slug ) {
		if ( ! in_array( $template_slug, RestApi::BUILTIN_TEMPLATES, true ) ) {
			$template_slug = 'classic';
		}

		$template_file = MAINTENIAS_TEMPLATE_PATH . '/maintenance/' . $template_slug . '.php';
		if ( ! file_exists( $template_file ) ) {
			$template_file = MAINTENIAS_TEMPLATE_PATH . '/maintenance/classic.php';
		}

		include $template_file;
	}

	/**
	 * Determine whether this request should see maintenance page.
	 *
	 * @return bool
	 */
	private function should_intercept_request(): bool {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return false;
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return false;
		}

		if ( current_user_can( 'manage_options' ) ) {
			return false;
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		if ( false !== strpos( $request_uri, 'wp-login.php' ) || false !== strpos( $request_uri, 'wp-register.php' ) ) {
			return false;
		}

		return true;
	}
}
