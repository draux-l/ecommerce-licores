<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php get_header( 'shop' ); ?>

<?php
// Dispay Loop Meta at top
strute_add_custom_title_content( 'pre', 'archive-product.php' );
if ( strute_titlearea_top() ) {
	strute_pagehead_img( 'shop', false );
	get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
	strute_add_custom_title_content( 'post', 'archive-product.php' );
} else {
	strute_pagehead_img( 'shop', true );
}

// Template modification Hook
do_action( 'strute_before_content_grid', 'archive-product.php' );
?>

<div class="hgrid main-content-grid">

	<main <?php hoot_attr( 'content' ); ?>>
		<div <?php hoot_attr( 'content-wrap', 'archive-product' ); ?>>

			<?php
			// Template modification Hook
			do_action( 'strute_main_start', 'archive-product.php' );

			/**
			 * woocommerce_before_main_content hook
			 *
			 * removed @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );

			if ( ( function_exists( 'woocommerce_product_loop' ) && woocommerce_product_loop() ) || have_posts() ) :

				// Dispay Loop Meta in content wrap
				if ( ! strute_titlearea_top() ) {
					strute_add_custom_title_content( 'post', 'archive-product.php' );
					get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
				}

				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				echo '<div ' . hoot_get_attr( 'archive-wrap', 'shop-archive' ) . '>';

				woocommerce_product_loop_start();

				if ( !function_exists( 'wc_get_loop_prop' ) || wc_get_loop_prop( 'total' ) ) :
					while ( have_posts() ) : the_post();
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part( 'content', 'product' );
					endwhile;
				endif;

				woocommerce_product_loop_end();

				echo '</div>';

				// Template modification Hook
				do_action( 'strute_before_loop_nav', 'archive-product.php' );

				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );

			else:

				do_action( 'woocommerce_no_products_found' );

			endif;

			/**
			 * woocommerce_after_main_content hook
			 *
			 * removed @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			// Template modification Hook
			do_action( 'strute_main_end', 'archive-product.php' );
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