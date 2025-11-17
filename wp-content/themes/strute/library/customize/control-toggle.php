<?php
/**
 * Customize for Toggle (bettertoggle), extend the WP customizer
 */

/**
 * Better Toggle Control Class extends the WP customizer
 *
 * @since 3.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class Hoot_Customize_Bettertoggle_Control extends WP_Customize_Control {

	/**
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'bettertoggle';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Define variable to whitelist inverttoggle parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $inverttoggle = false;

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'bettertoggle' : ?>
				<div class="bettertoggle-control">

					<div class="bettertoggle-labels"><?php
						if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;
		
						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
						<?php endif;
		
						if ( ! empty( $this->sublabel ) ) : ?>
							<span class="description customize-control-sublabel"><?php echo wp_kses_post( $this->sublabel ); ?></span>
						<?php endif;
					?></div>

					<div class="bettertoggle-input">
						<div class="bettertogglebox">
							<input type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
							<span class="<?php echo ( $this->inverttoggle ? 'bettertoggle-invert' : 'bettertoggle' ); ?>"></span>
						</div>
					</div>

				</div>
				<?php
				break;

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
function hoot_customize_bettertoggle_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'bettertoggle' ) {
			$wp_customize->add_control(
				new Hoot_Customize_Bettertoggle_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hoot_customize_control_interface', 'hoot_customize_bettertoggle_control_interface', 10, 3 );
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
function hoot_customize_sanitize_bettertoggle_callback( $callback, $type, $setting, $name ) {
	if ( $type == 'bettertoggle' ) {
		$callback = 'hoot_sanitize_checkbox';
	}
	return $callback;
}
add_filter( 'hoot_customize_sanitize_callback', 'hoot_customize_sanitize_bettertoggle_callback', 5, 4 );

