<?php

namespace Arafatkn\Maintenias\Core;

/**
 * Admin AdminMenu class.
 *
 * Responsible for managing admin menus.
 */
class RestApi {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init AdminMenu.
	 *
	 * @return void
	 */
	public function init() {
		wrest()->setNamespace( 'maintenias/v1' );

		wrest()->get( '/posts', function () {
			$posts = get_posts( [
				'post_type'      => 'post',
				'posts_per_page' => 5,
			] );

			return $posts;
		});
	}
}
