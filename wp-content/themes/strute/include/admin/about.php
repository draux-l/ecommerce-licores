<?php
/**
 * About page
 */

/**
 * Sets up the Appearance Subpage
 *
 * @since 1.0
 * @access public
 * @return void
 */
function strute_add_appearance_subpage() {

	add_theme_page(
		strute_abouttag( 'label' ), // Page Title
		strute_abouttag( 'label' ), // Menu Title
		'edit_theme_options', // capability
		strute_abouttag( 'slug' ) . '-welcome', // menu-slug
		'strute_appearance_subpage', // function name
		1 // position
		);

	add_action( 'admin_enqueue_scripts', 'strute_admin_enqueue_about_styles' );

}
/* Add the admin setup function to the 'admin_menu' hook. */
add_action( 'admin_menu', 'strute_add_appearance_subpage' );

/**
 * Enqueue CSS
 *
 * @since 1.0
 * @access public
 * @return void
 */
function strute_admin_enqueue_about_styles( $hook ) {
	$slug = strute_abouttag( 'slug' );
	if ( $hook === "appearance_page_{$slug}-welcome" ) {
		wp_enqueue_style( 'hoot-admin-about', hoot_data()->incuri . 'admin/css/about.css', array(),  hoot_data()->hoot_version );
		wp_enqueue_script( 'hoot-admin-about', hoot_data()->incuri . 'admin/js/about.js', array( 'jquery' ),  hoot_data()->hoot_version, true );
	}
}

/**
 * Display the Appearance Subpage
 *
 * @since 1.0
 * @access public
 * @return void
 */
