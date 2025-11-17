<?php
/**
 * Customize for Preset Patterns (betterbackground), extend the WP customizer
 */

/**
 * Betterbackground Control Class extends the WP customizer
 *
 * @since 3.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class Hoot_Customize_Betterbackground_Control extends WP_Customize_Control {

	/**
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'betterbackground';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Define variable to whitelist background parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $background = '';

	/**
	 * Define variable to whitelist identifier parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $identifier = '';

	/**
	 * Define variable to whitelist options parameter
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 */
	public $options = '';

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 * Add extra class names
	 *
	 * @since 3.0.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-' . $this->type . ' hoot-customize-control-' . $this->type . $this->background;
		if ( !empty( $this->identifier ) )
			$class .= ' hoot-control-id-' . $this->identifier;

		printf( '<li id="%s" class="%s">', esc_attr( $id ), esc_attr( $class ) );
		$this->render_content();
		echo '</li>';
	}

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'betterbackground' :

				switch ( $this->background ) {

					case 'button' :
						if (
							empty( $this->options ) ||
							( is_array( $this->options ) && in_array( 'image', $this->options ) && in_array( 'pattern', $this->options ) )
							):
							$value = $this->value();
							$value = ( empty( $value ) ) ? 'predefined' : $value;
							?>
							<div class="hoot-betterbackground-buttons">
								<span class="button hoot-betterbackground-button hoot-betterbackground-button-predefined <?php if ( 'predefined' == $this->value() ) echo 'selected'; else echo 'deactive'; ?>" data-value="predefined"><?php esc_html_e( 'Pattern', 'strute' ); ?></span><span class="button hoot-betterbackground-button hoot-betterbackground-button-custom <?php if ( 'custom' == $this->value() ) echo 'selected'; else echo 'deactive'; ?>" data-value="custom"><?php esc_html_e( 'Custom Image', 'strute' ); ?></span>
							</div>
							<input class="hoot-customize-control-betterbackground" value="<?php echo esc_attr( $this->value() ) ?>" <?php $this->link(); ?> type="hidden"/>
						<?php
						endif;
					break;

					case 'start' :
						if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;

						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
						<?php endif;

						if ( ! empty( $this->sublabel ) ) : ?>
							<span class="description customize-control-sublabel"><?php echo wp_kses_post( $this->sublabel ); ?></span>
						<?php endif;
					break;

					case 'end' :
					break;

				}

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
function hoot_customize_betterbackground_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'betterbackground' ) {
			$wp_customize->add_control(
				new Hoot_Customize_Betterbackground_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hoot_customize_control_interface', 'hoot_customize_betterbackground_control_interface', 10, 3 );
endif;

/**
 * Modify the settings array and prepare betterbackground settings for Customizer Library Interface functions
 *
 * @since 3.0.0
 * @param array $value
 * @param string $key
 * @param array $setting
 * @param int $count
 * @return void
 */
function hoot_customize_prepare_betterbackground_settings( $value, $key, $setting, $count ) {

	if ( $setting['type'] == 'betterbackground' ) {

		$setting = wp_parse_args( $setting, array(
			'label'       => '',
			'section'     => '',
			'priority'    => '',
			'choices'     => hoot_enum_background_pattern(),
			'default'     => array(),
			'description' => '',
			'options'     => array( 'image', 'color', 'repeat', 'position', 'attachment', 'pattern', 'size' ),
			'identifier'  => $key,
			) );
		$setting['default'] = wp_parse_args( $setting['default'], array(
			'type'       => 'predefined',
			'color'      => '',
			'image'      => '',
			'repeat'     => 'repeat',
			'position'   => 'top center',
			'attachment' => 'scroll',
			'pattern'    => '0',
			'size'       => 'original',
			) );

		if ( is_array( $setting['options'] ) && !empty( $setting['options'] ) ):
			$color = in_array( 'color', $setting['options'] );
			$image = in_array( 'image', $setting['options'] );
			$repeat = in_array( 'repeat', $setting['options'] );
			$position = in_array( 'position', $setting['options'] );
			$attachment = in_array( 'attachment', $setting['options'] );
			$pattern = ( in_array( 'pattern', $setting['options'] ) && !empty( $setting['choices'] ) );
			$size = in_array( 'size', $setting['options'] );
			$acb = isset( $setting['active_callback'] ) ? $setting['active_callback'] : false;

			$transport = isset( $setting['transport'] ) ? $setting['transport'] : false;

			if ( $color || $image || $pattern ):

				// Betterbackground Start
				$value[ "betterbackground-{$count}" ] = array(
					'label'       => $setting['label'],
					'section'     => $setting['section'],
					'type'        => 'betterbackground',
					'priority'    => $setting['priority'],
					'description' => $setting['description'],
					'identifier'  => $setting['identifier'],
					'background'  => 'start',
				);
				if ( $acb ) { $value[ "betterbackground-{$count}" ]['active_callback'] = $acb; }

				// Background Color :: (priority & section same as betterbackground)
				if ( $color ) :

					$value[ "{$key}-color" ] = array(
						'section'     => $setting['section'],
						'type'        => 'color',
						'priority'    => $setting['priority'],
						'default'     => $setting['default']['color'],
					);
					if ( $acb ) { $value[ "{$key}-color" ]['active_callback'] = $acb; }
					if ( $transport ) { $value[ "{$key}-color" ]['transport'] = $transport; }

				endif;

				// Background Type Button
				if ( $image && $pattern ) :

					$value[ "{$key}-type" ] = array(
						'section'     => $setting['section'],
						'type'        => 'betterbackground',
						'priority'    => $setting['priority'],
						'default'     => $setting['default']['type'],
						'identifier'  => $setting['identifier'],
						'background'  => 'button',
					);
					if ( $acb ) { $value[ "{$key}-type" ]['active_callback'] = $acb; }
					if ( $transport ) { $value[ "{$key}-type" ]['transport'] = $transport; }

				endif;

				// Background Image :: (priority & section same as betterbackground)
				if ( $image ) :

					$value[ "{$key}-image" ] = array(
						'section'     => $setting['section'],
						'type'        => 'image',
						'priority'    => $setting['priority'],
						'default'     => $setting['default']['image'],
					);
					if ( $acb ) { $value[ "{$key}-image" ]['active_callback'] = $acb; }
					if ( $transport ) { $value[ "{$key}-image" ]['transport'] = $transport; }

					if ( $size ) {
						$value[ "{$key}-size" ] = array(
							'section'     => $setting['section'],
							'type'        => 'select',
							'priority'    => $setting['priority'],
							'choices'     => hoot_enum_background_size(),
							'default'     => $setting['default']['size'],
						);
						if ( $acb ) { $value[ "{$key}-size" ]['active_callback'] = $acb; }
						if ( $transport ) { $value[ "{$key}-size" ]['transport'] = $transport; }
					}

					if ( $attachment ) {
						$value[ "{$key}-attachment" ] = array(
							'section'     => $setting['section'],
							'type'        => 'select',
							'priority'    => $setting['priority'],
							'choices'     => hoot_enum_background_attachment(),
							'default'     => $setting['default']['attachment'],
						);
						if ( $acb ) { $value[ "{$key}-attachment" ]['active_callback'] = $acb; }
						if ( $transport ) { $value[ "{$key}-attachment" ]['transport'] = $transport; }
					}

					if ( $repeat ) {
						$value[ "{$key}-repeat" ] = array(
							'section'     => $setting['section'],
							'type'        => 'select',
							'priority'    => $setting['priority'],
							'choices'     => hoot_enum_background_repeat(),
							'default'     => $setting['default']['repeat'],
						);
						if ( $acb ) { $value[ "{$key}-repeat" ]['active_callback'] = $acb; }
						if ( $transport ) { $value[ "{$key}-repeat" ]['transport'] = $transport; }
					}

					if ( $position ) {
						$value[ "{$key}-position" ] = array(
							'section'     => $setting['section'],
							'type'        => 'select',
							'priority'    => $setting['priority'],
							'choices'     => hoot_enum_background_position(),
							'default'     => $setting['default']['position'],
						);
						if ( $acb ) { $value[ "{$key}-position" ]['active_callback'] = $acb; }
						if ( $transport ) { $value[ "{$key}-position" ]['transport'] = $transport; }
					}

				endif;

				// Background Patterns :: (priority & section same as betterbackground)
				if ( $pattern ) :

					// Group Start
					$value[ "group-{$count}-p" ] = array(
						'section'     => $setting['section'],
						'type'        => 'group',
						'priority'    => $setting['priority'],
						'button'      => '<span class="hoot-betterbackground-button-pattern"></span>' . __( 'Select Pattern', 'strute' ),
						'identifier'  => $key . '-patterns',
						'group'       => 'start',
					);
					if ( $acb ) { $value[ "group-{$count}-p" ]['active_callback'] = $acb; }

					// Pattern Images
					$value[ "{$key}-pattern" ] = array(
						'section'     => $setting['section'],
						'type'        => 'radioimage',
						'priority'    => $setting['priority'],
						'choices'     => $setting['choices'],
						'default'     => $setting['default']['pattern'],
					);
					if ( $acb ) { $value[ "{$key}-pattern" ]['active_callback'] = $acb; }
					if ( $transport ) { $value[ "{$key}-pattern" ]['transport'] = $transport; }
					if ( apply_filters( 'hoot_customize_pattern_pnote', false ) ) {
						$value[ "{$key}-pnote" ] = array(
							'section'     => $setting['section'],
							'type'        => 'pnote',
							'priority'    => $setting['priority'],
							'content'     => esc_html__( 'Premium version comes with a much bigger curated collection of patterns.', 'strute' ),
						);
					}

					// Group End
					$value[ "group-{$count}-p-end" ] = array(
						'section'     => $setting['section'],
						'type'        => 'group',
						'priority'    => $setting['priority'],
						'identifier'  => $key . '-patterns',
						'group'       => 'end',
					);
					if ( $acb ) { $value[ "group-{$count}-p-end" ]['active_callback'] = $acb; }

				endif;

				// Betterbackground End
				$value[ "betterbackground-{$count}-end" ] = array(
					'section'     => $setting['section'],
					'type'        => 'betterbackground',
					'priority'    => $setting['priority'],
					'identifier'  => $setting['identifier'],
					'background'  => 'end',
				);
				if ( $acb ) { $value[ "betterbackground-{$count}-end" ]['active_callback'] = $acb; }

			endif;
		endif;

	}

	return $value;

}
add_filter( 'hoot_customize_prepare_settings', 'hoot_customize_prepare_betterbackground_settings', 10, 4 );

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
function hoot_customize_sanitize_betterbackground_callback( $callback, $type, $setting, $name ) {
	if ( $type == 'betterbackground' && isset( $setting['background'] ) && $setting['background'] == 'button' )
		$callback = 'hoot_sanitize_background_type';
	return $callback;
}
add_filter( 'hoot_customize_sanitize_callback', 'hoot_customize_sanitize_betterbackground_callback', 5, 4 );