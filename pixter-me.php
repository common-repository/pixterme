<?php
/*
Plugin Name: Pixter.me Wordpress Print Store
Plugin URI: http://www.pixter-media.com/wordpress
Description: Enable printing of images on accessories directly from your website.
This plugin adds a button on top of images in your website. The button appears on hover only.
Author: Pixter Media
Author URI: https://www.pixter.me
Text Domain: pixter-me
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.12

Copyright 2016 Pixter Media
*/

defined('ABSPATH') && defined('WPINC') || die;


require_once dirname(__FILE__) . '/shared_variables.php';
require_once dirname(__FILE__) . '/admin.php';
require_once dirname(__FILE__) . '/plugin_functions.php';

function plugins_loaded_pixter_me()
{
    p1xtr_pixter_me_plugin_loaded('pixter_me');
}

add_action('plugins_loaded', 'plugins_loaded_pixter_me', 999999);

function pixter_me_activate()
{
    p1xtr_pixter_me_activate('pixter_me');
}

register_activation_hook(__FILE__, 'pixter_me_activate');

function pixter_me_activation_redirect($plugin)
{
    p1xtr_pixter_me_activation_redirect($plugin , 'pixter_me');
}

add_action('activated_plugin', 'pixter_me_activation_redirect');


//	function pixter_me_init()
//	{
//		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
//	//	wp_enqueue_script( 'pixter-me-global', 'http://ddd.rrr.com/x.js', array(), '0.1', true );
//	}
//	add_action( 'init', 'pixter_me_init');

function show_pixter_me()
{
    return p1xtr_pixter_me_show_plugin('pixter_me');
}

function pixter_me_inline_script()
{
    p1xtr_pixter_me_inline_script('pixter_me');
}

add_action('wp_footer', 'pixter_me_inline_script', 99999);


function pixter_me_register_notice()
{
    p1xtr_pixter_me_register_notice('pixter_me', 'Pixter.me Wordpress Print Store');
}

add_action('admin_notices', 'pixter_me_register_notice');


/***
 * Added By Itay 20/9/2016
 */

function pixter_me_toggle_psk_notice()
{
    p1xtr_pixter_me_psk_notice('pixter_me', 'Pixter.me Wordpress Print Store');
}

add_action('admin_notices', 'pixter_me_toggle_psk_notice');


function pixter_me_eventStage($url)
{
    $data = array(
        "storename" => get_bloginfo('name'),
        "website" => get_home_url(),
        "lang" => get_bloginfo('language'),
        "uid" => get_option('p1xtr_uid'),
        "plugin_uid" => get_option('pixter_me_uid'),
        "plugin_ver" => get_option('pixter_me_ver'),
        "plugin_db_ver" => get_option('pixter_me_db_ver'),
        "wp_ver" => get_bloginfo('version'),
        "php_ver" => phpversion(),
    );
    $data_string = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    //execute post
    $result = trim(curl_exec($ch));

    curl_close($ch);

    return $result;

}




function pixter_me_active()
{
    $pixter_me_user = get_option('pixter_me_user');
    global $pixter_me_admin_tools;

    $pixter_me_admin_tools->init_options();

    $apiUrl = P1XTR_API_BASE_URL . "/api/v2/publisher/activate_wp?user=wp&api_key=" . get_option('pixter_me_user') . "&plugin_name=" . "pixter_me";

    pixter_me_eventStage($apiUrl);
    
    if(empty($pixter_me_user)){
        $pixter_me_admin_tools->registerGuestUser();
    }
    
}

register_activation_hook(__FILE__, 'pixter_me_active');


function pixter_me_deactivation()
{
    $apiUrl = P1XTR_API_BASE_URL . "/api/v2/publisher/deactivate_wp?user=wp&api_key=" . get_option('pixter_me_user') . "&plugin_name=" . "pixter_me";

    pixter_me_eventStage($apiUrl);
}

register_deactivation_hook(__FILE__, 'pixter_me_deactivation');
