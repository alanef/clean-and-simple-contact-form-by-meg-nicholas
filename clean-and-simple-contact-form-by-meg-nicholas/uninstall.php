<?php

//if uninstall not called from WordPress exit

if (!defined('WP_UNINSTALL_PLUGIN')) exit();
delete_option('cff_options');
delete_option('cff_version');
delete_option('cscf_options');
delete_option('cscf_version');

// The plugin is not loaded during uninstall, so load the library for its own cleanup
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
\Fullworks_Free_Plugin_Lib\Main::plugin_uninstall('CSCF');
