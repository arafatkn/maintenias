<?php
/**
 * Gradient maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Be Right Back', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#667eea 0%,#764ba2 50%,#f093fb 100%);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#fff}
		.wrap{max-width:600px;padding:48px 40px;text-align:center}
		h1{margin:0 0 16px;font-size:3rem;font-weight:700;text-shadow:0 2px 10px rgba(0,0,0,0.2)}
		p{margin:0;font-size:1.2rem;line-height:1.7;opacity:0.9}
		.icon{font-size:4rem;margin-bottom:24px}
	</style>
</head>
<body>
	<div class="wrap">
		<div class="icon">&#9881;</div>
		<h1><?php esc_html_e( 'Be Right Back', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'We are making some improvements to serve you better. Please check back soon!', 'maintenias' ); ?></p>
	</div>
</body>
</html>
