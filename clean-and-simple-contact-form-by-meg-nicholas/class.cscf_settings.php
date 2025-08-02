<?php

/*
 * creates the settings page for the plugin
*/


class cscf_settings {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'admin_menu', array(
				$this,
				'add_plugin_page',
			) );
			add_action( 'admin_init', array(
				$this,
				'page_init',
			) );
		}
	}


	public function add_plugin_page() {

		// This page will be under "Settings".
		add_options_page(
			esc_html__( 'Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			esc_html__( 'Contact Form', 'clean-and-simple-contact-form-by-meg-nicholas' ),
			'manage_options',
			'contact-form-settings',
			array(
				$this,
				'create_admin_page',
			)
		);
	}

	public function create_admin_page() {
		?>
        <h2><?php esc_html_e( 'Clean and Simple Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></h2>
        <div style="float:left;padding:20px; max-width: 1200px;margin-right: 10%;" class="postbox cscf-settings">
            <table class="form-table">
                <tbody>
                </tbody>
            </table>

			<?php if ( cscf_PluginSettings::IsJetPackContactFormEnabled() ) { ?>
                <p class="highlight">
					<?php esc_html_e( 'NOTICE: You have JetPack\'s Contact Form enabled please deactivate it or use the shortcode [cscf-contact-form] instead.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
                    &nbsp; </p>
			<?php } ?>
            <h3><?php esc_html_e( 'How to Use', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></h3>

            <p class="howto"><?php esc_html_e( 'To add the contact form to your page please add the text', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
                <code>[cscf-contact-form]</code> <?php esc_html_e( 'to your post or page.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>
            </p>
            <h3><?php esc_html_e( 'Support the developer', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></h3>
            <p>
                <?php do_action( 'ffpl_ad_display' ); ?>
            </p>

            <form method="post" action="options.php">
				<?php
				submit_button();
				/* This prints out all hidden setting fields*/
				settings_fields( 'test_option_group' );
				do_settings_sections( 'contact-form-settings' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function page_init() {

		add_settings_section( 'section_message', '<h3>' . esc_html__( 'Message Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>', array(
			$this,
			'print_section_info_message',
		), 'contact-form-settings', array( 'after_section' => $this->anti_spam_notice() ) );
		add_settings_field( 'recipient_emails', esc_html__( 'Recipient Emails :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'recipient_emails',
		) );
		add_settings_field( 'confirm-email', esc_html__( 'Confirm Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'confirm-email',
		) );
		add_settings_field( 'email-sender', esc_html__( 'Allow users to email themselves a copy :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'email-sender',
		) );
		add_settings_field( 'contact-consent', esc_html__( 'Add a consent checkbox :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'contact-consent',
		) );
		add_settings_field( 'contact-consent-msg', esc_html__( 'Consent message :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'contact-consent-msg',
		) );
		add_settings_field( 'phone-number', esc_html__( 'Add a phone number field :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'phone-number',
		) );
		add_settings_field( 'phone-number-mandatory', esc_html__( 'Phone number is mandatory :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'phone-number-mandatory',
		) );
		add_settings_field( 'override-from', esc_html__( 'Override \'From\' Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'override-from',
		) );
		add_settings_field( 'from-email', esc_html__( '\'From\' Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'from-email',
		) );
		add_settings_field( 'subject', esc_html__( 'Email Subject :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'subject',
		) );
		add_settings_field( 'message', esc_html__( 'Message :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'message',
		) );
		add_settings_field( 'sent_message_heading', esc_html__( 'Message Sent Heading :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'sent_message_heading',
		) );
		add_settings_field( 'sent_message_body', esc_html__( 'Message Sent Content :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_message', array(
			'sent_message_body',
		) );
		add_settings_section( 'section_styling', '<h3>' . esc_html__( 'Styling and Validation', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>', array(
			$this,
			'print_section_info_styling',
		), 'contact-form-settings' );
		add_settings_field( 'load_stylesheet', esc_html__( 'Use the plugin default stylesheet (un-tick to use your theme style sheet instead) :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_styling', array(
			'load_stylesheet',
		) );
		add_settings_field( 'use_client_validation', esc_html__( 'Use client side validation (AJAX) :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_styling', array(
			'use_client_validation',
		) );
		add_settings_section(
			'section_recaptcha',
			'<h3 class="expandable-heading">' . esc_html__( 'ReCAPTCHA Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>',
			array(
				$this,
				'print_section_info_recaptcha',
			),
			'contact-form-settings',
		);
		register_setting( 'test_option_group', CSCF_OPTIONS_KEY, array(
			$this,
			'check_form',
		) );
		add_settings_field( 'use_recaptcha', esc_html__( 'Use reCAPTCHA :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_recaptcha', array(
			'use_recaptcha',
			'class' => 'recaptcha-field',
		) );
		add_settings_field( 'theme', esc_html__( 'reCAPTCHA Theme :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_recaptcha', array(
			'theme',
			'class' => 'recaptcha-field',
		) );
		add_settings_field( 'recaptcha_public_key', esc_html__( 'reCAPTCHA Public Key :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_recaptcha', array(
			'recaptcha_public_key',
			'class' => 'recaptcha-field',
		) );
		add_settings_field( 'recaptcha_private_key', esc_html__( 'reCAPTCHA Private Key :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_recaptcha', array(
			'recaptcha_private_key',
			'class' => 'recaptcha-field',
		) );
		
		// REST API Settings
		add_settings_section(
			'section_rest_api',
			'<h3>' . esc_html__( 'REST API Settings', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</h3>',
			array(
				$this,
				'print_section_info_rest_api',
			),
			'contact-form-settings'
		);
		add_settings_field( 'enable_rest_api', esc_html__( 'Enable REST API :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_rest_api', array(
			'enable_rest_api',
		) );
		add_settings_field( 'rest_api_capability', esc_html__( 'Required User Capability :', 'clean-and-simple-contact-form-by-meg-nicholas' ), array(
			$this,
			'create_fields',
		), 'contact-form-settings', 'section_rest_api', array(
			'rest_api_capability',
		) );
	}

	public function anti_spam_notice() {
		global $fwantispam_fs;
		$output = '<h3>Anti Spam</h3>';
		if ( null !== $fwantispam_fs && $fwantispam_fs->can_use_premium_code() ) {
			$output .= '<p>' . esc_html__( 'Congratulations: you are protected by Fullworks Anti Spam', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p>' . esc_html__( 'Configure', 'clean-and-simple-contact-form-by-meg-nicholas' ) . ' <a href="' . esc_url( admin_url( 'options-general.php?page=fullworks-anti-spam-settings' ) ) . '">' . esc_html__( 'Anti Spam Settings here', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</a></p>';
		} else {
			$output .= '<p>' . esc_html__( 'The best way to show your appreciation for this free plugin and keep it maintained is to support it by installing Fullworks Anti Spam Pro', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p>' . esc_html__( 'With a 14 day free trial, you will find it surprisingly affordable when you compare it with Akismet, yet extremely effective.', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p><a href="https://fullworksplugins.com/products/anti-spam/?mtm_campaign=clean-and-simple-contact-form"><p>' . esc_html__( 'Try Fullworks Anti Spam Pro now and support the maintenance this FREE contact form plugin', 'clean-and-simple-contact-form-by-meg-nicholas' ) .
                       ' <img src="'.CSCF_PLUGIN_URL.'/images/external_link.svg" alt="external link"></p></a></p>
<p><img src="'.CSCF_PLUGIN_URL.'/images/upsell_banner.svg" alt="Anti Spam banner"></a></p>';
			$output .= '<p><a href="https://fullworksplugins.com/products/anti-spam/?mtm_campaign=clean-and-simple-contact-form"><p>' . esc_html__( 'Try Fullworks Anti Spam Pro now and support the maintenance this FREE contact form plugin', 'clean-and-simple-contact-form-by-meg-nicholas' ) .
			           ' <img src="'.CSCF_PLUGIN_URL.'/images/external_link.svg" alt="external link">
</p></a></p>';

		}
		$output .= '<h3>Message Logging</h3>';
		if ( null !== $fwantispam_fs && $fwantispam_fs->can_use_premium_code() ) {
			$output .= '<p>' . esc_html__( 'Message logging is enabled by  Fullworks Anti Spam Pro', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p> <a href="' . esc_url( admin_url( 'options-general.php?page=fullworks-anti-spam-settings' ) ) . '">' .
                       esc_html__( 'View Logs here', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</a></p>';
		} else {
			$output .= '<p>' . esc_html__( 'Enable message log by installing Fullworks Anti Spam Pro', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p>' . esc_html__( 'With a 14 day free trial, will automatically log all messages from this form.', 'clean-and-simple-contact-form-by-meg-nicholas' ) . '</p>';
			$output .= '<p><a href="https://fullworksplugins.com/products/anti-spam/?mtm_campaign=clean-and-simple-contact-form"><p>' . esc_html__( 'Enable logs now', 'clean-and-simple-contact-form-by-meg-nicholas' ) .
			           ' <img src="' . CSCF_PLUGIN_URL . '/images/external_link.svg" alt="external link"></p></a></p>';
		}

		return $output;

	}

	public function check_form( $input ) {

		//recaptcha theme
		if ( isset( $input['theme'] ) ) {
			$input['theme'] = sanitize_text_field( $input['theme'] );
		}

		//recaptcha_public_key
		if ( isset( $input['recaptcha_public_key'] ) ) {
			$input['recaptcha_public_key'] = sanitize_text_field( $input['recaptcha_public_key'] );
		}

		//recaptcha_private_key
		if ( isset( $input['recaptcha_private_key'] ) ) {
			$input['recaptcha_private_key'] = sanitize_text_field( $input['recaptcha_private_key'] );
		}

		//sent_message_heading
		$input['sent_message_heading'] = sanitize_text_field( $input['sent_message_heading'] );

		//sent_message_body
		$input['sent_message_body'] = sanitize_text_field( $input['sent_message_body'] );

		//message
		$input['message'] = sanitize_textarea_field( $input['message'] );


		//consent message
		$input['contact-consent-msg'] = sanitize_text_field( $input['contact-consent-msg'] );

		//recipient_emails
		foreach ( $input['recipient_emails'] as $key => $recipient ) {
			if ( ! filter_var( $input['recipient_emails'][ $key ] ) ) {
				unset( $input['recipient_emails'][ $key ] );
			} else {
				$input['recipient_emails'][ $key ] = sanitize_email( $input['recipient_emails'][ $key ] );
			}
		}

		//from
		if ( ! filter_var( $input['from-email'], FILTER_VALIDATE_EMAIL ) ) {
			unset( $input['from-email'] );
		} else {
			$input['from-email'] = sanitize_email( $input['from-email'] );
		}

		//subject
		$input['subject'] = trim( sanitize_text_field( $input['subject'] ) );
		if ( empty( $input['subject'] ) ) {
			unset( $input['subject'] );
		}
		if ( check_admin_referer('test_option_group-options', '_wpnonce')) {
			// The nonce check has passed, safe to process

			if ( isset( $_POST['add_recipient'] ) ) {
				$input['recipient_emails'][] = "";
			}
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- No action, array and not stored
			if ( isset( $_POST['remove_recipient'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- No action, array and not stored
                foreach ( $_POST['remove_recipient'] as $key => $element ) {
					unset( $input['recipient_emails'][ $key ] );
				}
			}
		}

		// REST API settings
		if ( isset( $input['rest_api_capability'] ) ) {
			$input['rest_api_capability'] = sanitize_text_field( $input['rest_api_capability'] );
			// Validate capability exists
			if ( empty( $input['rest_api_capability'] ) ) {
				$input['rest_api_capability'] = 'edit_posts';
			}
		}

		//tidy up the keys
		$tidiedRecipients = array();
		foreach ( $input['recipient_emails'] as $recipient ) {
			$tidiedRecipients[] = $recipient;
		}
		$input['recipient_emails'] = $tidiedRecipients;


		return $input;
	}

	public function print_section_info_recaptcha() {
		echo '<div class="recaptcha-field">';
		print esc_html__( 'Enter your reCAPTCHA settings below :', 'clean-and-simple-contact-form-by-meg-nicholas' );
		print "<p>" . esc_html__( 'To use reCAPTCHA you must get an API key from', 'clean-and-simple-contact-form-by-meg-nicholas' ) . " <a target='_blank' href='" . esc_url(csf_RecaptchaV2::$signUpUrl ) . "'>Google reCAPTCHA</a></p>";
		echo '</div>';
	}

	public function print_section_info_message() {
		print esc_html__( 'Enter your message settings below :', 'clean-and-simple-contact-form-by-meg-nicholas' );
	}

	public function print_section_info_styling() {

		//print 'Enter your styling settings below:';

	}

	public function print_section_info_rest_api() {
		echo '<p>';
		print esc_html__( 'Enable REST API support for headless WordPress implementations.', 'clean-and-simple-contact-form-by-meg-nicholas' );
		echo '</p><p>';
		print esc_html__( 'When enabled, authenticated users can submit the form via: POST /wp-json/cscf/v1/submit', 'clean-and-simple-contact-form-by-meg-nicholas' );
		echo '</p>';
	}

	public function create_fields(
		$args
	) {
		$fieldname        = $args[0];

		switch ( $fieldname ) {
			case 'use_recaptcha':
				$checked = cscf_PluginSettings::UseRecaptcha() === true ? 'checked' : '';
				?><label for="use_recaptcha" class="screen-reader-text">
				<?php esc_html_e('use recaptcha', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
            </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="use_recaptcha"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[use_recaptcha]"><?php
				break;
			case 'load_stylesheet':
				$checked = cscf_PluginSettings::LoadStyleSheet() === true ? 'checked' : '';
				?><label for="load_stylesheet" class="screen-reader-text">
				<?php esc_html_e('load stylesheet', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="load_stylesheet"
                         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[load_stylesheet]"><?php
				break;
			case 'recaptcha_public_key':
				$disabled = cscf_PluginSettings::UseRecaptcha() === false ? 'readonly' : '';
				?><label for="recaptcha_public_key" class="screen-reader-text">
				<?php esc_html_e('recaptcha public key', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input <?php echo esc_attr( $disabled ); ?> type="text" size="60" id="recaptcha_public_key"
                                                              name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recaptcha_public_key]"
                                                              value="<?php echo esc_attr( cscf_PluginSettings::PublicKey() ); ?>" /><?php
				break;
			case 'recaptcha_private_key':
				$disabled = cscf_PluginSettings::UseRecaptcha() === false ? 'readonly' : '';
				?><label for="recaptcha_private_key" class="screen-reader-text">
				<?php esc_html_e('recaptcha private key', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input <?php echo esc_attr( $disabled ); ?> type="text" size="60" id="recaptcha_private_key"
                                                              name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recaptcha_private_key]"
                                                              value="<?php echo esc_attr( cscf_PluginSettings::PrivateKey() ); ?>" /><?php
				break;
			case 'recipient_emails':
				?>
                <ul id="recipients"><?php
				foreach ( cscf_PluginSettings::RecipientEmails() as $key => $recipientEmail ) {
					?>
                    <li class="recipient_email" data-element="<?php echo esc_attr( $key ); ?>">
                        <label for="[recipient_emails][<?php echo esc_attr( $key ) ?>]" class="screen-reader-text">
		                    <?php esc_html_e('recipient email', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                        </label>
                        <input class="enter_recipient" type="email" size="50"
                               id="[recipient_emails][<?php echo esc_attr( $key ) ?>]"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[recipient_emails][<?php echo esc_attr( $key ) ?>]"
                               value="<?php echo esc_attr( $recipientEmail ); ?>"/>
                        <label for="[add_recipient_emails][<?php echo esc_attr( $key ) ?>]" class="screen-reader-text">
		                    <?php esc_html_e('add button for new recipient email', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                        </label>
                        <input id="[add_recipient_emails][<?php echo esc_attr( $key ) ?>]"
                               class="add_recipient" title="Add New Recipient" type="submit" name="add_recipient"
                               value="+">
                        <label for="[remove_recipient_emails][<?php echo esc_attr( $key ) ?>]" class="screen-reader-text">
		                    <?php esc_html_e('remove button for new recipient email', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                        </label>
                        <input id="[remove_recipient_emails][<?php echo esc_attr( $key ) ?>]"
                               class="remove_recipient" title="Remove This Recipient" type="submit"
                               name="remove_recipient[<?php echo esc_attr( $key ); ?>]" value="-">
                    </li>

					<?php
				}
				?></ul><?php
				break;
			case 'confirm-email':
				$checked = cscf_PluginSettings::ConfirmEmail() == true ? "checked" : "";
				?><label for="confirm-email" class="screen-reader-text">
				<?php esc_html_e('confirm email', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="confirm-email"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[confirm-email]"><?php
				break;
			case 'override-from':
				$checked = cscf_PluginSettings::OverrideFrom() == true ? "checked" : "";
				?><label for="override-from" class="screen-reader-text">
				<?php esc_html_e('override from address', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="override-from"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[override-from]"><?php
				break;
			case 'email-sender':
				$checked = cscf_PluginSettings::EmailToSender() == true ? "checked" : "";
				?><label for="email-sender" class="screen-reader-text">
				<?php esc_html_e('email to sender', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="email-sender"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[email-sender]"><?php
				break;
			case 'contact-consent':
				$checked = cscf_PluginSettings::ContactConsent() == true ? "checked" : "";
				?><label for="contact-consent" class="screen-reader-text">
				<?php esc_html_e('contact consent', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="contact-consent"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[contact-consent]"><?php
				break;
			case 'contact-consent-msg':
				?><label for="contact-consent-msg" class="screen-reader-text">
				<?php esc_html_e('contact consent message', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="text" size="60" id="contact-consent-msg"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[contact-consent-msg]"
                               value="<?php echo esc_attr( cscf_PluginSettings::ContactConsentMsg() ); ?>"><?php
				break;
			case 'phone-number':
				$checked = cscf_PluginSettings::PhoneNumber() == true ? "checked" : "";
				?><label for="phone-number" class="screen-reader-text">
				<?php esc_html_e('phone number', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="phone-number"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[phone-number]"><?php
				break;
			case 'phone-number-mandatory':
				$checked = cscf_PluginSettings::PhoneNumberMandatory() == true ? "checked" : "";
				?><label for="phone-number-mandatory" class="screen-reader-text">
				<?php esc_html_e('phone number mandatory', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="phone-number-mandatory"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[phone-number-mandatory]"><?php
				break;
			case 'from-email':
				$disabled = cscf_PluginSettings::OverrideFrom() === false ? "readonly" : "";
				?><label for="from-email" class="screen-reader-text">
				<?php esc_html_e('from email address', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input <?php echo esc_attr( $disabled ); ?> type="text" size="60" id="from-email"
                                                                    name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[from-email]"
                                                                    value="<?php echo esc_attr( cscf_PluginSettings::FromEmail() ); ?>" /><?php
				break;
			case 'subject':
				?><label for="subject" class="screen-reader-text">
				<?php esc_html_e('email subject', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="text" size="60" id="subject" name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[subject]"
                               value="<?php echo esc_attr( cscf_PluginSettings::Subject() ); ?>" /><?php
				break;
			case 'sent_message_heading':
				?><label for="sent_message_heading" class="screen-reader-text">
				<?php esc_html_e('sent message heading', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="text" size="60" id="sent_message_heading"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[sent_message_heading]"
                               value="<?php echo esc_attr( cscf_PluginSettings::SentMessageHeading() ); ?>" /><?php
				break;
			case 'sent_message_body':
				?><label for="sent_message_body" class="screen-reader-text">
				<?php esc_html_e('sent message body', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><textarea id="sent_message_body" cols="63" rows="8"
                                  name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[sent_message_body]"><?php echo esc_attr( cscf_PluginSettings::SentMessageBody() ); ?></textarea><?php
				break;
			case 'message':
				?><label for="message" class="screen-reader-text">
				<?php esc_html_e('message', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><textarea id="message" cols="63" rows="8"
                                  name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[message]"><?php echo esc_attr( cscf_PluginSettings::Message() ); ?></textarea><?php
				break;
			case 'theme':
				$theme = cscf_PluginSettings::Theme();
				$disabled = cscf_PluginSettings::UseRecaptcha() == false ? "disabled" : "";
				?><label for="theme" class="screen-reader-text">
				<?php esc_html_e('recaptcha theme', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label>
                <select <?php echo esc_attr( $disabled ); ?> id="theme"
                                                             name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[theme]">
                    <option <?php echo ( 'light' == $theme ) ? 'selected' : ''; ?>
                            value="light"><?php esc_html_e( 'Light', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></option>
                    <option <?php echo ( 'dark' == $theme ) ? 'selected' : ''; ?>
                            value="dark"><?php esc_html_e( 'Dark', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></option>
                </select>
				<?php
				break;
			case 'use_client_validation':
				$checked = cscf_PluginSettings::UseClientValidation() == true ? "checked" : "";
				?><label for="use_client_validation" class="screen-reader-text">
				<?php esc_html_e('use client validation', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="use_client_validation"
                               name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[use_client_validation]"><?php
				break;
			case 'enable_rest_api':
				$checked = cscf_PluginSettings::RestApiEnabled() === true ? 'checked' : '';
				?><label for="enable_rest_api" class="screen-reader-text">
				<?php esc_html_e('enable REST API', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
				</label><input type="checkbox" <?php echo esc_attr( $checked ); ?>  id="enable_rest_api"
				         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[enable_rest_api]"><?php
				break;
			case 'rest_api_capability':
				$capability = cscf_PluginSettings::RestApiCapability();
				?><label for="rest_api_capability" class="screen-reader-text">
				<?php esc_html_e('REST API required capability', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
				</label><input type="text" size="40" id="rest_api_capability"
				         name="<?php echo esc_attr( CSCF_OPTIONS_KEY ); ?>[rest_api_capability]"
				         value="<?php echo esc_attr( $capability ); ?>" />
				<p class="description"><?php esc_html_e( 'Default: edit_posts. Common capabilities: edit_posts, publish_posts, manage_options', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></p><?php
				break;
			default:
				break;
		}
	}
}
