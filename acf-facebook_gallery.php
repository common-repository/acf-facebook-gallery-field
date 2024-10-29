<?php

/*
Plugin Name: Advanced Custom Fields: Facebook gallery
Plugin URI: https://www.facebook.com/galleryfield
Description: ACF add-on which provides facebook gallery field
Version: 1.0.0
Author: Turn
Author URI: http://turn.lv/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




// 1. set text domain
// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain( 'acf-facebook_gallery', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );




// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_facebook_gallery( $version ) {
	
	include_once('inc/shared.php');
	include_once('acf-facebook_gallery-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_facebook_gallery');




// 3. Include field type for ACF4
function register_fields_facebook_gallery() {

	include_once('inc/shared.php');
	include_once('acf-facebook_gallery-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_facebook_gallery');



	
?>