function strute_appearance_subpage() {
	$slug = strute_abouttag( 'slug' );
	$themename = strute_abouttag( 'name' );
	$themelabel = strute_abouttag( 'label' );
	$screenshot = strute_abouttag( 'shot' );

	$urlhoot = strute_abouttag('urlhoot');
	$urldemo = strute_abouttag('urldemo');
	$urltheme = strute_abouttag('urltheme');
	$urlsupport = strute_abouttag('urlsupport');
	$urldocs = strute_abouttag('urldocs');

	$hasupsell = apply_filters( 'strute_load_upsell', true );
	$default_tabs = array( 'qstart', 'plugins' );
	if ( $hasupsell ) $default_tabs[] = 'upsell';
	$availabletabs = apply_filters( 'strute_about_load_tabs', $default_tabs );
	if ( ! is_array( $availabletabs ) ) $availabletabs = $default_tabs;
	$activetab = !empty( $_GET['tab'] ) && in_array( $_GET['tab'], $availabletabs ) ? $_GET['tab'] : ( $hasupsell ? 'upsell' : 'qstart' );
	?>
	<div class="wrap">

		<h1 class="hoot-about-title"><?php
			/* Translators: 1 is the theme name */
			printf( esc_html__( 'About %1$s', 'strute' ), $themelabel );
			?></h1>

		<div id="hoot-about-sub" class="hoot-about-sub">
			<div class="hoot-about-ss"><?php if ( !empty( $screenshot ) ) echo '<img src="' . esc_url( $screenshot ) . '">'; ?></div>
			<div class="hoot-about-text">
				<p class="hoot-about-intro"><?php 
					/* Translators: 1 is the theme name */
					printf( esc_html__( '%1$s is a multipurpose highly flexible WordPress theme built on a SEO friendly framework with fast loading speed. It comes with multiple Theme Customizer options, various blog layouts, WooCommerce support etc among many other features.', 'strute' ), $themename );
					?></p>
				<p class="hoot-about-textlinks">
					<?php if ( $hasupsell ) : ?>
					<a class="button button-primary" href="<?php echo esc_url( $urltheme ); ?>" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'View Premium', 'strute' ) ?></a>
					<?php endif; ?>
					<a class="button" href="<?php echo esc_url( $urldemo ); ?>" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo', 'strute' ) ?></a>
					<a class="button" href="<?php echo esc_url( $urldocs ); ?>" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'Documentation', 'strute' ) ?></a>
					<a class="button" href="<?php echo esc_url( $urlsupport ); ?>" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'strute' ) ?></a>
					<a class="button" href="https://wordpress.org/support/theme/strute/reviews/#new-post" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> <?php esc_html_e( 'Rate Us', 'strute' ) ?></a>
				</p>
				<?php do_action( 'strute_theme_after_about_textlinks', $slug ); ?>
			</div>
			<div class="clear"></div>
		</div><!-- .hoot-about-sub -->

		<div id="hoot-abouttabs" class="hoot-abouttabs">

			<h2 id="hootnav-tabs" class="nav-tab-wrapper">
				<?php if ( $hasupsell ) : ?>
				<span class="hootnav-tab nav-tab <?php if ( $activetab === 'upsell' ) echo 'nav-tab-active'; ?>" data-tabid="upsell"><?php esc_html_e( 'Premium Options', 'strute' ) ?></span>
				<?php endif; ?>
				<span class="hootnav-tab nav-tab <?php if ( $activetab === 'qstart' ) echo 'nav-tab-active'; ?>" data-tabid="qstart"><?php esc_html_e( 'Quick Start Guide', 'strute' ) ?></span>
				<span class="hootnav-tab nav-tab <?php if ( $activetab === 'plugins' ) echo 'nav-tab-active'; ?>" data-tabid="plugins"><?php esc_html_e( 'Theme Plugins', 'strute' ) ?></span>
				<?php do_action( 'strute_about_tabs', $activetab ); ?>
			</h2>

			<?php if ( $hasupsell ) : ?>
			<div id="hoot-upsell" class="hoot-upsell hoot-tabblock <?php if ( $activetab == 'upsell' ) echo 'hootactive'; ?>">
				<h2 class="centered allcaps"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'Do more with %2$s%1$s %3$sPremium%4$s%5$s', 'strute' ), $themename, '<span>', '<strong>', '</strong>', '</span>' );
					?></h2>
				<p class="hoot-tab-intro centered"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'If you have enjoyed using %1$s, you are going to love %2$s%1$s Premium%3$s.%4$sIt is a robust upgrade to %1$s that gives you many useful features.', 'strute' ), $themename, '<strong>', '</strong>', '<br />' );
					?></p>
				<p class="hoot-tab-cta centered">
					<a class="button button-secondary secondary-cta" href="<?php echo esc_url( $urldemo ); ?>" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo Site', 'strute' ) ?></a>
					<a class="button button-primary primary-cta" href="<?php echo esc_url( $urltheme ); ?>" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php
						/* Translators: 1 is the theme name */
						printf( esc_html__( 'View %1$s Premium', 'strute' ), $themename );
						?></a>
				</p>
				<div class="hoot-tab-sub"><div class="hoot-tab-subinner">
					<?php strute_tabsections( 'features' ); ?>
					<div class="tabsection hoot-tab-cta centered">
						<a class="button button-secondary secondary-cta" href="<?php echo esc_url( $urldemo ); ?>" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo Site', 'strute' ) ?></a>
						<a class="button button-primary primary-cta" href="<?php echo esc_url( $urltheme ); ?>" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php
							/* Translators: 1 is the theme name */
							printf( esc_html__( 'View %1$s Premium', 'strute' ), $themename );
							?></a>
					</div>
				</div></div><!-- .hoot-tab-sub -->
			</div><!-- #hoot-upsell -->
			<?php endif; ?>

			<div id="hoot-qstart" class="hoot-qstart hoot-tabblock <?php if ( $activetab == 'qstart' ) echo 'hootactive'; ?>">
				<h2 class="centered allcaps"><span class="dashicons dashicons-clock"></span> <?php esc_html_e( 'Quick Start Guide', 'strute' ); ?></h2>
				<p class="hoot-tab-intro centered"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'Follow these steps to quickly start developing your site.%1$sTo read the full documentation, or to get support from one of our support ninjas, click the buttons below.', 'strute' ), '<br />' );
					?></p>
				<p class="hoot-tab-cta centered">
					<a class="button button-primary primary-cta" href="<?php echo esc_url( $urldocs ); ?>" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'View Full Documentation', 'strute' ) ?></a>
					<a class="button button-secondary secondary-cta" href="<?php echo esc_url( $urlsupport ); ?>" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'strute' ) ?></a>
				</p>
				<div class="hoot-tab-sub hoot-qstart-sub"><div class="hoot-tab-subinner">
					<?php strute_tabsections( 'quickstart' ); ?>
					<div class="tabsection hoot-tab-cta centered">
						<a class="button button-primary primary-cta" href="<?php echo esc_url( $urldocs ); ?>" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'View Full Documentation', 'strute' ) ?></a>
						<a class="button button-secondary secondary-cta" href="<?php echo esc_url( $urlsupport ); ?>" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'strute' ) ?></a>
					</div>
				</div></div><!-- .hoot-tab-sub -->
			</div><!-- #hoot-qstart -->

			<div id="hoot-plugins" class="hoot-plugins hoot-tabblock <?php if ( $activetab == 'plugins' ) echo 'hootactive'; ?>">

				<div class="wp-list-table widefat plugin-install">
					<div id="the-list">

						<div class="plugin-card">
							<div class="plugin-card-top">
								<div class="name column-name">
									<h3><?php esc_html_e( 'HootKit', 'strute' ) ?><img src="https://s.w.org/plugins/geopattern-icon/hootkit.svg" class="plugin-icon" alt=""></h3>
								</div>
								<div class="action-links">
									<ul class="plugin-action-buttons">
										<li><?php
											if ( class_exists( 'HootKit' ) ) {
												echo '<button type="button" class="button button-disabled" disabled="disabled">' . esc_html( 'Active', 'strute' ) . '</button>';
											} else {
												echo '<a href="#" class="hoot-btn-processplugin button button-primary hoot-btn-smallmsg" data-plugin="hootkit">';
													if ( file_exists( WP_PLUGIN_DIR . '/hootkit/hootkit.php' ) )
														esc_html_e( 'Activate', 'strute' );
													else
														esc_html_e( 'Install', 'strute' );
												echo '</a>';
											}
										?></li>
										<li><?php
											/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
											echo sprintf( esc_html__( '%1$sView Details%2$s', 'strute' ), '<a href="https://wordpress.org/plugins/hootkit/" target="_blank">', '</a>' );
										?></li>
									</ul>
								</div>
								<div class="desc column-description">
									<p><?php esc_html_e( 'This plugin adds widgets and sliders developed and styled specifically for the theme.', 'strute' ); ?></p>
									<p class="authors"><cite><?php
										/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
										echo sprintf( esc_html__( 'By %1$swpHoot%2$s', 'strute' ), '<a href="' . esc_url( $urlhoot ) . '" target="_blank">', '</a>' );
									?></cite></p>
								</div>
							</div>
						</div>

						<?php $import_config = apply_filters( 'hootimport_theme_config', array() ); // Hoot Import has been configured for active theme
						if ( ! empty( $import_config ) ) : ?>
						<div class="plugin-card">
							<div class="plugin-card-top">
								<div class="name column-name">
									<h3><?php esc_html_e( 'Hoot Import', 'strute' ) ?><img src="https://s.w.org/plugins/geopattern-icon/hoot-import.svg" class="plugin-icon" alt=""></h3>
								</div>
								<div class="action-links">
									<ul class="plugin-action-buttons">
										<li><?php
											if ( class_exists( 'HootImport' ) ) {
												echo '<button type="button" class="button button-disabled" disabled="disabled">' . esc_html( 'Active', 'strute' ) . '</button>';
											} else {
												echo '<a href="#" class="hoot-btn-processplugin button button-primary hoot-btn-smallmsg">';
													if ( file_exists( WP_PLUGIN_DIR . '/hoot-import/hoot-import.php' ) )
														esc_html_e( 'Activate', 'strute' );
													else
														esc_html_e( 'Install', 'strute' );
												echo '</a>';
											}
										?></li>
										<li><?php
											/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
											echo sprintf( esc_html__( '%1$sView Details%2$s', 'strute' ), '<a href="https://wordpress.org/plugins/hoot-import/" target="_blank">', '</a>' );
										?></li>
									</ul>
								</div>
								<div class="desc column-description">
									<p><?php esc_html_e( 'This plugin helps you import the demo data to help you get familiar with the theme.', 'strute' ); ?></p>
									<p class="authors"><cite><?php
										/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
										echo sprintf( esc_html__( 'By %1$swpHoot%2$s', 'strute' ), '<a href="' . esc_url( $urlhoot ) . '" target="_blank">', '</a>' );
									?></cite></p>
								</div>
							</div>
						</div>
						<?php endif; ?>

					</div>
				</div>

			</div><!-- #hoot-plugins -->

			<?php do_action( 'strute_about_tabcontent', $activetab ); ?>


		</div><!-- .hoot-abouttabs -->
		<a class="hoot-abouttheme-top" href="#hoot-about-sub"><span class="dashicons dashicons-arrow-up-alt"></span></a>
	</div><!-- .wrap -->
	<?php
}

