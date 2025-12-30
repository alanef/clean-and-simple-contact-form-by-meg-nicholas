<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CSS Class mapping system for different CSS frameworks
 *
 * @package Clean and Simple Contact Form
 */
class cscf_CSS_Classes {

	/**
	 * CSS framework presets
	 *
	 * @var array
	 */
	private static $presets = array(
		'bootstrap' => array(
			'form_group'       => 'control-group form-group',
			'form_group_error' => 'error has-error',
			'input'            => 'form-control input-xlarge',
			'textarea'         => 'form-control input-xlarge',
			'checkbox'         => '',
			'button'           => 'btn btn-default',
			'help_text'        => 'help-inline help-block error',
			'input_group'      => 'input-group',
			'input_addon'      => 'input-group-addon',
			'text_error'       => 'text-error',
		),
		'modern' => array(
			'form_group'       => 'cscf-field',
			'form_group_error' => 'cscf-field--error',
			'input'            => 'cscf-input',
			'textarea'         => 'cscf-textarea',
			'checkbox'         => 'cscf-checkbox',
			'button'           => 'cscf-button',
			'help_text'        => 'cscf-error-message',
			'input_group'      => '',
			'input_addon'      => '',
			'text_error'       => 'cscf-error-message',
		),
		'theme-native' => array(
			'form_group'       => 'cscf-field',
			'form_group_error' => 'cscf-field--error',
			'input'            => '',
			'textarea'         => '',
			'checkbox'         => '',
			'button'           => 'wp-element-button',
			'help_text'        => 'cscf-error-message',
			'input_group'      => '',
			'input_addon'      => '',
			'text_error'       => 'cscf-error-message',
		),
		'none' => array(
			'form_group'       => 'cscf-field',
			'form_group_error' => 'cscf-field--error',
			'input'            => 'cscf-input',
			'textarea'         => 'cscf-textarea',
			'checkbox'         => 'cscf-checkbox',
			'button'           => 'cscf-button',
			'help_text'        => 'cscf-error-message',
			'input_group'      => 'cscf-input-group',
			'input_addon'      => 'cscf-input-addon',
			'text_error'       => 'cscf-error-message',
		),
	);

	/**
	 * Get all available presets
	 *
	 * @return array
	 */
	public static function get_presets() {
		return array(
			'bootstrap'    => __( 'Bootstrap (Default)', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			'modern'       => __( 'Modern (Card style)', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			'theme-native' => __( 'Theme Native', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			'none'         => __( 'Minimal (Semantic only)', 'clean-and-simple-contact-form-by-meg-nicholas' ),
		);
	}

	/**
	 * Get CSS classes for a specific element
	 *
	 * @param string $element The element key (e.g., 'form_group', 'input', 'button')
	 * @return string The CSS classes for that element
	 */
	public static function get( $element ) {
		$framework = cscf_PluginSettings::CssFramework();
		$classes   = self::get_all_classes();

		$class = isset( $classes[ $element ] ) ? $classes[ $element ] : '';

		/**
		 * Filter individual CSS class for an element
		 *
		 * @param string $class     The CSS class(es) for the element
		 * @param string $element   The element key
		 * @param string $framework The selected CSS framework
		 */
		return apply_filters( 'cscf_css_class', $class, $element, $framework );
	}

	/**
	 * Get all CSS classes for the current framework
	 *
	 * @return array
	 */
	public static function get_all_classes() {
		$framework = cscf_PluginSettings::CssFramework();

		// Get preset classes or fall back to bootstrap
		$classes = isset( self::$presets[ $framework ] ) ? self::$presets[ $framework ] : self::$presets['bootstrap'];

		/**
		 * Filter all CSS classes for the form
		 *
		 * Allows developers to completely customize the CSS class mapping
		 *
		 * @param array  $classes   The CSS classes array
		 * @param string $framework The selected CSS framework
		 */
		return apply_filters( 'cscf_css_classes', $classes, $framework );
	}

	/**
	 * Output a CSS class attribute if class is not empty
	 *
	 * @param string $element The element key
	 * @param string $additional Additional classes to append
	 * @return string The class attribute or empty string
	 */
	public static function attr( $element, $additional = '' ) {
		$class = self::get( $element );

		if ( ! empty( $additional ) ) {
			$class = trim( $class . ' ' . $additional );
		}

		if ( empty( $class ) ) {
			return '';
		}

		return 'class="' . esc_attr( $class ) . '"';
	}

	/**
	 * Check if Bootstrap stylesheet should be loaded
	 *
	 * @return bool
	 */
	public static function should_load_bootstrap_css() {
		$framework = cscf_PluginSettings::CssFramework();
		return 'bootstrap' === $framework && cscf_PluginSettings::LoadStyleSheet();
	}
}