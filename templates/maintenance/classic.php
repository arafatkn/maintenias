<?php
/**
 * Classic maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'We&rsquo;ll be back soon', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e0e7ff,#f8fafc);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#0f172a}
		.wrap{max-width:640px;background:#ffffffd9;border-radius:16px;padding:48px 40px;text-align:center;box-shadow:0 15px 40px rgba(15,23,42,.08)}
		h1{margin:0 0 16px;font-size:2rem}
		p{margin:0;color:#334155;line-height:1.7}
	</style>
</head>
<body>
	<div class="wrap">
		<h1><?php esc_html_e( 'We&rsquo;re doing a little tune-up', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'Our website is currently under maintenance. We&rsquo;ll be back online shortly. Thank you for your patience.', 'maintenias' ); ?></p>
	</div>
</body>
</html>
