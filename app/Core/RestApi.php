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
		'bold',
		'countdown',
		'gradient',
		'corporate',
		'elegant',
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
					'args'                => [
						'enabled'       => [
							'type'              => 'boolean',
							'sanitize_callback' => 'rest_sanitize_boolean',
						],
						'pageId'        => [
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						],
						'selectionType' => [
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_key',
						],
						'templateSlug'  => [
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_key',
						],
					],
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
		$params = $request->get_params();

		$selection_type = $this->sanitize_selection_type(
			isset( $params['selectionType'] ) ? $params['selectionType'] : $current_settings['selectionType']
		);
		$template_slug  = $this->sanitize_template_slug(
			isset( $params['templateSlug'] ) ? $params['templateSlug'] : $current_settings['templateSlug']
		);
		$page_id        = $this->sanitize_page_id( isset( $params['pageId'] ) ? $params['pageId'] : $current_settings['pageId'] );
		$enabled        = isset( $params['enabled'] ) ? rest_sanitize_boolean( $params['enabled'] ) : $current_settings['enabled'];

		$updated_settings = [
			'enabled'       => $enabled,
			'pageId'        => 'template' === $selection_type ? 0 : $page_id,
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
		$preview_base = home_url( '?maintenias_preview=' );
		$image_base   = MAINTENIAS_ASSETS . '/images/templates/';

		$templates = [
			[
				'slug'         => 'classic',
				'name'         => __( 'Classic Maintenance', 'maintenias' ),
				'description'  => __( 'A centered, clean maintenance message with soft gradient background.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'classic',
				'previewImage' => $image_base . 'classic.jpg',
			],
			[
				'slug'         => 'split',
				'name'         => __( 'Split Layout', 'maintenias' ),
				'description'  => __( 'A bold split screen message with highlighted status panel.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'split',
				'previewImage' => $image_base . 'split.jpg',
			],
			[
				'slug'         => 'minimal',
				'name'         => __( 'Minimal Notice', 'maintenias' ),
				'description'  => __( 'A simple and minimal maintenance notice.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'minimal',
				'previewImage' => $image_base . 'minimal.jpg',
			],
			[
				'slug'         => 'bold',
				'name'         => __( 'Bold', 'maintenias' ),
				'description'  => __( 'A dark, bold design with large typography.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'bold',
				'previewImage' => $image_base . 'bold.jpg',
			],
			[
				'slug'         => 'countdown',
				'name'         => __( 'Countdown', 'maintenias' ),
				'description'  => __( 'Coming soon page with a countdown timer.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'countdown',
				'previewImage' => $image_base . 'countdown.jpg',
			],
			[
				'slug'         => 'gradient',
				'name'         => __( 'Gradient', 'maintenias' ),
				'description'  => __( 'Colorful gradient background with centered message.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'gradient',
				'previewImage' => $image_base . 'gradient.jpg',
			],
			[
				'slug'         => 'corporate',
				'name'         => __( 'Corporate', 'maintenias' ),
				'description'  => __( 'Professional business style with contact info.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'corporate',
				'previewImage' => $image_base . 'corporate.jpg',
			],
			[
				'slug'         => 'elegant',
				'name'         => __( 'Elegant', 'maintenias' ),
				'description'  => __( 'Sophisticated serif typography on dark background.', 'maintenias' ),
				'previewUrl'   => $preview_base . 'elegant',
				'previewImage' => $image_base . 'elegant.jpg',
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
		$selection_type = $this->sanitize_selection_type( isset( $settings['selectionType'] ) ? $settings['selectionType'] : 'page' );
		$template_slug  = $this->sanitize_template_slug( isset( $settings['templateSlug'] ) ? $settings['templateSlug'] : self::BUILTIN_TEMPLATES[0] );
		$page_id        = $this->sanitize_page_id( isset( $settings['pageId'] ) ? $settings['pageId'] : 0 );

		return [
			'enabled'       => ! empty( $settings['enabled'] ),
			'pageId'        => 'template' === $selection_type ? 0 : $page_id,
			'selectionType' => $selection_type,
			'templateSlug'  => $template_slug,
		];
	}

	/**
	 * Sanitize selection type.
	 *
	 * @param mixed $selection_type Selection type.
	 *
	 * @return string
	 */
	private function sanitize_selection_type( $selection_type ): string {
		$selection_type = sanitize_key( (string) $selection_type );

		if ( ! in_array( $selection_type, [ 'page', 'template' ], true ) ) {
			return 'page';
		}

		return $selection_type;
	}

	/**
	 * Sanitize template slug.
	 *
	 * @param mixed $template_slug Template slug.
	 *
	 * @return string
	 */
	private function sanitize_template_slug( $template_slug ): string {
		$template_slug = sanitize_key( (string) $template_slug );

		if ( ! in_array( $template_slug, self::BUILTIN_TEMPLATES, true ) ) {
			return self::BUILTIN_TEMPLATES[0];
		}

		return $template_slug;
	}

	/**
	 * Validate and sanitize selected page id.
	 *
	 * @param mixed $page_id Selected page id.
	 *
	 * @return int
	 */
	private function sanitize_page_id( $page_id ): int {
		$page_id = absint( $page_id );

		if ( ! $page_id ) {
			return 0;
		}

		$page = get_post( $page_id );
		if ( ! $page || 'page' !== $page->post_type || 'publish' !== $page->post_status ) {
			return 0;
		}

		return $page_id;
	}
}
