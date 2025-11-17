<?php
// Get Content
global $hoot_data;
hoot_set_data( 'below_sitehead_left', is_active_sidebar( 'hoot-below-sitehead-left' ) );
hoot_set_data( 'below_sitehead_right', is_active_sidebar( 'hoot-below-sitehead-right' ) );

// Template modification Hook
do_action( 'strute_before_below_sitehead' );

// Display Below sitehead
$hoot_below_sitehead_left = hoot_data()->below_sitehead_left || hoot_data()->below_sitehead_right;
$hoot_below_sitehead_right = hoot_data()->below_sitehead_left || hoot_data()->below_sitehead_right;
if ( !empty( $hoot_below_sitehead_left ) || !empty( $hoot_below_sitehead_right ) ) :

	$below_sitehead_grid = ( hoot_get_mod( 'below_sitehead_grid' ) == 'stretch' ) ? ' below-sitehead-stretch' : ' below-sitehead-boxed'; ?>
	<div <?php hoot_attr( 'below-sitehead', '', 'inline-nav js-search' . $below_sitehead_grid ); ?>>
		<div class="hgrid">
			<div class="hgrid-span-12">

				<div class="below-sitehead-inner<?php if ( !empty( $hoot_below_sitehead_left ) && !empty( $hoot_below_sitehead_right ) ) echo ' below-sitehead-parts'; ?>">
					<?php
					if ( $hoot_below_sitehead_left ):
						$below_siteheadid = ( $hoot_below_sitehead_right ) ? 'left' : 'center';

						// Template modification Hook
						do_action( 'strute_sidebar_start', 'below-sitehead-left', $below_siteheadid );
						?>

						<div id="below-sitehead-<?php echo $below_siteheadid; ?>" class="below-sitehead-part">
							<?php dynamic_sidebar( 'hoot-below-sitehead-left' ); ?>
						</div>

						<?php
						// Template modification Hook
						do_action( 'strute_sidebar_end', 'below-sitehead-left', $below_siteheadid );

					endif;
					?>

					<?php
					if ( $hoot_below_sitehead_right ):
						$below_siteheadid = ( $hoot_below_sitehead_left ) ? 'right' : 'center';

						// Template modification Hook
						do_action( 'strute_sidebar_start', 'below-sitehead-right', $below_siteheadid );
						?>

						<div id="below-sitehead-<?php echo $below_siteheadid; ?>" class="below-sitehead-part">
							<?php dynamic_sidebar( 'hoot-below-sitehead-right' ); ?>
						</div>

						<?php
						// Template modification Hook
						do_action( 'strute_sidebar_end', 'below-sitehead-right', $below_siteheadid );

					endif;
					?>
				</div>

			</div>
		</div>
	</div>
	<?php

endif;

// Template modification Hook
do_action( 'strute_after_below_sitehead' );