/**
 * About Page displat Tab's content sections
 *
 * @since 1.0
 * @access public
 * @return mixed
 */
function strute_tabsections( $string ) {
	if ( in_array( $string, array( 'features', 'quickstart' ) ) ) :
		$features = strute_upstrings( $string );
		if ( !empty( $features ) && is_array( $features ) ) :
			foreach ( $features as $key => $feature ) :
				$style = empty( $feature['style'] ) ? 'std' : $feature['style'];
				?>
				<div class="tabsection <?php
					if ( $style == 'hero-top' || $style == 'hero-bottom' ) echo "tabsection-hero tabsection-{$style}";
					elseif ( $style == 'side' ) echo 'tabsection-sideinfo';
					elseif ( $style == 'aside' ) echo 'tabsection-asideinfo';
					else echo "tabsection-std";
					?>">

					<?php if ( $style == 'hero-top' || $style == 'hero-bottom' ) :
						if ( $style == 'hero-top' ) : ?>
							<h4 class="heading"><?php echo $feature['name']; ?><cite><span><?php esc_html_e( '* Premium Feature', 'strute' ) ?></span></cite></h4>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-hero-text">' . $feature['desc'] . '</div>'; ?>
						<?php endif; ?>
						<?php if ( !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-hero-img">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
						<?php if ( $style == 'hero-bottom' ) : ?>
							<h4 class="heading"><?php echo $feature['name']; ?><cite><span><?php esc_html_e( '* Premium Feature', 'strute' ) ?></span></cite></h4>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-hero-text">' . $feature['desc'] . '</div>'; ?>
						<?php endif; ?>

					<?php elseif ( $style == 'side' ) : ?>
						<div class="tabsection-side-wrap">
							<div class="tabsection-side-img">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
							<div class="tabsection-side-textblock">
								<?php if ( !empty( $feature['name'] ) ) : ?>
									<h4 class="heading"><?php echo $feature['name']; ?><cite><span><?php esc_html_e( '* Premium Feature', 'strute' ) ?></span></cite></h4>
								<?php endif; ?>
								<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-side-text">' . $feature['desc'] . '</div>'; ?>
							</div>
							<div class="clear"></div>
						</div>

					<?php elseif ( $style == 'aside' ) : ?>
						<?php if ( !empty( $feature['img-top'] ) ) : ?>
							<div class="tabsection-std-img attop" style="text-align: center;">
								<img src="<?php echo esc_url( $feature['img-top'] ); ?>" />
							</div>
						<?php endif; ?>
						<?php if ( !empty( $feature['blocks'] ) ) : ?>
							<div class="tabsection-aside-wrap">
							<?php foreach ( $feature['blocks'] as $key => $block ) {
								echo '<div class="tabsection-aside-block tabsection-aside-'.($key+1).'">';
									if ( !empty( $block['img'] ) ) : ?>
										<div class="tabsection-aside-img">
											<img src="<?php echo esc_url( $block['img'] ); ?>" />
										</div>
									<?php endif;
									if ( !empty( $block['name'] ) ) : ?>
										<h4 class="heading"><?php echo $block['name']; ?><cite><span><?php esc_html_e( '* Premium Feature', 'strute' ) ?></span></cite></h4>
									<?php endif;
									if ( !empty( $block['desc'] ) ) echo '<div class="tabsection-aside-text">' . $block['desc'] . '</div>';
								echo '</div>';
							} ?>
							<div class="clear"></div>
							</div>
						<?php endif; ?>
						<?php if ( !empty( $feature['img-bottom'] ) ) : ?>
							<div class="tabsection-std-img atbottom" style="text-align: center;">
								<img src="<?php echo esc_url( $feature['img-bottom'] ); ?>" />
							</div>
						<?php endif; ?>

					<?php else : ?>
						<?php if ( $style != 'img-bottom' && !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-std-img attop">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
						<div class="tabsection-std-textblock <?php if ( $style == 'img-bottom' ) echo 'attop'; else echo 'atbottom'; ?>">
							<?php if ( !empty( $feature['name'] ) ) : ?>
								<div class="tabsection-std-heading"><h4 class="heading"><?php echo $feature['name']; ?><cite><span><?php esc_html_e( '* Premium Feature', 'strute' ) ?></span></cite></h4></div>
							<?php endif; ?>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-std-text">' . $feature['desc'] . '</div>'; ?>
							<div class="clear"></div>
						</div>
						<?php if ( $style == 'img-bottom' && !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-std-img atbottom">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
					<?php endif; ?>

				</div><!-- .tabsection -->
				<?php
			endforeach;
		endif;
	endif;
}

/**
 * About Page Strings
 *
 * @since 1.0
 * @access public
 * @return mixed
 */
function strute_upstrings( $string ) {

	$features = $quickstart = array();
	$imagepath =  esc_url( hoot_data()->incuri . 'admin/images/' );
	$slug = strute_abouttag( 'slug' );
	$themename = strute_abouttag( 'name' );
	$urldemo = trailingslashit( strute_abouttag('urldemo') );

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Complete %1$sStyle %2$sCustomization%3$s', 'strute' ), '<br />', '<strong>', '</strong>' ),
		/* Translators: 1 is the theme name */
		'desc' => sprintf( esc_html__( 'Explore a wide range of customization options in %1$s Premium to create a unique website that matches your vision. Give your site the personality it deserves with diverse styling possibilities.', 'strute' ), $themename ),
		'style' => 'hero-top',
		);

	$features[] = array(
		'name' => esc_html__( 'Custom Colors &amp; Backgrounds for Sections', 'strute' ),
		/* Translators: 1 is the theme name */
		'desc' => sprintf( esc_html__( 'Tailor your site\'s design by assigning custom colors and backgrounds to specific sections like the header, footer, logo area, menu dropdown, content area, page title sections, etc.%2$s%3$sClick here to see demo of custom colors example%4$s', 'strute' ), $themename, '<hr>', '<a href="' . esc_url( $urldemo ) . 'style-customization-typography/" target="_blank">', '</a>' ),
		'img' => $imagepath . 'premium-colors.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Fonts and %1$sTypography Control%2$s', 'strute' ), '<span>', '</span>' ),
		'desc' => esc_html__( "Customize your site's typography by assigning different fonts, sizes, and colors to elements like the menu, topbar, logo, content headings, sidebar, footer, etc.", 'strute' ),
		'img' => $imagepath . 'premium-typography.jpg',
		);

	$features[] = array(
		'name' => esc_html__( '600+ Google Fonts', 'strute' ),
		'desc' => esc_html__( "With access to over 600 Google Fonts, you can easily select the perfect fonts to match your site's unique personality and style.", 'strute' ),
		'img' => $imagepath . 'premium-googlefonts.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Unlimites Sliders, Unlimites Slides', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Create unlimited sliders with as many slides as you need using the powerful HootKit plugin in %1$s Premium.%2$sShowcase custom images with text, create carousels, or even display vertical sliding lists for posts and WooCommerce products.', 'strute' ), $themename, '<hr>' ),
		'img' => $imagepath . 'premium-sliders.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Image Carousels', 'strute' ),
		'desc' => esc_html__( 'Easily add carousel widgets to your posts, sidebar, front page, or footer. The drag-and-drop interface makes creating and managing carousels simple and hassle-free.', 'strute' ),
		'img' => $imagepath . 'premium-carousels.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Additional Blog Layouts (including pinterest %1$stype mosaic)%2$s', 'strute' ), '<span>', '</span>' ),
		/* Translators: 1 is the theme name */
		'desc' => sprintf( esc_html__( '%1$s Premium offers various layout options for your post archives, including a stunning mosaic-style layout inspired by Pinterest.', 'strute' ), $themename ),
		'img' => $imagepath . 'premium-blogstyles.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Custom Widgets', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Unlock additional HootKit custom widgets crafted exclusively for %1$s Premium to enhance your site\'s content presentation.%2$sUse widgets like Buttons, Carousel Sliders, Carousel Posts Slider, Contact Info, Icon Lists, Notices, Number Blocks, Tabs, Toggles, Vcards, and more for a polished, professional look.', 'strute' ), $themename, '<hr>' ),
		'img' => $imagepath . 'premium-widgets.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Menu Icons', 'strute' ),
		'desc' => esc_html__( 'Choose from over 900 icons to enhance your main navigation menu links.', 'strute' ),
		'img' => $imagepath . 'premium-menuicons.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Premium Background Patterns (CC0)', 'strute' ),
		/* Translators: 1 is the theme name */
		'desc' => sprintf( esc_html__( '%1$s Premium includes a curated collection of additional premium background patterns to elevate your site\'s design. You can also upload custom background images or patterns to perfectly match your site\'s design.', 'strute' ), $themename ),
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Automatic Image Lightbox and %1$sWordPress Gallery%2$s', 'strute' ), '<span>', '</span>' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Enable seamless automatic lightbox functionality for image links with %1$s Premium.%2$sAutomatically transform standard WordPress galleries into stunning sliders in a lightbox for a more dynamic visual experience.', 'strute' ), $themename, '<hr>' ),
		'img' => $imagepath . 'premium-lightbox.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Post & Page %1$sSpecific Settings%2$s', 'strute' ), '<span>', '</span>' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => esc_html__( 'Optionally override global Customizer settings for an individual Post or Page. Adjust the sidebar layout, hide the title area, change feature image display, article background and width, add custom CSS, and configure other settings for each post or page.', 'strute' ),
		'img' => $imagepath . 'premium-postmeta.jpg',
		'style' => 'side',
		);

	$features[] = array(
		'name' => esc_html__( 'Custom 404 Error Page', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Ditch the boring "Not Found" error page! With %1$s Premium, design a custom 404 error page with tailored messages, images, and links to keep your visitors engaged and guide them back to your content.', 'strute' ), $themename ),
		'img' => $imagepath . 'premium-404.jpg',
		'style' => 'side',
		);

	$features[] = array(
		'style' => 'aside',
		'img-bottom' => $imagepath . 'premium-customcode.jpg',
		'blocks' => array(
			array(
				'name' => esc_html__( 'Custom Javascript', 'strute' ),
				'desc' => esc_html__( "Easily add custom JavaScript snippets to your header without touching the core code files. Whether it's Google Analytics, AdSense, or any custom code, you can add it effortlessly.", 'strute' ),
				'img' => $imagepath . 'premium-customjs.jpg',
				),
			array(
				'name' => esc_html__( 'Custom PHP Snippets', 'strute' ),
				'desc' => esc_html__( 'Easily add PHP code from wp-admin without editing core files, creating custom plugins, or using child themes. Your snippets stay safe and intact when you update the theme.', 'strute' ),
				'img' => $imagepath . 'premium-customphp.jpg',
				),
			),
		);

	$features[] = array(
		'style' => 'aside',
		'blocks' => array(
			array(
				'name' => esc_html__( 'Developers love {SCSS}', 'strute' ),
				/* Translators: 1 is the theme name */
				'desc' => sprintf( esc_html__( 'SCSS offers modularity and flexibility, making it a favorite for developers. %1$s Premium includes well-structured SCSS files for effortless customization and styling.', 'strute' ), $themename ),
				'img' => $imagepath . 'premium-scss.jpg',
				),
			array(
				'name' => esc_html__( 'Easy Import/Export', 'strute' ),
				'desc' => esc_html__( 'Switching hosts or applying a new child theme? Import or export your customizer settings in just a few clicks directly from the backend, making transitions seamless.', 'strute' ),
				'img' => $imagepath . 'premium-import-export.jpg',
				),
			),
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Continued %1$sLifetime Updates', 'strute' ), '<br />' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Active development of %1$s ensures your theme stays compatible with future WordPress versions for years to come so you don\'t have to worry about a broken website after an update.', 'strute' ), $themename ),
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => esc_html__( 'Priority Support', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Need assistance setting up %1$s? Upgrade to %1$s Premium for priority support from our growing team, ready to answer your questions.%2$sLooking for small modifications? Even if you\'re not a developer, our support staff can provide CSS snippets to help you achieve your desired look, and your changes will remain intact across updates.', 'strute' ), $themename, '<hr>' ),
		'img' => $imagepath . 'premium-support.jpg',
		);



	$settinglink = admin_url( 'options-reading.php' );
	$addpagelink = admin_url( 'post-new.php?post_type=page' );
	$quickstart[] = array(
		'name' => esc_html__( 'Setup Frontpage and Blog Page', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Users often want to create a landing Homepage/Frontpage to welcome their visitors, while a separate \'Blog\' page to list all their blog posts. To do this, follow these steps:%9$s%1$s
			%3$sIn your wp-admin area, click %11$sPages > Add New%12$s%4$s
			%3$sGive page a Title %7$s(lets call it "My Home Page")%8$s and %5$sPublish%6$s%4$s
			%3$sIn your wp-admin area, click %11$sPages > Add New%12$s%4$s
			%3$sGive page a Title %7$s(lets call it "My Blog")%8$s and %5$sPublish%6$s%4$s
			%3$sIn your wp-admin area, go to %10$sSettings > Reading%12$s%4$s
			%3$sSelect the %5$sStatic Page%6$s option.%4$s
			%3$sSelect the pages you created in Step 2 and 4 above.%4$s
			%3$s%5$sSave%6$s the Changes.%4$s
			%2$s', 'strute' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<br />',
										'<a href="' . esc_url( $settinglink ) . '">',
										'<a href="' . esc_url( $addpagelink ) . '">',
										'</a>'
				),
		'img' => $imagepath . 'qstart-staticpage.png',
		'style' => 'img-bottom',
		);

	$menulink = admin_url( 'nav-menus.php' );
	$quickstart[] = array(
		'name' => esc_html__( 'Setup Main Navigation Menu', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( '%1$s
			%3$sIn your wp-admin, go to %10$sAppearance > Menus%12$s%4$s
			%3$sClick on %5$screate a new menu%6$s link. %9$s%7$s(If you already have an existing menu, jump to Step 6)%8$s%4$s
			%3$sGive your menu a name and click %5$sCreate Menu%6$s%4$s
			%3$sNow add pages, categories, custom links etc to this menu.%4$s
			%3$sClick %5$sSave Menu%6$s%4$s
			%3$sClick %11$sManage Locations%12$s tab at the top%4$s
			%3$sSelect the menu you just created in the dropdown options.%4$s
			%3$sClick %5$sSave Changes%6$s%4$s
			%2$s%7$sTip: You can add "My Home Page" and "My Blog" pages created in above section to your menu.%8$s
			', 'strute' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<br />',
										'<a href="' . esc_url( $menulink ) . '">',
										'<a href="' . esc_url( $menulink ) . '?action=locations">',
										'</a>'
				),
		);

	$widgetslink = admin_url( 'widgets.php' );
	$customizelink = admin_url( 'customize.php' );
	$quickstart[] = array(
		'name' => esc_html__( 'Add Content to Frontpage', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( '%1$s
			%3$sIn your wp-admin, go to %10$sAppearance > Widgets%12$s%4$s
			%3$sAdd Widgets to the %5$sFrontpage Widget Areas%6$s%4$s
			%3$sYou can further manage Frontpage modules in your wp-admin by going to %11$sAppearance > Customizer%12$s and click %5$sFrontpage Modules%6$s section.%4$s
			%2$s
			%9$s%9$s
			%13$sExample: Display a full width slider%14$s
			To display a full width slider on your frontpage, set one of the Frontpage Module to full width in %11$sAppearance > Customizer > Frontpage Modules%12$s.%9$sNow go to the %10$sWidgets%12$s screen and add a %5$sHootKit Slider%6$s widget to this area.
			', 'strute' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<hr>',
										'<a href="' . esc_url( $widgetslink ) . '">',
										'<a href="' . esc_url( $customizelink ) . '">',
										'</a>', '<h4>', '</h4>'
				),
		'img' => $imagepath . 'qstart-fpmodule.png',
		'style' => 'img-bottom',
		);

	if ( ! class_exists( 'HootKit' ) ) {
		$quickstart[] = array(
			/* Translators: 1 is a line break */
			'name' => sprintf( esc_html__( 'Install%1$sHootKit plugin', 'strute' ), '<br />' )
					. '<small>' . esc_html__( '[ recommended ]', 'strute' ) . '</small>',
			/* Translators: 1 is the theme name */
			'desc' => sprintf( esc_html__( '%1$s works best with its companion plugin HootKit.', 'strute' ), $themename ) . '<hr><em>' . esc_html__( 'HootKit is a wpHoot plugin which adds various functionalities to the theme such as widgets and sliders which were developed and styled specifically for the theme.', 'strute' ) . '</em><hr><a href="' . esc_url( admin_url( "themes.php?page={$slug}-welcome&tab=plugins" ) ) . '">' . esc_html__( 'Go to the Plugins tab to install HootKit', 'strute' ) . '</a>',
			);
	}

	$hootthemeimplink = ( ! class_exists( 'HootImport' ) ) ? '<a href="' . esc_url( admin_url( "themes.php?page={$slug}-welcome&tab=plugins" ) ) . '">' . esc_html__( 'Go to the Plugins tab to install Hoot Import plugin', 'strute' ) . '</a>' : '<a href="' . esc_url( admin_url( "themes.php?page=hoot-import" ) ) . '">' . esc_html__( 'Go to Hoot Import', 'strute' ) . '</a>';
	$quickstart[] = array(
		/* Translators: 1 is a line break */
		'name' => sprintf( esc_html__( 'Install%1$sDemo Content', 'strute' ), '<br />' )
				. '<small>' . esc_html__( '[ optional ]', 'strute' ) . '</small>',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Importing demo content is the easiest way to setup your theme and make it look like the %1$sDemo Site%2$s', 'strute' ), '<a href="' . esc_url( $urldemo ) . '" target="_blank">', '</a>' )
			. '<hr>' . $hootthemeimplink,
		);


	return ( !empty( $$string ) ) ? $$string : '';


}