<?php
/**
 * Elegant maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Coming Soon', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#18181b;font-family:Georgia,'Times New Roman',serif;color:#fafafa}
		.wrap{max-width:600px;padding:48px 40px;text-align:center}
		.line{width:60px;height:1px;background:linear-gradient(90deg,transparent,#a1a1aa,transparent);margin:0 auto 32px}
		h1{margin:0 0 20px;font-size:2.5rem;font-weight:400;letter-spacing:4px;text-transform:uppercase}
		p{margin:0;color:#a1a1aa;font-size:1.1rem;line-height:1.8;font-style:italic}
		.line-bottom{width:60px;height:1px;background:linear-gradient(90deg,transparent,#a1a1aa,transparent);margin:32px auto 0}
	</style>
</head>
<body>
	<div class="wrap">
		<div class="line"></div>
		<h1><?php esc_html_e( 'Coming Soon', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'Something beautiful is on its way. We are crafting an experience worth waiting for.', 'maintenias' ); ?></p>
		<div class="line-bottom"></div>
	</div>
</body>
</html>
