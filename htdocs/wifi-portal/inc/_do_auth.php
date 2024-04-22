<?php

// start session management 
session_start();

// ubiquiti unifi cloud controller authentication
if ($_SESSION['profile']['wifi']['vendor'] == 'ubnt') {
		
	// include unifi
	require_once 'vendor/autoload.php';
	
	// controller settings
	$controller_url = $_SESSION['profile']['wifi']['auth_url'];
	$controller_usr = $_SESSION['profile']['wifi']['auth_usr'];
	$controller_psw = $_SESSION['profile']['wifi']['auth_psw'];
	$site_id = $_SESSION['profile']['wifi']['vendor_id'];
	$duration = 2000; // mins to allow
	$controller_ver = '8.1.113';
	
	$unifi_connection = new UniFi_API\Client($controller_usr, $controller_psw, $controller_url, $site_id, $controller_ver, false);
	$set_debug_mode = $unifi_connection->set_debug(false);
	$loginresults   = $unifi_connection->login();
	$auth_result = $unifi_connection->authorize_guest($_SESSION['profile']['device']['mac'], $duration, 200000, 200000, null, $_SESSION['profile']['wifi']['ap_mac']);
	
	echo json_encode($auth_result, JSON_PRETTY_PRINT);
}
	