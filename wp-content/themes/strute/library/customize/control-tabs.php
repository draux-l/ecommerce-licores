<?php
/**
 * Customize for Tabs (bettertabs), extend the WP customizer
 */

/**
 * Better Tabs Control Class extends the WP customizer
 *
 * @since 3.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class Hoot_Customize_Bettertabs_Control extends WP_Customize_Control {

	/**
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'bettertabs';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Define variable to whitelist tabs parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $tabs = '';

	/**
	 * Define variable to whitelist tabs parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $headingtabs = '';
	public $disablejstoggle = false;

	/**
	 * Define variable to whitelist identifier parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $identifier = '';

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'bettertabs' :
				if ( $this->tabs !== 'tab-start' && $this->tabs !== 'tab-end' ) {
					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif;

					if ( ! empty( $this->description ) ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
					<?php endif;

					if ( ! empty( $this->sublabel ) ) : ?>
						<span class="description customize-control-sublabel"><?php echo wp_kses_post( $this->sublabel ); ?></span>
					<?php endif;

					$tabskeys = explode( ',', $this->tabs );
					$headingtabsclass = !empty( $this->headingtabs ) ? ' hoot-tabs-heading' : '';
					$disablejstoggle = $this->disablejstoggle ? ' data-disablejstoggle="true"' : '';
					if ( is_array( $tabskeys ) && !empty( $tabskeys ) ) {
						echo '<div class="hoot-tabs-control' . $headingtabsclass . '"' . $disablejstoggle . '>';
						foreach ( $tabskeys as $tabkey ) {
							echo '<div class="hoot-tab-control" data-tab="' . esc_attr( $tabkey ) . '">';
							echo esc_html( ucwords( str_replace( ['-', '_'], ' ', $tabkey ) ) );
							echo '</div>';
						}
						echo '</div>';
					}
				}

		}

	}

}
endif;

/**
 * Hook into control display interface
 *
 * @since 3.0.0
 * @param object $wp_customize
 * @param string $id
 * @param array $setting
 * @return void
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
function hoot_customize_bettertabs_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'tabs' || $setting['type'] == 'bettertabs' ) {
			$setting['type'] == 'bettertabs';
			$wp_customize->add_control(
				new Hoot_Customize_Bettertabs_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hoot_customize_control_interface', 'hoot_customize_bettertabs_control_interface', 10, 3 );
endif;

/**
 * Modify the settings array and prepare tabs settings for Customizer Library Interface functions
 *
 * @since 3.0.0
 * @param array $value
 * @param string $key
 * @param array $setting
 * @param int $count
 * @return void
 */
function hoot_customize_prepare_bettertabs_settings( $value, $key, $setting, $count ) {

	if ( $setting['type'] == 'tabs' || $setting['type'] == 'bettertabs' ) {

		$setting = wp_parse_args( $setting, array(
			'label'       => '',
			'sublabel'    => '',
			'section'     => '',
			'priority'    => '',
			'description' => '',
			'options'     => array(),
			'headingtabs' => '',
			'disablejstoggle' => false,
			'identifier'  => $key,
			) );

		if( is_array( $setting['options'] ) && !empty( $setting['options'] ) ):

			$options_sanitizedkeys = array();
			foreach ( $setting['options'] as $tk => $to ) {
				$options_sanitizedkeys[ sanitize_html_class( $tk ) ] = $setting['options'][ $tk ];
			}
			$setting['options'] = $options_sanitizedkeys;

			$value[ "tabs-{$count}" ] = array(
				'label'       => $setting['label'],
				'sublabel'    => $setting['sublabel'],
				'section'     => $setting['section'],
				'type'        => 'bettertabs',
				'priority'    => $setting['priority'],
				'description' => $setting['description'],
				'headingtabs' => $setting['headingtabs'],
				'disablejstoggle' => $setting['disablejstoggle'],
				'identifier'  => $setting['identifier'],
				'tabs'        => implode( ',', array_keys( $setting['options'] ) ),
			);

			foreach ( $setting['options'] as $tabkey => $taboptions ) {
				if ( is_array( $taboptions ) && !empty( $taboptions ) ) {
					$value[ "tabs-{$count}-{$tabkey}" ] = array(
						'section'     => $setting['section'],
						'type'        => 'bettertabs',
						'priority'    => $setting['priority'],
						'identifier'  => $setting['identifier'],
						'tabs'        => 'tab-start',
					);

					foreach ( $taboptions as $okey => $osetting ) {
						if ( !empty( $osetting['type'] ) ) {

							// Add priority & section same as group
							$osetting['priority'] = $setting['priority'];
							$osetting['section'] = $setting['section'];

							$value[ $okey ] = $osetting;
							if ( $osetting['type'] === 'sortlist' && function_exists( 'hoot_customize_prepare_sortlist_settings' ) ) {
								$value = hoot_customize_prepare_sortlist_settings( $value, $okey, $osetting, $count );
							}

							if ( $osetting['type'] === 'betterrange' ) {
								if ( isset( $osetting['default'] ) && is_array( $osetting['default'] ) )
									$value[ $okey ]['default'] = json_encode( $osetting['default'] );
								if ( isset( $osetting['showreset'] ) && is_array( $osetting['showreset'] ) )
									$value[ $okey ]['showreset'] = json_encode( $osetting['showreset'] );
							}

						}
					}

					$value[ "tabs-{$count}-{$tabkey}-end" ] = array(
						'section'     => $setting['section'],
						'type'        => 'bettertabs',
						'priority'    => $setting['priority'],
						'identifier'  => $setting['identifier'],
						'tabs'        => 'tab-end',
					);
				}
			}

		endif;

	}

	return $value;

}
add_filter( 'hoot_customize_prepare_settings', 'hoot_customize_prepare_bettertabs_settings', 10, 4 );

