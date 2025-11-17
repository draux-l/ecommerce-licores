<?php
/**
 * This is the most generic template file in a WordPress theme
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the blog posts index page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

// Loads the header.php template.
get_header();
?>

<?php
// Dispay Loop Meta at top
strute_add_custom_title_content( 'pre', 'index.php' );
if ( strute_titlearea_top() ) {
	strute_pagehead_img( 'index', false );
	get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
	strute_add_custom_title_content( 'post', 'index.php' );
} else {
	strute_pagehead_img( 'index', true );
}

// Template modification Hook
do_action( 'strute_before_content_grid', 'index.php' );
?>

<div class="hgrid main-content-grid">

	<main <?php hoot_attr( 'content' ); ?>>
		<div <?php hoot_attr( 'content-wrap', 'index' ); ?>>

			<?php
			// Template modification Hook
			do_action( 'strute_main_start', 'index.php' );

			// Checks if any posts were found.
			if ( have_posts() ) :

				// Dispay Loop Meta in content wrap
				if ( ! strute_titlearea_top() ) {
					strute_add_custom_title_content( 'post', 'index.php' );
					get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
				}

				echo '<div ' . hoot_get_attr( 'archive-wrap', 'index' ) . '>';

				// Template modification Hook
				do_action( 'strute_loop_start', 'index.php' );

				// Begins the loop through found posts, and load the post data.
				$postcounter = 1;
				while ( have_posts() ) : the_post();

					// Loads the template-parts/content-{$post_type}.php template.
					hoot_set_data( 'archive_postcounter', $postcounter );
					hoot_get_content_template();
					$postcounter++;

				// End found posts loop.
				endwhile;

				// Template modification Hook
				do_action( 'strute_loop_end', 'index.php' );

				echo '</div>';

				// Template modification Hook
				do_action( 'strute_before_loop_nav', 'index.php' );

				// Loads the template-parts/loop-nav.php template.
				get_template_part( 'template-parts/loop-nav' );

			// If no posts were found.
			else :

				// Loads the template-parts/error.php template.
				get_template_part( 'template-parts/error' );

			// End check for posts.
			endif;

			// Template modification Hook
			do_action( 'strute_main_end', 'index.php' );
			?>

		</div><!-- #content-wrap -->
	</main><!-- #content -->

	<?php hoot_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .main-content-grid -->

<?php get_footer(); // Loads the footer.php template. ?>