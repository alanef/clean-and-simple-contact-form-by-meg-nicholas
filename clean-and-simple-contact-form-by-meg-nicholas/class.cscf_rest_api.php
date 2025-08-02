<?php
/**
 * REST API handler for Clean and Simple Contact Form
 *
 * @package Clean and Simple Contact Form
 */

class cscf_rest_api {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes
	 */
	public function register_routes() {
		// Only register if REST API is enabled in settings
		if ( ! cscf_PluginSettings::RestApiEnabled() ) {
			return;
		}

		register_rest_route(
			'cscf/v1',
			'/submit',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submit_form' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'args'                => $this->get_endpoint_args(),
			)
		);
	}

	/**
	 * Check if user has permission to use REST API
	 *
	 * @return bool|WP_Error
	 */
	public function check_permission() {
		// Check if user is logged in
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'Authentication required.', 'clean-and-simple-contact-form-by-meg-nicholas' ),
				array( 'status' => 401 )
			);
		}

		// Check user capability
		$required_capability = cscf_PluginSettings::RestApiCapability();
		if ( ! current_user_can( $required_capability ) ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'Insufficient permissions.', 'clean-and-simple-contact-form-by-meg-nicholas' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Handle form submission via REST API
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response|WP_Error
	 */
	public function submit_form( $request ) {
		$data = array(
			'name'            => $request->get_param( 'name' ),
			'email'           => $request->get_param( 'email' ),
			'confirm-email'   => $request->get_param( 'confirm_email' ),
			'message'         => $request->get_param( 'message' ),
			'phone-number'    => $request->get_param( 'phone_number' ),
			'contact-consent' => $request->get_param( 'contact_consent' ),
			'email-sender'    => $request->get_param( 'email_sender' ),
		);

		// Get post ID if provided
		$post_id = $request->get_param( 'post_id' );

		// Create contact instance with REST API data
		$contact = new cscf_Contact();
		$contact->set_from_array( $data, $post_id, true ); // true indicates REST API context

		// Validate the contact form
		$is_valid = $contact->IsValid();

		if ( ! $is_valid ) {
			return new WP_Error(
				'validation_failed',
				esc_html__( 'Validation failed.', 'clean-and-simple-contact-form-by-meg-nicholas' ),
				array(
					'status' => 400,
					'errors' => $contact->Errors,
				)
			);
		}

		// Send the email
		$sent = $contact->SendMail();

		if ( ! $sent ) {
			return new WP_Error(
				'email_failed',
				esc_html__( 'Failed to send email.', 'clean-and-simple-contact-form-by-meg-nicholas' ),
				array( 'status' => 500 )
			);
		}

		// Action hook for REST API form submission
		do_action( 'cscf_form_submitted_rest', $contact );

		// Return success response
		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => cscf_PluginSettings::SentMessageHeading(),
			),
			200
		);
	}

	/**
	 * Get endpoint arguments for validation
	 *
	 * @return array
	 */
	private function get_endpoint_args() {
		$args = array(
			'name'    => array(
				'required'          => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'email'   => array(
				'required'          => true,
				'type'              => 'string',
				'format'            => 'email',
				'sanitize_callback' => 'sanitize_email',
			),
			'message' => array(
				'required'          => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_textarea_field',
			),
			'post_id' => array(
				'required'          => false,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
			),
		);

		// Add confirm email if enabled
		if ( cscf_PluginSettings::ConfirmEmail() ) {
			$args['confirm_email'] = array(
				'required'          => true,
				'type'              => 'string',
				'format'            => 'email',
				'sanitize_callback' => 'sanitize_email',
			);
		}

		// Add phone number if enabled
		if ( cscf_PluginSettings::PhoneNumber() ) {
			$args['phone_number'] = array(
				'required'          => cscf_PluginSettings::PhoneNumberMandatory(),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			);
		}

		// Add contact consent if enabled
		if ( cscf_PluginSettings::ContactConsent() ) {
			$args['contact_consent'] = array(
				'required' => true,
				'type'     => 'boolean',
			);
		}

		// Email sender option
		if ( cscf_PluginSettings::EmailToSender() ) {
			$args['email_sender'] = array(
				'required' => false,
				'type'     => 'boolean',
				'default'  => false,
			);
		}

		return $args;
	}
}