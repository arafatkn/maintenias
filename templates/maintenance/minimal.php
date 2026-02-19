<?php
/**
 * Minimal maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Temporarily unavailable', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f8fafc;color:#1e293b;font-family:Georgia,'Times New Roman',serif}
		.wrap{text-align:center;padding:24px;max-width:560px}
		h1{margin:0 0 16px;font-weight:500;font-size:2rem}
		p{margin:0;color:#475569;line-height:1.8;font-size:1.06rem}
	</style>
</head>
<body>
	<div class="wrap">
		<h1><?php esc_html_e( 'Temporarily unavailable', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'We are performing planned maintenance. Please visit again in a little while.', 'maintenias' ); ?></p>
	</div>
</body>
</html>
