<?php
/**
 * Corporate maintenance template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Scheduled Maintenance', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#1e293b}
		.wrap{max-width:600px;background:#fff;border-radius:8px;padding:48px 40px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.1);border-top:4px solid #2563eb}
		.logo{width:48px;height:48px;background:#2563eb;border-radius:8px;margin:0 auto 24px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.5rem}
		h1{margin:0 0 12px;font-size:1.5rem;font-weight:600}
		p{margin:0 0 24px;color:#64748b;line-height:1.7}
		.contact{font-size:0.875rem;color:#64748b;padding-top:24px;border-top:1px solid #e2e8f0}
		.contact a{color:#2563eb;text-decoration:none}
	</style>
</head>
<body>
	<div class="wrap">
		<div class="logo">M</div>
		<h1><?php esc_html_e( 'Scheduled Maintenance', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'We are currently performing scheduled maintenance on our systems. Our team is working to complete this as quickly as possible.', 'maintenias' ); ?></p>
		<div class="contact">
			<?php esc_html_e( 'For urgent inquiries, please contact', 'maintenias' ); ?> <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"><?php echo esc_html( get_option( 'admin_email' ) ); ?></a>
		</div>
	</div>
</body>
</html>
