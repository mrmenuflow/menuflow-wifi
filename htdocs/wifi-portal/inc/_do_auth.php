<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// ubiquiti unifi cloud controller authentication
if ($_SESSION['profile']['wifi']['vendor'] == 'ubnt') {
	
	// include unifi class
	require_once('unifi.class.php');
	
	// controller settings
	$controllerurl      = $_SESSION['profile']['wifi']['auth_url'];
	$controlleruser     = $_SESSION['profile']['wifi']['auth_usr'];
	$controllerpassword = $_SESSION['profile']['wifi']['auth_psw'];
	$controllerversion  = '8.1.113';
	$debug = false;
	
	// make connection to unifi controller
	$unifi_connection = new UniFi_API\Client($controlleruser, $controllerpassword, $controllerurl, $_SESSION['profile']['wifi']['vendor_id'], $controllerversion);
	$set_debug_mode   = $unifi_connection->set_debug(true);
	$loginresults     = $unifi_connection->login();
	$duration = 180;
	
	// authenticate guest device 
	$auth_result = $unifi_connection->authorize_guest($_SESSION['profile']['device']['mac'], $duration, '200000', '200000', '', $_SESSION['profile']['wifi']['ap_mac']);
}
	
echo 'AP MAC: '.$_SESSION['profile']['wifi']['ap_mac'].'<hr>';
echo 'USER MAC: '.$_SESSION['profile']['device']['mac'].'<hr>';

var_dump($auth_result);
echo $loginresults;
echo json_encode($auth_result, JSON_PRETTY_PRINT).'<hr>';	
$errors = get_last_results_raw();
var_dump($errors);
