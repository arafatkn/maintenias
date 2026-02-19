<?php
/**
 * Countdown maintenance template.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'Coming Soon', 'maintenias' ); ?></title>
	<style>
		body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1e293b,#0f172a);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#fff}
		.wrap{max-width:700px;padding:48px 40px;text-align:center}
		h1{margin:0 0 16px;font-size:2.5rem;font-weight:700}
		p{margin:0 0 40px;color:#94a3b8;font-size:1.1rem;line-height:1.7}
		.countdown{display:flex;justify-content:center;gap:24px}
		.countdown-item{background:rgba(255,255,255,0.1);border-radius:12px;padding:24px 32px;min-width:80px}
		.countdown-item span{display:block;font-size:2.5rem;font-weight:700}
		.countdown-item small{color:#94a3b8;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px}
	</style>
</head>
<body>
	<div class="wrap">
		<h1><?php esc_html_e( 'Launching Soon', 'maintenias' ); ?></h1>
		<p><?php esc_html_e( 'We are working hard to bring you something amazing. Stay tuned!', 'maintenias' ); ?></p>
		<div class="countdown">
			<div class="countdown-item">
				<span id="days">00</span>
				<small><?php esc_html_e( 'Days', 'maintenias' ); ?></small>
			</div>
			<div class="countdown-item">
				<span id="hours">00</span>
				<small><?php esc_html_e( 'Hours', 'maintenias' ); ?></small>
			</div>
			<div class="countdown-item">
				<span id="minutes">00</span>
				<small><?php esc_html_e( 'Minutes', 'maintenias' ); ?></small>
			</div>
			<div class="countdown-item">
				<span id="seconds">00</span>
				<small><?php esc_html_e( 'Seconds', 'maintenias' ); ?></small>
			</div>
		</div>
	</div>
	<script>
		const targetDate = new Date().getTime() + 7 * 24 * 60 * 60 * 1000;
		setInterval(() => {
			const now = new Date().getTime();
			const diff = targetDate - now;
			document.getElementById('days').textContent = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
			document.getElementById('hours').textContent = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
			document.getElementById('minutes').textContent = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
			document.getElementById('seconds').textContent = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
		}, 1000);
	</script>
</body>
</html>
