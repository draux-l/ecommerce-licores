<?php
/**
 * Customize for Range (betterrange), extend the WP customizer
 */

/**
 * Better Range Control Class extends the WP customizer
 *
 * @since 3.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class Hoot_Customize_Betterrange_Control extends WP_Customize_Control {

	/**
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'betterrange';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $displaysuffix = '';
	public $showreset = '';
	public $mediaquery = '';

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function render_content() {
		$haslabel = ! empty( $this->label ) ? '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>' : false;
		$hasdesc = ! empty( $this->description ) ? '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>' : false ;
		$hasmediaquery = ! empty( $this->mediaquery ) ? '<span class="hoot-mediaswitcher">
					<i title="' . esc_attr__( 'Desktop', 'strute' ) . '" data-device="desktop" class="dashicons dashicons-desktop hootactive"></i>
					<i title="' . esc_attr__( 'Tablet', 'strute' ) .  '" data-device="tablet"  class="dashicons dashicons-tablet"></i>
					<i title="' . esc_attr__( 'Mobile', 'strute' ) .  '" data-device="mobile"  class="dashicons dashicons-smartphone"></i>
				</span>' : false;

		switch ( $this->type ) {

			case 'betterrange' : ?>
				<div class="betterrange-labels"><?php
					if ( $haslabel || $hasmediaquery ) {
						if ( $hasmediaquery ) echo '<div class="hoot-control-mediaquery-wrap">';
							if ( $haslabel ) echo $haslabel;
							if ( $hasmediaquery ) echo $hasmediaquery;
						if ( $hasmediaquery ) echo '</div>';
					}

					if ( $hasdesc ) {
						if ( $hasmediaquery && ! $haslabel ) echo '<div class="hoot-control-mediaquery-wrap">';
							echo $hasdesc;
							if ( $hasmediaquery && ! $haslabel ) echo $hasmediaquery;
						if ( $hasmediaquery && ! $haslabel ) echo '</div>';
					}

					if ( ! empty( $this->sublabel ) ) : ?>
						<span class="description customize-control-sublabel"><?php echo wp_kses_post( $this->sublabel ); ?></span>
					<?php endif;
				?></div>

				<div class="betterrange-box"><?php
					$value = $this->value();
					$showreset = ! empty( $this->showreset ) ? $this->showreset : false;
					$mediavalues = $hasmediaquery && is_string( $value ) ? json_decode( $value, true ) : false;
					$mediaresets = $hasmediaquery && is_string( $showreset ) ? json_decode( $showreset, true ) : false;
					$looparray = $hasmediaquery ? array( 'desktop', 'tablet', 'mobile' ) : array( 'allmedia' );
					$count = 0;
					foreach ( $looparray as $media ) {
						$mediavalue = $media === 'allmedia' ? $value : ( isset( $mediavalues[ $media ] ) ? $mediavalues[ $media ] : '' );
						$mediashowreset = $media === 'allmedia' ? $showreset : ( isset( $mediaresets[ $media ] ) ? $mediaresets[ $media ] : false );
						$boxdisplay = $media === 'allmedia' || $media === 'desktop' ? '' : 'display:none';
						echo '<div class="betterrange-mediabox" style="' . esc_attr( $boxdisplay ) . '" data-device="' . esc_attr( $media ) . '">';
						$this->render_betterrange_control( $mediavalue, $mediashowreset );
						echo '</div>';
						$count++;
					}
					?>
					<input type="hidden" value="<?php echo esc_attr( $value ); ?>" <?php $this->link(); ?> />
				</div>

				<?php
				break;

		}

	}

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function render_betterrange_control( $value, $showreset ) {
		?>
		<input class="betterrange-range" type="range" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $value ); ?>" />
		<input class="betterrange-number" type="number" <?php
			?> value="<?php echo esc_attr( $value ); ?>" />
		<?php if ( ! empty( $this->displaysuffix ) ) echo ' <span>' . esc_html( $this->displaysuffix ) .'</span>'; ?>
		<?php if ( ! empty( $showreset )  ) : ?>
			<span class="betterrange-reset dashicons dashicons-update" title="<?php echo esc_attr( 'Reset', 'strute' ) ?>" data-resetval="<?php echo esc_attr( intval( $showreset ) ) ?>"></span>
		<?php endif; ?>
		<?php
	}

}
endif;

/**
 * Modify the settings array and prepare sortlist settings
 */
function hoot_customize_prepare_betterrange_settings( $value, $key, $setting, $count ) {
	if ( $setting['type'] == 'betterrange' ) {
		if ( isset( $setting['default'] ) && is_array( $setting['default'] ) )
			$setting['default'] = json_encode( $setting['default'] );
		if ( isset( $setting['showreset'] ) && is_array( $setting['showreset'] ) )
			$setting['showreset'] = json_encode( $setting['showreset'] );
		$value[ $key ] = $setting;
	}
	return $value;
}
add_filter( 'hoot_customize_prepare_settings', 'hoot_customize_prepare_betterrange_settings', 10, 4 );

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
function hoot_customize_betterrange_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'betterrange' ) {
			$wp_customize->add_control(
				new Hoot_Customize_Betterrange_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hoot_customize_control_interface', 'hoot_customize_betterrange_control_interface', 10, 3 );
endif;

/**
 * Add sanitization function
 *
 * @since 3.0.0
 * @param string $callback
 * @param string $type
 * @param array $setting
 * @param string $name name (id) of the setting
 * @return string
 */
function hoot_customize_sanitize_betterrange_callback( $callback, $type, $setting, $name ) {
	if ( $type == 'betterrange' ) {
		$callback = !empty( $setting['mediaquery'] ) ? 'hoot_sanitize_jsonstring' : 'hoot_sanitize_range';
	}
	return $callback;
}
add_filter( 'hoot_customize_sanitize_callback', 'hoot_customize_sanitize_betterrange_callback', 5, 4 );

