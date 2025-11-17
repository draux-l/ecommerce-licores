<?php
// Get Content
global $hoot_data;
hoot_set_data( 'topbar_left', is_active_sidebar( 'hoot-topbar-left' ) );
hoot_set_data( 'topbar_right', is_active_sidebar( 'hoot-topbar-right' ) );

// Template modification Hook
do_action( 'strute_before_topbar' );

// Display Topbar
$tparts = 0;
$hoot_topbar_left = hoot_data()->topbar_left || hoot_data()->topbar_right;
$hoot_topbar_right = hoot_data()->topbar_left || hoot_data()->topbar_right;
if ( !empty( $hoot_topbar_left ) ) $tparts++;
if ( !empty( $hoot_topbar_right ) ) $tparts++;

if ( $tparts ) :
	$topbar_grid = ( hoot_get_mod( 'topbar_grid' ) == 'stretch' ) ? 'hgrid-stretch' : 'hgrid';

	?>
	<div <?php hoot_attr( 'topbar', '', 'inline-nav js-search social-icons-invertx hgrid-stretch' ); ?>>
		<div class="<?php echo $topbar_grid; ?>">
			<div class="hgrid-span-12">

				<div class="topbar-inner hootflex<?php echo $tparts === 2 ? ' topbar-parts' : ' hootflex-center'; ?>">
					<?php if ( $hoot_topbar_left ): ?>
						<?php $topbarid = $tparts === 2 ? 'left' : 'center'; ?>
						<div id="topbar-<?php echo $topbarid; ?>" class="topbar-part">
							<?php dynamic_sidebar( 'hoot-topbar-left' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $hoot_topbar_right ): ?>
						<?php $topbarid = $tparts === 2 ? 'right' : 'center'; ?>
						<div id="topbar-<?php echo $topbarid; ?>" class="topbar-part">
							<?php dynamic_sidebar( 'hoot-topbar-right' ); ?>
						</div>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</div>
	<?php

endif;

// Template modification Hook
do_action( 'strute_after_topbar' );