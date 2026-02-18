<?php

namespace Arafatkn\Maintenias\Core;

use WP_REST_Request;
use WP_REST_Response;

/**
 * RestApi class.
 *
 * Responsible for plugin API endpoints.
 */
class RestApi {
	/**
	 * Option key used to store plugin settings.
	 */
	const OPTION_KEY = 'maintenias_settings';

	/**
	 * Built-in template slugs.
	 */
	const BUILTIN_TEMPLATES = [
		'classic',
		'split',
		'minimal',
	];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register all plugin REST routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			'maintenias/v1',
			'/settings',
			[
				[
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_settings' ],
					'permission_callback' => [ $this, 'can_manage_settings' ],
				],
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'update_settings' ],
					'permission_callback' => [ $this, 'can_manage_settings' ],
				],
			]
		);

		register_rest_route(
			'maintenias/v1',
			'/pages',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_pages' ],
				'permission_callback' => [ $this, 'can_manage_settings' ],
			]
		);

		register_rest_route(
			'maintenias/v1',
			'/templates',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_templates' ],
				'permission_callback' => [ $this, 'can_manage_settings' ],
			]
		);
	}

	/**
	 * Check if current user can manage settings.
	 *
	 * @return bool
	 */
	public function can_manage_settings(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Return plugin settings.
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings(): WP_REST_Response {
		return new WP_REST_Response( $this->normalize_settings( get_option( self::OPTION_KEY, [] ) ) );
	}

	/**
	 * Save plugin settings.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return WP_REST_Response
	 */
	public function update_settings( WP_REST_Request $request ): WP_REST_Response {
		$current_settings = $this->normalize_settings( get_option( self::OPTION_KEY, [] ) );
		$params           = $request->get_json_params();
		$selection_type   = isset( $params['selectionType'] ) ? sanitize_key( $params['selectionType'] ) : $current_settings['selectionType'];
		$template_slug    = isset( $params['templateSlug'] ) ? sanitize_key( $params['templateSlug'] ) : $current_settings['templateSlug'];

		if ( ! in_array( $selection_type, [ 'page', 'template' ], true ) ) {
			$selection_type = 'page';
		}

		if ( ! in_array( $template_slug, self::BUILTIN_TEMPLATES, true ) ) {
			$template_slug = self::BUILTIN_TEMPLATES[0];
		}

		$updated_settings = [
			'enabled'       => isset( $params['enabled'] ) ? (bool) $params['enabled'] : $current_settings['enabled'],
			'pageId'        => isset( $params['pageId'] ) ? absint( $params['pageId'] ) : $current_settings['pageId'],
			'selectionType' => $selection_type,
			'templateSlug'  => $template_slug,
		];

		update_option( self::OPTION_KEY, $updated_settings );

		return new WP_REST_Response( $this->normalize_settings( $updated_settings ) );
	}

	/**
	 * Return pages for the selector list.
	 *
	 * @return WP_REST_Response
	 */
	public function get_pages(): WP_REST_Response {
		$pages = get_posts(
			[
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		$data = array_map(
			function ( $page ) {
				$thumbnail_id = get_post_thumbnail_id( $page->ID );

				return [
					'id'        => $page->ID,
					'title'     => get_the_title( $page->ID ),
					'permalink' => get_permalink( $page->ID ),
					'preview'   => $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : null,
				];
			},
			$pages
		);

		return new WP_REST_Response( $data );
	}

	/**
	 * Return built in templates.
	 *
	 * @return WP_REST_Response
	 */
	public function get_templates(): WP_REST_Response {
		$templates = [
			[
				'slug'        => 'classic',
				'name'        => __( 'Classic Maintenance', 'maintenias' ),
				'description' => __( 'A centered, clean maintenance message with soft gradient background.', 'maintenias' ),
			],
			[
				'slug'        => 'split',
				'name'        => __( 'Split Layout', 'maintenias' ),
				'description' => __( 'A bold split screen message with highlighted status panel.', 'maintenias' ),
			],
			[
				'slug'        => 'minimal',
				'name'        => __( 'Minimal Notice', 'maintenias' ),
				'description' => __( 'A simple and minimal maintenance notice.', 'maintenias' ),
			],
		];

		return new WP_REST_Response( $templates );
	}

	/**
	 * Normalize settings shape.
	 *
	 * @param array $settings Raw settings.
	 *
	 * @return array
	 */
	private function normalize_settings( array $settings ): array {
		$template_slug = isset( $settings['templateSlug'] ) ? sanitize_key( $settings['templateSlug'] ) : self::BUILTIN_TEMPLATES[0];
		if ( ! in_array( $template_slug, self::BUILTIN_TEMPLATES, true ) ) {
			$template_slug = self::BUILTIN_TEMPLATES[0];
		}

		$selection_type = isset( $settings['selectionType'] ) ? sanitize_key( $settings['selectionType'] ) : 'page';
		if ( ! in_array( $selection_type, [ 'page', 'template' ], true ) ) {
			$selection_type = 'page';
		}

		return [
			'enabled'       => ! empty( $settings['enabled'] ),
			'pageId'        => isset( $settings['pageId'] ) ? absint( $settings['pageId'] ) : 0,
			'selectionType' => $selection_type,
			'templateSlug'  => $template_slug,
		];
	}
}
