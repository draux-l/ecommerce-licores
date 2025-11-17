<?php
/**
 * Scroller Extension adds Scrollpoints and Waypoints sub-extensions
 *  > sticky-sitehead (waypoints)
 *  > goto-top buttons (scrollpoints, waypoints optional)
 * 
 * This file is loaded at 'after_setup_theme' hook with 2 priority.
 */

/**
 * Scroller class. This wraps everything up nicely.
 *
 * @since 3.0.0
 */
final class Hoot_Scroller {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 3.0.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Check if this is customizer preview
	 *
	 * @since 3.0.0
	 * @access private
	 */
	private $is_cpreview = false;

	/**
	 * Enabled Modules
	 *
	 * @since 3.0.0
	 * @access private
	 */
	private $modules = array();

	/**
	 * Initialize everything
	 * 
	 * @since 3.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Add the required scripts and styles */
		add_action( 'wp_loaded', array( $this, 'init' ) );

	}

	/**
	 * Initialize everything
	 * 
	 * @since 3.0.0
	 * @access public
	 * @return void
	 */
	public function init() {
		$this->is_cpreview = is_customize_preview();

		/* Init Support */

		// 1. Goto Top Button
		if ( $this->is_cpreview || !hoot_get_mod( 'disable_goto_top' ) )
			$this->modules[] = 'goto-top';

		// 2. Sticky Sitehead
		if ( $this->is_cpreview || hoot_get_mod( 'sticky_sitehead_dtp' ) || hoot_get_mod( 'sticky_sitehead_mob' ) )
			$this->modules[] = 'sticky-sitehead';

		// 3. AOS
		if ( ! $this->is_cpreview && hoot_get_mod( 'enable_anims' ) && ( hoot_getchecked( 'enabled_anims', 'aos' ) || hoot_getchecked( 'enabled_anims', 'aosmob' ) ) )
			$this->modules[] = 'aos';

		// 4. Prev/Next Preview
		if ( hoot_get_mod( 'enable_anims' ) && hoot_getchecked( 'enabled_anims', 'prevnext' ) )
			$this->modules[] = 'loopnav-preview';

		// 5. Scrollpoints
		$scope = ( hoot_get_mod( 'enable_anims' ) && (
					hoot_getchecked( 'enabled_anims', 'scrollhash' ) || hoot_getchecked( 'enabled_anims', 'scrollmain' )
				 ) ) ? hoot_get_mod( 'autoscroll_scope' ) : false;
		if ( $scope === 'sitewide' ) {
			$this->modules[] = 'scrollscope-sitelinks';
		} elseif ( $scope === 'menu' ) {
			$this->modules[] = 'scrollscope-menu';
		} elseif ( $scope === 'menu-posts' ) {
			$this->modules[] = 'scrollscope-menu';
			$this->modules[] = 'scrollscope-archive-entry';
			$this->modules[] = 'scrollscope-single-entry';
			$this->modules[] = 'scrollscope-prevnext';
		}

		$this->modules = apply_filters( 'hoot_scroller_modules', $this->modules );
		$this->modules = is_array( $this->modules ) ? $this->modules : array();

		/* 1. Insert Goto Top Button */
		if ( in_array( 'goto-top', $this->modules ) ) {
			add_action( 'strute_body_end', array( $this, 'goto_top_button' ) );
		}

		/* 2. Enable Sticky sitehead */
		if ( in_array( 'sticky-sitehead', $this->modules ) ) {
			add_filter( 'hoot_attr_topbar', array( $this, 'hoot_attr_topbar' ), 10 );
			add_filter( 'hoot_attr_sitehead', array( $this, 'hoot_attr_sitehead' ), 10 );
		}

		/* 3. AOS */
		if ( in_array( 'aos', $this->modules ) || in_array( 'aosmob', $this->modules ) ) {
			do_action( 'hoot_aos_init' );
		}

		/* 4. Prev/Next Preview */

		/* 5. Enable Scroll Points */
		if ( in_array( 'scrollscope-sitelinks', $this->modules ) ) {
			add_filter( 'hoot_attr_topbar', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			add_filter( 'hoot_attr_page-wrapper', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			add_filter( 'hoot_attr_loop-nav', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
		} else {
			if ( in_array( 'scrollscope-menu', $this->modules ) ) {
				add_filter( 'hoot_attr_menu', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			}
			if ( in_array( 'scrollscope-archive-entry', $this->modules ) ) {
				add_filter( 'hoot_attr_archive-wrap', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			}
			if ( in_array( 'scrollscope-single-entry', $this->modules ) ) {
				add_filter( 'hoot_attr_entry-content', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			}
			if ( in_array( 'scrollscope-prevnext', $this->modules ) ) {
				add_filter( 'hoot_attr_loop-nav', array( $this, 'hoot_attr_scrollpoint_class' ), 10 );
			}
		}

		/* Add the required scripts and styles */
		if ( $this->is_cpreview || !empty( $this->modules ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wp_styles_scripts' ), 15 );
		}

	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 3.0.0
	 */
	function enqueue_wp_styles_scripts( $hook ) {

		/* Enqueue Waypoint */
		if ( in_array( 'goto-top', $this->modules ) || in_array( 'sticky-sitehead', $this->modules ) || in_array( 'loopnav-preview', $this->modules ) ) {
			$script_uri = hoot_locate_script( hoot_data()->liburi . 'js/jquery.waypoints' );
			wp_enqueue_script( 'jquery-waypoints', $script_uri, array( 'jquery' ), '4.0.1', true );
		}

		/* Enqueue AOS */
		if ( in_array( 'aos', $this->modules ) || in_array( 'aosmob', $this->modules ) ) {
			$script_uri = hoot_locate_script( 'js/aos' );
			wp_enqueue_script( 'aos', $script_uri, array(), '2.3.4', true );
			$style_uri = hoot_locate_style( 'js/aos' );
			wp_enqueue_style( 'aos', $style_uri, false, '2.3.4' );
		}

		/* Enqueue Scroller */
		$script_uri = hoot_locate_script( hoot_data()->liburi . 'js/scroller' );
		wp_enqueue_script( 'hoot-scroller', $script_uri, array(), hoot_data()->hoot_version, true );

	}

	/**
	 * Filter element attributes to enable sticky
	 *
	 * @since 3.0.0
	 * @access public
	 * @param array $attr
	 * @param string $context
	 * @return array
	 */
	function hoot_attr_topbar( $attr ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		// Desktop
		if ( hoot_get_mod( 'sticky_sitehead_dtp' ) ) {
			$layout = hoot_get_mod( 'sticky_sitehead_dtp_layout' );
			if ( in_array( $layout, array( 'topbar' ) ) ) {
				$attr['class'] .= ' stickydtp';
				$attr['data-stickydtp'] = 'stickydtp-' . $layout;
			}
		}
		// Mobile
		if ( hoot_get_mod( 'sticky_sitehead_mob' ) ) {
			$layout = hoot_get_mod( 'sticky_sitehead_mob_layout' );
			if ( in_array( $layout, array( 'topbar' ) ) ) {
				$attr['class'] .= ' stickymob';
				$attr['data-stickymob'] = 'stickymob-' . $layout;
			}
		}
		return $attr;
	}

	function hoot_attr_sitehead( $attr ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		if ( hoot_get_mod( 'sticky_accent' ) === 'accent' ) {
			$attr['class'] .= ' sticky-accent';
		}
		// Desktop
		if ( hoot_get_mod( 'sticky_sitehead_dtp' ) ) {
			$layout = hoot_get_mod( 'sticky_sitehead_dtp_layout' );
			if ( ! in_array( $layout, array( 'topbar' ) ) ) {
				$attr['class'] .= ' stickydtp';
				$attr['data-stickydtp'] = 'stickydtp-' . $layout;
			}
		}
		// Mobile
		if ( hoot_get_mod( 'sticky_sitehead_mob' ) ) {
			$layout = hoot_get_mod( 'sticky_sitehead_mob_layout' );
			if ( ! in_array( $layout, array( 'topbar' ) ) ) {
				$attr['class'] .= ' stickymob';
				$attr['data-stickymob'] = 'stickymob-' . $layout;
			}
		}
		return $attr;
	}

	/**
	 * Enable auto scroller scope
	 *
	 * @since 3.0.0
	 * @access public
	 * @param array $attr
	 * @return array
	 */
	function hoot_attr_scrollpoint_class( $attr ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		$attr['class'] .= ' autoscroller';
		return $attr;
	}

	/**
	 * Insert Top Button
	 *
	 * @since 3.0.0
	 */
	function goto_top_button() {
		$icon_class = hoot_get_mod( 'goto_top_icon');
		$icon_class = !empty( $icon_class ) ? $icon_class : 'fa-chevron-up fas';
		$style_class = hoot_get_mod( 'goto_top_icon_style');
		$style_class = !empty( $style_class ) ? $style_class : 'style1';
		$mobile_class = hoot_get_mod( 'goto_top_mobile');
		$mobile_class = !empty( $mobile_class ) ? '' : ' hidemobile';

		$attr = array(
			'href' => '#page-wrapper', // default browser behavior if hootscroller didn't work
			'data-scroll-to' => 'body', // hootscroller
			'class' => 'fixed-goto-top waypoints-goto-top goto-top-' . esc_attr( $style_class ) . $mobile_class,
		);
		if ( $this->is_cpreview ) {
			$attr['class'] .= ' sureshow';
			if ( hoot_get_mod( 'disable_goto_top' ) ) $attr['class'] .= ' hootnoshow';
		}
		
		echo '<a ' . hoot_get_attr( 'goto-top', '', $attr ) . '><i class="' . esc_attr( $icon_class ) . '"></i>' . '</a>';
	}

	/**
	 * Returns the instance.
	 *
	 * @since 3.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

}

/* Initialize class */
global $hoot_scroller;
$hoot_scroller = Hoot_Scroller::get_instance();