<?php

class CSCF_View {
	/**
	 * Path of the view to render
	 */
	var string $view = '';
	/**
	 * Variables for the view
	 */
	var array $vars = array();

	/**
	 * Construct a view from a file in the
	 */
	public
	function __construct(
		$view
	) {

		if ( file_exists( CSCF_PLUGIN_DIR . '/views/' . $view . '.view.php' ) ) {
			$this->view = CSCF_PLUGIN_DIR . '/views/' . $view . '.view.php';
		} else {
			// translators: %s is the path to the view file
			wp_die( sprintf( esc_html__( 'View %s not found', 'clean-and-simple-contact-form-by-meg-nicholas' ), esc_html( CSCF_PLUGIN_URL . '/views/' . $view . '.view.php' ) ) );
		}
	}

	/**
	 * set a variable which gets rendered in the view
	 */
	public function Set(
		$name, $value
	) {
		$this->vars[ $name ] = $value;
	}

	/**
	 * render the view
	 */
	public function Render() {
		extract( $this->vars, EXTR_SKIP );
		ob_start();
		include $this->view;

		return str_replace( array( '\n', '\r' ), '', ob_get_clean() );
	}
}    

