<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php
// Fire the wp_head action required for hooking in scripts, styles, and other <head> tags.
wp_head();
?>
</head>

<body <?php hoot_attr( 'body' ); ?>>

	<?php wp_body_open(); ?>

	<a href="#main" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'strute' ); ?></a>

	<?php
	// Template modification Hook
	do_action( 'strute_body_start' );

	// Display Top Announcement
	strute_topann();

	// Display Topbar
	get_template_part( 'template-parts/topbar' );
	?>

	<div <?php hoot_attr( 'page-wrapper' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'strute_site_start' );
		?>

		<?php
		// Display SiteHead
		strute_sitehead();
		?>

		<?php hoot_get_sidebar( 'below-sitehead' ); // Loads the template-parts/sidebar-below-sitehead.php template. ?>

		<div <?php hoot_attr( 'main' ); ?>>
			<?php
			// Template modification Hook
			do_action( 'strute_main_wrapper_start' );