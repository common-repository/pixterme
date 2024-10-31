<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 19/10/16
 * Time: 09:49
 */

require_once dirname(__FILE__) . '/shared_variables.php';

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$apiKey = get_option('pixter_me_user');
if(empty($apiKey)){
    $apiKey = '';
}
$apiUrl = P1XTR_API_BASE_URL  . "/api/v2/publisher/uninstall_wp?user=wp&api_key=" . $apiKey . "&plugin_name=" . 'pixter_me';

$ch = curl_init();

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
curl_setopt($ch, CURLOPT_URL, $apiUrl);
//execute post
$result = trim(curl_exec($ch));
curl_close($ch);

if (get_option('pixter_me_user')) {
    delete_option('pixter_me_user');
}

if (get_option('pixter_me_v3_user')) {
    delete_option('pixter_me_v3_user');
}

if (get_option('pixter_me_is_guest_user')) {
    delete_option('pixter_me_is_guest_user');
}

