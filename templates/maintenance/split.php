<?php
/**
 * Split maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Maintenance in progress', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0f172a;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#fff}
		.wrap{width:min(900px,92vw);display:grid;grid-template-columns:1.4fr 1fr;overflow:hidden;border-radius:20px;background:#111827;box-shadow:0 16px 44px rgba(0,0,0,.3)}
		.main{padding:48px 40px;background:linear-gradient(135deg,#1d4ed8,#3b82f6)}
		.side{padding:48px 32px;background:#fff;color:#111827}
		h1{margin:0 0 14px;font-size:2.1rem}
		p{margin:0;line-height:1.6}
		.badge{display:inline-block;margin-bottom:18px;padding:6px 12px;border-radius:9999px;background:#ffffff33;font-size:.75rem;letter-spacing:.05em;text-transform:uppercase}
		@media(max-width:720px){.wrap{grid-template-columns:1fr}}
	</style>
</head>
<body>
	<div class="wrap">
		<div class="main">
			<div class="badge"><?php esc_html_e( 'Scheduled update', 'maintenias' ); ?></div>
			<h1><?php esc_html_e( 'Maintenance in progress', 'maintenias' ); ?></h1>
			<p><?php esc_html_e( 'We are upgrading our platform to serve you better. Please check back shortly.', 'maintenias' ); ?></p>
		</div>
		<div class="side">
			<h2><?php esc_html_e( 'Thanks for waiting', 'maintenias' ); ?></h2>
			<p><?php esc_html_e( 'We are applying important improvements and will be back online soon.', 'maintenias' ); ?></p>
		</div>
	</div>
</body>
</html>
