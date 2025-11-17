<?php
/**
 * Template to display single static page content
 */

/**
 * If viewing a single page (pages can occur in archive lists as well. Example: search results)
 * Use `( is_singular() )` instead of `( is_page() )` to allow plugins like Tribe Events use page templates on single event pages
 * This lets pages show in category pages with plugins like 'Category Tag Pages'
 * Additionally, display full content on post type archive pages to allow plugins like Tribe Events use page templates on events pages
 */
if ( is_singular() || ( is_archive() && is_post_type_archive() ) ) :
?>

	<article <?php hoot_attr( 'page', 'single' ); ?>>

		<div <?php hoot_attr( 'entry-content' ); ?>>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<div class="screen-reader-text" itemprop="datePublished" itemtype="https://schema.org/Date"><?php echo get_the_date('Y-m-d'); ?></div>

		<?php
		$hide_meta_info = apply_filters( 'strute_hide_meta', false, 'bottom' );
		if ( !$hide_meta_info && 'bottom' == hoot_get_mod( 'page_meta_location' ) ):
			$metarray = hoot_get_mod('page_meta');
			if ( is_customize_preview() || hoot_meta_info( $metarray, 'page', true ) ) :
				?>
				<footer class="entry-footer">
					<?php hoot_display_meta_info( $metarray, 'page' ); ?>
				</footer><!-- .entry-footer -->
				<?php
			endif;
		endif;
		?>

	</article><!-- .entry -->

<?php
/**
 * If not viewing a single page i.e. viewing the page in a list index (Example: search results)
 */
else :

	if ( ! apply_filters( 'strute_searchresults_hide_pages', false ) ) {
		// Loads the template-parts/archive-{$post_type}-{$archive_type}.php template.
		hoot_get_archive_content();
	}

endif;
?>