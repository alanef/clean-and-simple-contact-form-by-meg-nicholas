<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @package Clean and Simple Contact Form
 */

/*
Plugin Name: Contact Form Clean and Simple
Plugin URI: https://fullworks.net/products/clean-and-simple-contact-form
Description: A clean and simple contact form with Google reCAPTCHA and Twitter Bootstrap markup.
Version: 4.12
Requires at least: 5.6
Requires PHP: 7.4
Author: Alan Fuller
Author URI: https://fullworks.net
License: GPLv2 or later
Text Domain: clean-and-simple-contact-form-by-meg-nicholas
Domain Path: /languages
*/

/*
All code up to version 4.7.1 is attributed to
Author: Meghan Nicholas
Author URI: http://www.megnicholas.com
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/*
 * @package Main
*/
require 'shortcodes/contact-form.php';
require 'class.cscf.php';
require 'class.cscf_pluginsettings.php';
require 'class.cscf_settings.php';
require 'class.cscf_contact.php';
require 'class.view.php';
require 'class.cscf_filters.php';
require 'class.cscf_rest_api.php';
require 'class.cscf_css_classes.php';
require 'ajax.php';
require 'recaptchav2.php';


if ( ! defined( 'CSCF_THEME_DIR' ) ) {
	define( 'CSCF_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template() );
}

if ( ! defined( 'CSCF_PLUGIN_NAME' ) ) {
	define( 'CSCF_PLUGIN_NAME', 'clean-and-simple-contact-form-by-meg-nicholas' );
}

if ( ! defined( 'CSCF_PLUGIN_DIR' ) ) {
	define( 'CSCF_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CSCF_PLUGIN_NAME );
}

if ( ! defined( 'CSCF_PLUGIN_URL' ) ) {
	define( 'CSCF_PLUGIN_URL', WP_PLUGIN_URL . '/' . CSCF_PLUGIN_NAME );
}

if ( ! defined( 'CSCF_VERSION_KEY' ) ) {
	define( 'CSCF_VERSION_KEY', 'cscf_version' );
}

if ( ! defined( 'CSCF_VERSION_NUM' ) ) {
	define( 'CSCF_VERSION_NUM', '4.12' );
}

if ( ! defined( 'CSCF_OPTIONS_KEY' ) ) {
	define( 'CSCF_OPTIONS_KEY', 'cscf_options' );
}


require_once CSCF_PLUGIN_DIR . '/vendor/autoload.php';
new \Fullworks_Free_Plugin_Lib\Main('clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php',
	admin_url( 'options-general.php?page=contact-form-settings' ),
	'CSCF',
	'settings_page_contact-form-settings',
	'Clean and Simple Contact Form',);

$cscf = new cscf();
$cscf_rest_api = new cscf_rest_api();

// Configure SMTP if constants are defined
if ( defined( 'CSCF_USE_SMTP' ) && CSCF_USE_SMTP ) {
	add_action( 'phpmailer_init', function( $phpmailer ) {
		$phpmailer->isSMTP();
		
		// Required settings
		if ( defined( 'CSCF_SMTP_HOST' ) ) {
			$phpmailer->Host = CSCF_SMTP_HOST;
		}
		
		if ( defined( 'CSCF_SMTP_PORT' ) ) {
			$phpmailer->Port = (int) CSCF_SMTP_PORT;
		}
		
		// Authentication settings
		if ( defined( 'CSCF_SMTP_AUTH' ) && CSCF_SMTP_AUTH ) {
			$phpmailer->SMTPAuth = true;
			
			if ( defined( 'CSCF_SMTP_USER' ) ) {
				$phpmailer->Username = CSCF_SMTP_USER;
			}
			
			if ( defined( 'CSCF_SMTP_PASS' ) ) {
				$phpmailer->Password = CSCF_SMTP_PASS;
			}
		} else {
			$phpmailer->SMTPAuth = false;
		}
		
		// Security settings
		if ( defined( 'CSCF_SMTP_SECURE' ) ) {
			$phpmailer->SMTPSecure = CSCF_SMTP_SECURE; // 'tls' or 'ssl' or empty string
		}
		
		// Optional: From email override
		if ( defined( 'CSCF_SMTP_FROM' ) ) {
			$phpmailer->From = CSCF_SMTP_FROM;
		}
		
		// Optional: From name override
		if ( defined( 'CSCF_SMTP_FROM_NAME' ) ) {
			$phpmailer->FromName = CSCF_SMTP_FROM_NAME;
		}
		
		// Debug mode
		if ( defined( 'CSCF_SMTP_DEBUG' ) && CSCF_SMTP_DEBUG ) {
			$phpmailer->SMTPDebug = 2;
		}
	});
}

/*get the current version and update options to the new option*/
$cscf_old_version = get_option( CSCF_VERSION_KEY );
update_option( CSCF_VERSION_KEY, CSCF_VERSION_NUM );

/*If this is a new installation then set some defaults*/
if ( false == $cscf_old_version ) {
	$cscf_options                          = get_option( CSCF_OPTIONS_KEY );
	$cscf_options['use_client_validation'] = true;
	$cscf_options['load_stylesheet']       = true;
	$cscf_options['confirm-email']         = true;
	update_option( CSCF_OPTIONS_KEY, $cscf_options );
}

/*if necessary do an upgrade*/
if ( $cscf_old_version < CSCF_VERSION_NUM ) {
	$cscf->Upgrade( $cscf_old_version );
}
