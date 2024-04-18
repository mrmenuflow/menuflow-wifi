<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// set array
$_SESSION['profile'] = array('venue' => array('serivce_id' => 0, 'location_id'	=> 0, 'connected_at' => ''), 'ap' => array('vendor' => '', 'vendor_id' => '', 'ap_mac' => '', 'auth_url' => '', 'auth_usr' => '', 'auth_psw' => ''), 'device' => array('mac' => '', 'type' => '', 'ico' => ''), 'guest' => array('name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));

$json = json_encode($_SESSION['profile'], JSON_PRETTY_PRINT);

echo '<pre>'.$json.'</pre>';	