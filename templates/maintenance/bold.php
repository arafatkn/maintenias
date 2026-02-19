<?php
/**
 * Bold maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Under Maintenance', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#0a0a0a;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#fff}
		.wrap{max-width:800px;padding:48px 40px;text-align:center}
		h1{margin:0 0 24px;font-size:4rem;font-weight:800;letter-spacing:-2px;line-height:1.1}
		p{margin:0;color:#a1a1aa;font-size:1.25rem;line-height:1.7}
		.accent{color:#fbbf24}
	</style>
</head>
<body>
	<div class="wrap">
		<h1><?php esc_html_e( 'WE ARE', 'maintenias' ); ?> <span class="accent"><?php esc_html_e( 'OFFLINE', 'maintenias' ); ?></span></h1>
		<p><?php esc_html_e( 'Our website is currently undergoing scheduled maintenance. We appreciate your patience and will be back online shortly.', 'maintenias' ); ?></p>
	</div>
</body>
</html>
