<?php

$is_cpreview = is_customize_preview();

// Dispay Sidebar if sidebar has widgets
if ( is_active_sidebar( 'hoot-sitehead' ) || $is_cpreview ) :

	// Template modification Hook
	do_action( 'strute_sidebar_start', 'sitehead-sidebar' );

	?>
	<aside <?php hoot_attr( 'sidebar', 'sitehead', 'inline-nav' ); ?>>
		<?php
			if ( is_active_sidebar( 'hoot-sitehead' ) ):
				dynamic_sidebar( 'hoot-sitehead' );
			elseif ( $is_cpreview && hoot_widget_exists( 'WP_Widget_Text' ) ) :
				the_widget(
					'WP_Widget_Text',
					array(
						/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
						'text'   => sprintf( __( '%3$sYou can add widgets like <strong>"Search"</strong> and <strong>"HootKit Social Icons"</strong> here by adding them to "Header Side" area in the %1$swidgets screen%2$s in wp-admin.<br /><strong>Your visitors will not see this example text.</strong>%4$s', 'strute' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>', '<span style="display:inline-block; max-width: 350px;">', '</span>' ),
						'filter' => true,
					),
					array(
						'before_widget' => '<section class="widget widget_text">',
						'after_widget'  => '</section>',
						'before_title'  => '<h3 class="widget-title"><span>',
						'after_title'   => '</span></h3>'
					)
				);
			endif;
		?>
	</aside>
	<?php

	// Template modification Hook
	do_action( 'strute_sidebar_end', 'sitehead-sidebar' );

endif;