<?php
// Let child theme modify template structure
do_action( 'strute_frontpage' );

// Loads the header.php template.
get_header();

// Template modification Hook
do_action( 'strute_before_content_grid', 'frontpage.php' );
?>

<div <?php hoot_attr( 'frontpage-grid' ); ?>>

	<main <?php hoot_attr( 'frontpage-content' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'strute_main_start', 'frontpage.php' );

		// Get Sections List
		$fp_widgetized = hoot_get_mod( 'frontpage_sections_enable' );
		$sections = $fp_widgetized ? hoot_sortlist( hoot_get_mod( 'frontpage_sections' ) ) : hoot_sortlist( hoot_get_mod( 'frontpage_default_sections' ) );
		$is_cpreview = is_customize_preview();

		// Display Each Section according to ther sort order.
		if ( is_array( $sections ) && !empty( $sections ) ) :
			foreach ( $sections as $key => $section ) :
				if ( $key === 'image' ) {
					if ( $is_cpreview || empty( $section[ 'sortitem_hide' ] ) ) {
						strute_header_image();
					}
					continue;
				}
				if ( $is_cpreview ) {
					$section_class_preview = !empty( $section[ 'sortitem_hide' ] ) ? ' hootnoshow' : '';
					$section[ 'sortitem_hide' ] = false;
				} else {
					$section_class_preview = '';
				}
				if ( empty( $section[ 'sortitem_hide' ] ) ):

					// Set section type / context
					$context = ( strpos( $key, 'area_' ) === 0 ) ? str_replace( 'area_', '', $key ) : '';
					if ( ! empty( $context ) )
						$areatype = 'widgetarea';
					elseif ( $key == 'content' )
						$areatype = ( is_home() ) ? 'content-blog' : 'content-page';
					else
						$areatype = $key;
					$areatype = apply_filters( 'strute_frontpage_sections_switch', $areatype, $key, $sections );

					// Exit the loop except main content for a paged post list (blog)
					if ( $areatype != 'content-blog' && apply_filters( 'strute_paged_frontpage_hidemodules', is_paged() ) )
						continue;

					// Get section options
					$module_bg   = hoot_get_mod( "frontpage_sectionbg_{$key}-type" );
					$module_bg   = ( empty( $module_bg ) ) ? 'none' : $module_bg;
					$module_grid = hoot_get_mod( "frontpage_sectionbg_{$key}-grid" );
					$module_grid = ( $module_grid === 'boxed' ) ? 'boxed' : 'stretch';
					$module_cols = hoot_get_mod( "frontpage_sectionbg_{$key}-columns" );
					$module_cols = ( empty( $module_cols ) ) ? '100' : $module_cols;

					// Set section background and layout
					$section_class = 'module-bg-' . $module_bg;
					$section_class .= ' module-font-' . hoot_get_mod( "frontpage_sectionbg_{$key}-font" );
					$section_class .= ( $module_grid == 'stretch' ) ? ' frontpage-area-stretch' : ' frontpage-area-boxed';

					// Allow child themes to have templates
					$custom_template = hoot_get_frontpage_content( $key, false );
					if ( $custom_template ):
						include( $custom_template );
					else:

						switch( $areatype ):

							// Display Widget Areas
							case 'widgetarea':
								$areakey = 'area_' . $context;
								$structure = strute_get_column_span( $module_cols );
								$count = count( $structure );
								$displayarea = false;
								for ( $c = 1; $c <= $count ; $c++ ) {
									if ( is_active_sidebar( "hoot-frontpage-{$areakey}_{$c}" ) ) {
										$displayarea = true;
										break;
									}
								}
								$cp_colclass = array();
								if ( $is_cpreview ) {
									$maxcols = 4;
									for ( $c = 1; $c <= $maxcols ; $c++ ) {
										$cp_colclass[$c-1] = $c > $count ? ' hootnoshow' : '';
									}
									$count = $maxcols;
								}
								if ( $is_cpreview && !$displayarea ) {
									$section_class_preview .= ' nomarginpadding';
								}
								if ( $displayarea || $is_cpreview ) : ?>
									<div id="frontpage-<?php echo sanitize_html_class( $areakey ); ?>" <?php hoot_attr( 'frontpage-area', $areakey, 'frontpage-' . sanitize_html_class( $areakey ) . ' frontpage-area frontpage-widgetarea ' . esc_attr( $section_class ) . esc_attr( $section_class_preview ) ); ?>>
										<div class="hgrid">
											<?php
											for ( $c = 1; $c <= $count ; $c++ ) {
												$area_id = "frontpage-{$areakey}_{$c}";
												$structurekey = $c - 1;
												?>
												<div id="<?php echo sanitize_html_class( $area_id ); ?>" class="frontpage-areacol <?php
													if ( !empty( $structure[$structurekey] ) ) echo sanitize_html_class( $structure[$structurekey] );
													if ( !empty( $cp_colclass[ $c-1 ] ) ) echo hoot_sanitize_html_classes( $cp_colclass[ $c-1 ] );
												?>">
													<?php
													if ( is_active_sidebar( 'hoot-' . $area_id ) )
														dynamic_sidebar( 'hoot-' . $area_id );
													?>
												</div>
												<?php
											}
											?>
										</div>
									</div>
								<?php endif;
								break;

							// Display Blog Content
							case 'content-blog':
								wp_reset_postdata(); ?>
								<div id="frontpage-page-content" <?php hoot_attr( 'frontpage-area', $key, 'frontpage-area frontpage-page-content frontpage-blog-content ' . esc_attr( $section_class ) . esc_attr( $section_class_preview ) ); ?>>
									<?php
									$content_title = $fp_widgetized ? hoot_get_mod( "frontpage_sectionbg_{$key}-title" ) : '';
									if ( !empty( $content_title ) )
										echo '<div class="hgrid frontpage-page-content-title"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title">' . wp_kses_post( $content_title ) . '</h3></div></div>';
									elseif ( $is_cpreview )
										echo '<div class="hgrid frontpage-page-content-title hootnoshow"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title"></h3></div></div>';
									?>

									<div class="hgrid hoot-blogposts main-content-grid">

										<div <?php hoot_attr( 'content' ); ?>>
											<div <?php hoot_attr( 'content-wrap', 'frontpage-blog' ); ?>>

												<?php
												if ( have_posts() ) :

													echo '<div ' . hoot_get_attr( 'archive-wrap', 'frontpage-blog' ) . '>';

													// Template modification Hook
													do_action( 'strute_loop_start', 'frontpage.php' );

													$postcounter = 1;
													while ( have_posts() ) : the_post();
														// Loads the template-parts/content-{$post_type}.php template.
														hoot_set_data( 'archive_postcounter', $postcounter );
														hoot_get_content_template();
														$postcounter++;
													endwhile;

													// Template modification Hook
													do_action( 'strute_loop_end', 'frontpage.php' );

													echo '</div>';

													// Loads the template-parts/loop-nav.php template.
													get_template_part( 'template-parts/loop-nav' );

												else :
													// Loads the template-parts/error.php template.
													get_template_part( 'template-parts/error' );
												endif;
												?>

											</div><!-- #content-wrap -->
										</div><!-- #content -->

										<?php hoot_get_sidebar(); // Loads the sidebar.php template. ?>

									</div><!-- .main-content-grid -->
								</div>

								<?php break;

							// Display Page Content
							case 'content-page':
								wp_reset_postdata(); ?>
								<div id="frontpage-page-content" <?php hoot_attr( 'frontpage-area', $key, 'frontpage-area frontpage-page-content frontpage-staticpage-content ' . esc_attr( $section_class ) . esc_attr( $section_class_preview ) ); ?>>
									<?php
									$content_title = $fp_widgetized ? hoot_get_mod( "frontpage_sectionbg_{$key}-title" ) : '';
									if ( !empty( $content_title ) )
										echo '<div class="hgrid frontpage-page-content-title"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title">' . wp_kses_post( $content_title ) . '</h3></div></div>';
									elseif ( $is_cpreview )
										echo '<div class="hgrid frontpage-page-content-title hootnoshow"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title"></h3></div></div>';
									?>

									<div class="hgrid main-content-grid">

										<div <?php hoot_attr( 'content' ); ?>>
											<div <?php hoot_attr( 'content-wrap', 'frontpage-page', 'entry-content' ); ?>>
												<?php
												// Load the static page content
												while ( have_posts() ) : the_post();
													hoot_get_content_template();
												endwhile;
												?>
											</div><!-- #content-wrap -->
										</div><!-- #content -->

										<?php hoot_get_sidebar(); // Loads the sidebar.php template. ?>

									</div><!-- .main-content-grid -->
								</div>

								<?php break;

							default:
								// Allow mods to display content
								do_action( 'strute_frontpage_sections', $areatype, $sections, $section_class, $context, $section_class_preview );

						endswitch;

					endif;

				endif;
			endforeach;
		endif;

		// Template modification Hook
		do_action( 'strute_main_end', 'frontpage.php' );
		?>

	</main><!-- #frontpage-content -->

	<?php
	// Template modification Hook
	do_action( 'strute_after_main', 'frontpage.php' );
	?>

</div><!-- .frontpage-grid -->

<?php get_footer(); // Loads the footer.php template. ?>