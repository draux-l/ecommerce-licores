<?php

// Template modification Hook
do_action( 'strute_before_menu', 'primary' );

if ( has_nav_menu( 'hoot-primary-menu' ) ) : // Check if there's a menu assigned to the 'primary' location.

	?>
	<div class="screen-reader-text"><?php esc_html_e( 'Primary Navigation Menu', 'strute' ); ?></div>
	<nav <?php hoot_attr( 'menu', 'primary' ); ?>>
		<a class="menu-toggle" href="#"><?php
		$menulabel = hoot_get_mod( 'mobile_menu_label' );
		$is_cpreview = is_customize_preview() ? ( empty( $menulabel ) ? ' hootnoshow' : ' ' ) : false;
		if ( !empty( $menulabel ) || $is_cpreview ) {
			echo '<span class="menu-toggle-text' . $is_cpreview . '">' . esc_html( $menulabel ) . '</span>';
		} ?><i class="fas fa-bars"></i></a>

		<?php
		/* Display Main Menu */
		wp_nav_menu( array(
			'theme_location'  => 'hoot-primary-menu',
			'container'       => false,
			'menu_id'         => 'menu-primary-items',
			'menu_class'      => 'menu menu-items sf-menu fixedmenu-items fixedmenu-left',
			'fallback_cb'     => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		) ); ?>

		<a class="menu-toggleclose" href="#"><i class="fas fa-times"></i></a>
	</nav><!-- #menu-primary -->
	<?php

elseif ( is_customize_preview() ) :
	?>
	<div <?php hoot_attr( 'menu', 'primary' ); ?> style="background:#eee;padding:2px 5px;border:dashed 1px;font-size:14px;line-height:20px;">
		<?php
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		echo sprintf( esc_html__('Please assign a menu to the %1$sPrimary Menu%2$s location in %1$swp-admin > Appearance > Menus > Manage Locations.%2$s', 'strute'), '<strong>', '</strong>' );
		?>
	</div>
	<?php

endif; // End check for menu.

// Template modification Hook
do_action( 'strute_after_menu', 'primary' );