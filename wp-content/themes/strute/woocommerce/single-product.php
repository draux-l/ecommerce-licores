<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php get_header( 'shop' ); ?>

<?php
// Dispay Loop Meta at top
strute_add_custom_title_content( 'pre', 'single-product.php' );
if ( strute_titlearea_top() ) {
	get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
	strute_add_custom_title_content( 'post', 'single-product.php' );
}

// Template modification Hook
do_action( 'strute_before_content_grid', 'single-product.php' );
?>

<div class="hgrid main-content-grid">

	<main <?php hoot_attr( 'content' ); ?>>
		<div <?php hoot_attr( 'content-wrap', 'single-product' ); ?>>

			<?php
			// Template modification Hook
			do_action( 'strute_main_start', 'single-product.php' );

			/**
			 * woocommerce_before_main_content hook
			 *
			 * removed @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
			?>

			<?php if ( have_posts() ) : ?>

				<?php
				// Dispay Loop Meta in content wrap
				if ( ! strute_titlearea_top() ) {
					strute_add_custom_title_content( 'post', 'single-product.php' );
					get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
				}

				// Template modification Hook
				do_action( 'strute_loop_start', 'single-product.php' );
				?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; ?>

				<?php
				// Template modification Hook
				do_action( 'strute_loop_end', 'single-product.php' );

				// Template modification Hook
				do_action( 'strute_after_content_wrap', 'single-product.php' );
				?>

			<?php else : ?>

				<?php
				// Loads the template-parts/error.php template.
				get_template_part( 'template-parts/error' );
				?>

			<?php endif; ?>

			<?php
			/**
			 * woocommerce_after_main_content hook
			 *
			 * removed @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			// Template modification Hook
			do_action( 'strute_main_end', 'single-product.php' );
			?>

		</div><!-- #content-wrap -->
	</main><!-- #content -->

	<?php
	/**
	 * woocommerce_sidebar hook
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action( 'woocommerce_sidebar' );
	?>

</div><!-- .main-content-grid -->

<?php get_footer( 'shop' ); ?>