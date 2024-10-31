<?php
defined('ABSPATH') && defined('WPINC') || die;

require_once dirname(__FILE__) . '/admin-page-class/admin-page-class.php';
require_once dirname(__FILE__) . '/shared_variables.php';
require_once dirname(__FILE__) . '/admin_functions.php';

global $pixter_me_admin_tools;
$pixter_me_admin_tools = new p1xtr_pixter_me_admin_tools('pixter_me');
$pixter_me_user = get_option('pixter_me_user');

//if (empty($pixter_me_user)) {
add_action('wp_ajax_register_pixter_me_user', 'register_pixter_me_user');

function register_pixter_me_user()
{
    $isGuest = false;
    global $pixter_me_admin_tools;
    $pixter_me_admin_tools->register_user($isGuest);
    //p1xtr_pixter_me_register_user('pixter_me');
}

function pixter_me_admin_page_register()
{
    p1xtr_pixter_me_admin_page_register('pixter_me', 'Pixter.me Wordpress Print Store');
}

add_action('pixter_me_admin_page_class_display_register_page', 'pixter_me_admin_page_register');
//}else{
function pixter_me_admin_before_page()
{
    p1xtr_pixter_me_admin_before_page('pixter_me');
}

add_action('pixter_me_admin_page_class_before_page', 'pixter_me_admin_before_page');


//}
/**
 * configure your options page
 */
$config = array(
    'menu' => array('top' => 'Pixter.me Wordpress Print Store' .' settings'),
    'page_title' => 'Pixter.me Wordpress Print Store',
    'page_header_text' => 'Here you can find configurations to your Pixter.me buttons, please also check your account on <a target="_blank" href="https://publishers.pixter.me/app/">Pixter.me</a> for more details.',
    'capability' => 'install_plugins',
    'option_group' => 'pixter_me' .'_options',
    'id' => 'pixter_me' .'_plugin',
    'fields' => $p1xtr_pixter_me_fields,
    'icon_url' => plugins_url('admin-icon.png', __FILE__),
    'position' => 82,
    'plugin_name' => 'pixter_me',
);
$options_panel = new BF_Admin_Page_Class($config);
