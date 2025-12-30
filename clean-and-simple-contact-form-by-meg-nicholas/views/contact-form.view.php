<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
// Get CSS classes for current framework
$cscf_css = cscf_CSS_Classes::get_all_classes();
$cscf_form_group_class = $cscf_css['form_group'];
$cscf_form_group_error_class = $cscf_css['form_group_error'];
$cscf_input_class = $cscf_css['input'];
$cscf_textarea_class = $cscf_css['textarea'];
$cscf_button_class = $cscf_css['button'];
$cscf_help_text_class = $cscf_css['help_text'];
$cscf_input_group_class = $cscf_css['input_group'];
$cscf_input_addon_class = $cscf_css['input_addon'];
$cscf_text_error_class = $cscf_css['text_error'];
?>
<div id="cscf" class="cscfBlock">
    <div class="cscfMessageSent" style="display:none;">
		<?php echo wp_kses_post( $messageSentView->Render() ); ?>
    </div>
    <div class="cscfMessageNotSent" style="display:none;">
		<?php echo wp_kses_post( $messageNotSentView->Render() ); ?>
    </div>
    <div class="cscfForm">
        <p><?php echo wp_kses_post( $message ); ?></p>

        <form role="form" id="frmCSCF" name="frmCSCF" method="post">
			<?php wp_nonce_field( 'cscf_contact', 'cscf_nonce' ); ?>
            <input type="hidden" name="post-id" value="<?php echo esc_attr( $postID ); ?>">

			<?php if ( isset( $contact->Errors['recaptcha'] ) ) { ?>
                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?>">
                    <p class="<?php echo esc_attr( $cscf_text_error_class ); ?>"><?php echo esc_html( $contact->Errors['recaptcha'] ); ?></p>
                </div>
			<?php } ?>

            <!-- name -->
            <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['name'] ) ) {
				echo ' ' . esc_attr( $cscf_form_group_error_class );
			} ?>">
                <label for="cscf_name"><?php esc_html_e( 'Name:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>


                <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
					<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                        <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-user"></span></span>
					<?php } ?>
                    <input class="<?php echo esc_attr( $cscf_input_class ); ?>"
                           data-rule-required="true"
                           data-msg-required="<?php esc_html_e( 'Please give your name.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                           type="text" id="cscf_name" name="cscf[name]"
                           value="<?php echo esc_attr( $contact->Name ); ?>"
                           placeholder="<?php esc_html_e( 'Your Name', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                    />
                </div>
                <span for="cscf_name" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                      style="display:<?php echo isset( $contact->Errors['name'] ) ? 'block' : 'none'; ?>;">

                    <?php if ( isset( $contact->Errors['name'] ) ) {
	                    echo esc_html( $contact->Errors['name'] );
                    } ?>
                </span>
            </div>


            <!--email address-->
            <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['email'] ) ) {
				echo ' ' . esc_attr( $cscf_form_group_error_class );
			} ?>">
                <label for="cscf_email"><?php esc_html_e( 'Email Address:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>

                <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
					<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                        <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-envelope"></span></span>
					<?php } ?>
                    <input class="<?php echo esc_attr( $cscf_input_class ); ?>"
                           data-rule-required="true"
                           data-rule-email="true"
                           data-msg-required="<?php esc_html_e( 'Please give your email address.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                           data-msg-email="<?php esc_html_e( 'Please enter a valid email address.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                           type="email" id="cscf_email" name="cscf[email]"
                           value="<?php echo esc_attr( $contact->Email ); ?>"
                           placeholder="<?php esc_html_e( 'Your Email Address', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                    />
                </div>
                <span for="cscf_email" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                      style="display:<?php echo isset( $contact->Errors['email'] ) ? 'block' : 'none'; ?>;">
                    <?php if ( isset( $contact->Errors['email'] ) ) {
	                    echo esc_html( $contact->Errors['email'] );
                    } ?>
                </span>
            </div>

			<?php if ( $confirmEmail ) { ?>
                <!--confirm email address -->
                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['confirm-email'] ) ) {
					echo ' ' . esc_attr( $cscf_form_group_error_class );
				} ?>">
                    <label for="cscf_confirm-email"><?php esc_html_e( 'Confirm Email Address:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>
                    <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
						<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                            <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-envelope"></span></span>
						<?php } ?>
                        <input class="<?php echo esc_attr( $cscf_input_class ); ?>"
                               data-rule-required="true"
                               data-rule-email="true"
                               data-rule-equalTo="#cscf_email"
                               data-msg-required="<?php esc_html_e( 'Please enter the same email address again.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                               data-msg-email="<?php esc_html_e( 'Please enter a valid email address.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                               data-msg-equalTo="<?php esc_html_e( 'Please enter the same email address again.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                               type="email" id="cscf_confirm-email" name="cscf[confirm-email]"
                               value="<?php echo esc_attr( $contact->ConfirmEmail ); ?>"
                               placeholder="<?php esc_html_e( 'Confirm Your Email Address', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                        />
                    </div>
                    <span for="cscf_confirm-email" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                          style="display:<?php echo isset( $contact->Errors['confirm-email'] ) ? 'block' : 'none'; ?>;">
                    <?php if ( isset( $contact->Errors['confirm-email'] ) ) {
	                    echo esc_attr( $contact->Errors['confirm-email'] );
                    } ?>
                </span>
                </div>
			<?php } ?>

			<?php if ( cscf_PluginSettings::PhoneNumber() ) { ?>
                <!-- telephone number -->
                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['phone-number'] ) ) {
					echo ' ' . esc_attr( $cscf_form_group_error_class );
				} ?>">
                    <label for="cscf_phone-number"><?php esc_html_e( 'Phone Number:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>
                    <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
						<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                            <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-phone-alt"></span></span>
						<?php } ?>
                        <input class="<?php echo esc_attr( $cscf_input_class ); ?>"
                               data-rule-required="<?php echo ( true === cscf_PluginSettings::PhoneNumberMandatory() ) ? 'true' : 'false'; ?>"
							   data-msg-required="<?php esc_html_e( 'Please give your phone number.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
							   type="text" id="cscf_phone-number" name="cscf[phone-number]"
                               value="<?php echo esc_attr( $contact->PhoneNumber ); ?>"
                               placeholder="<?php esc_html_e( 'Your Phone Number', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                        />
                    </div>
                    <span for="cscf_phone-number" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                          style="display:<?php echo isset( $contact->Errors['phone-number'] ) ? 'block' : 'none'; ?>;">
                    <?php if ( isset( $contact->Errors['phone-number'] ) ) {
	                    echo esc_attr( $contact->Errors['phone-number'] );
                    } ?>
                </span>
                </div>
			<?php } ?>


            <!-- message -->
            <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['message'] ) ) {
				echo ' ' . esc_attr( $cscf_form_group_error_class );
			} ?>">
                <label for="cscf_message"><?php esc_html_e( 'Message:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>
                <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
					<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                        <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-comment"></span></span>
					<?php } ?>
                    <textarea class="<?php echo esc_attr( $cscf_textarea_class ); ?>"
                              data-rule-required="true"
                              data-msg-required="<?php esc_html_e( 'Please give a message.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
                              id="cscf_message" name="cscf[message]" rows="10"
                              placeholder="<?php esc_html_e( 'Your Message', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"><?php echo esc_textarea( $contact->Message??'' ); ?></textarea>
                </div>

                <span for="cscf_message" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                      style="display:<?php echo isset( $contact->Errors['message'] ) ? 'block' : 'none'; ?>;">
                    <?php if ( isset( $contact->Errors['message'] ) ) {
	                    echo esc_attr( $contact->Errors['message'] );
                    } ?>
                </span>
            </div>

			<?php if ( cscf_PluginSettings::EmailToSender() ) { ?>
                <!-- email to sender -->
                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['email-sender'] ) ) {
					echo ' ' . esc_attr( $cscf_form_group_error_class );
				} ?>">
                    <label for="cscf_email-sender"><?php esc_html_e( 'Send me a copy:', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?></label>
                    <div class="<?php echo ( true === cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
						<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                            <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-comment"></span></span>
						<?php } ?>
                        <input <?php echo ( true == $contact->EmailToSender ) ? 'checked' : ''; ?> type="checkbox"
                                                                                                   id="cscf_email-sender"
                                                                                                   name="cscf[email-sender]">
                    </div>
                    <span for="cscf_email-sender" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                          style="display:<?php echo isset( $contact->Errors['email-sender'] ) ? 'block' : 'none'; ?>;">
                        <?php if ( isset( $contact->Errors['email-sender'] ) ) {
	                        echo esc_attr( $contact->Errors['email-sender'] );
                        } ?>
                    </span>
                </div>
			<?php } ?>



			<?php if ( cscf_PluginSettings::ContactConsent() ) { ?>
                <!-- contact consent -->
                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php if ( isset( $contact->Errors['contact-consent'] ) ) {
					echo ' ' . esc_attr( $cscf_form_group_error_class );
				} ?>">
                    <label for="cscf_contact-consent"><?php echo esc_html( cscf_PluginSettings::ContactConsentMsg() ); ?>:</label>
                    <div class="<?php echo ( cscf_PluginSettings::InputIcons() && ! empty( $cscf_input_group_class ) ) ? esc_attr( $cscf_input_group_class ) : ''; ?>">
						<?php if ( cscf_PluginSettings::InputIcons() == true && ! empty( $cscf_input_addon_class ) ) { ?>
                            <span class="<?php echo esc_attr( $cscf_input_addon_class ); ?>"><span class="glyphicon glyphicon-comment"></span></span>
						<?php } ?>
                        <input class="<?php echo esc_attr( $cscf_input_class ); ?>"
							   data-rule-required="true"
                               data-msg-required="<?php esc_html_e( 'Please give your consent.', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"
							<?php echo ( true === $contact->ContactConsent ) ? 'checked' : ''; ?> type="checkbox"
                               id="cscf_contact-consent"
                               name="cscf[contact-consent]">
                    </div>
                    <span for="cscf[contact-consent]" class="<?php echo esc_attr( $cscf_help_text_class ); ?>"
                          style="display:<?php echo isset( $contact->Errors['contact-consent'] ) ? 'block' : 'none'; ?>;">
                        <?php if ( isset( $contact->Errors['contact-consent'] ) ) {
	                        echo esc_html( $contact->Errors['contact-consent'] );
                        } ?>
                    </span>
                </div>
			<?php } ?>

            <!-- recaptcha -->
			<?php if ( $contact->RecaptchaPublicKey <> '' && $contact->RecaptchaPrivateKey <> '' ) { ?>

                <div class="<?php echo esc_attr( $cscf_form_group_class ); ?><?php
				if ( isset( $contact->Errors['recaptcha'] ) ) {
					echo ' ' . esc_attr( $cscf_form_group_error_class );
				} ?>">
                    <div id="recaptcha_div">
                        <div class="g-recaptcha" data-theme="<?php echo esc_attr( cscf_PluginSettings::Theme() ); ?>"
                             data-sitekey="<?php echo esc_attr( $contact->RecaptchaPublicKey ); ?>"></div>


                        <div for="cscf_recaptcha"
                             class="<?php echo esc_attr( $cscf_help_text_class ); ?>"><?php if ( isset( $contact->Errors['recaptcha'] ) ) {
								echo esc_html( $contact->Errors['recaptcha'] );
							} ?></div>


                        <noscript>
                            <div style="width: 302px; height: 422px;">
                                <div style="width: 302px; height: 422px; position: relative;">
                                    <div style="width: 302px; height: 422px; position: absolute;">
                                        <iframe
                                                src="https://www.google.com/recaptcha/api/fallback?k=<?php echo esc_attr( $contact->RecaptchaPublicKey ); ?>"
                                                frameborder="0" scrolling="no"
                                                style="width: 302px; height:422px; border-style: none;">
                                        </iframe>
                                    </div>
                                    <div style="width: 300px; height: 60px; border-style: none;
                  bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
                  background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
        <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                  class="g-recaptcha-response"
                  style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
                         margin: 10px 25px; padding: 0px; resize: none;">
        </textarea>
                                    </div>
                                </div>
                            </div>
                        </noscript>

                    </div>
                </div>
			<?php } ?>
            <input type="submit" id="cscf_SubmitButton" class="<?php echo esc_attr( $cscf_button_class ); ?>"
                   value="<?php esc_html_e( 'Send Message', 'clean-and-simple-contact-form-by-meg-nicholas' ); ?>"/>
        </form>
    </div>
</div>