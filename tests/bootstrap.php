<?php
/**
 * PHPUnit bootstrap file for Clean and Simple Contact Form
 */

// Load Composer autoloader
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Load WP_Mock
WP_Mock::bootstrap();

// Define constants that the plugin expects
if ( ! defined( 'CSCF_PLUGIN_NAME' ) ) {
    define( 'CSCF_PLUGIN_NAME', 'clean-and-simple-contact-form-by-meg-nicholas' );
}

if ( ! defined( 'CSCF_PLUGIN_DIR' ) ) {
    define( 'CSCF_PLUGIN_DIR', dirname( __DIR__ ) . '/clean-and-simple-contact-form-by-meg-nicholas' );
}

if ( ! defined( 'CSCF_PLUGIN_URL' ) ) {
    define( 'CSCF_PLUGIN_URL', 'http://example.com/wp-content/plugins/clean-and-simple-contact-form-by-meg-nicholas' );
}

if ( ! defined( 'CSCF_VERSION_KEY' ) ) {
    define( 'CSCF_VERSION_KEY', 'cscf_version' );
}

if ( ! defined( 'CSCF_VERSION_NUM' ) ) {
    define( 'CSCF_VERSION_NUM', '4.11' );
}

if ( ! defined( 'CSCF_OPTIONS_KEY' ) ) {
    define( 'CSCF_OPTIONS_KEY', 'cscf_options' );
}

// Load plugin files that we'll test
require_once CSCF_PLUGIN_DIR . '/class.cscf_pluginsettings.php';
require_once CSCF_PLUGIN_DIR . '/class.cscf_contact.php';