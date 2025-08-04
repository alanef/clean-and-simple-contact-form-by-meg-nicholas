<?php
add_action( "wp_ajax_cscf-submitform", "cscfsubmitform" );
add_action( "wp_ajax_nopriv_cscf-submitform", "cscfsubmitform" );

//http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
function cscfsubmitform() {

	$contact        = new cscf_Contact;
	$result['sent'] = false;

	$result['valid']     = $contact->IsValid();
	$result['errorlist'] = $contact->Errors;

	if ( $result['valid'] ) {
		$result['sent'] = $contact->SendMail();
		
		// Action hook for AJAX form submission
		if ( $result['sent'] ) {
			do_action( 'cscf_form_submitted_ajax', $contact );
		}
	}

	header( 'Content-type: application/json' );

	wp_send_json(  $result  );
}