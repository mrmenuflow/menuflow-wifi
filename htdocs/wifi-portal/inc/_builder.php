<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// check if device repeat visitor
if ($_POST['action'] == 'check_device') {
	// do api call
	$data = json_encode(array('mac_addr' => $_SESSION['profile']['device']['mac']));
	$rsp2 = call_api('GET', '/wifi/'.$_SESSION['profile']['venue']['location_id'].'/device', $data);  
	
	// set crm_id to zero if not found
	if ($rsp2['result'][0]['crm_id'] == '') { $rsp2['result'][0]['crm_id'] = 0; }
	
	// update profile
	$_SESSION['profile']['guest']['crm_id'] = $rsp2['result'][0]['crm_id'];
	$_SESSION['profile']['guest']['locale'] = $_POST['locale'];
	$_SESSION['profile']['guest']['name']   = $rsp2['result'][0]['name'];
	$_SESSION['profile']['guest']['email']  = $rsp2['result'][0]['email'];
	$_SESSION['profile']['guest']['subscribed'] = $rsp2['result'][0]['subscribed'];
	$_SESSION['profile']['device']['type']  = $_POST['type'];
	$_SESSION['profile']['device']['ico']   = $_POST['ico'];
}
// process guest
else if ($_POST['action'] == 'guest') {
	$_SESSION['profile']['guest']['name'] = $_POST['name'];
	$_SESSION['profile']['guest']['locale'] = $_POST['locale'];
}
// process guest email
else if ($_POST['action'] == 'guest_email') {
	$_SESSION['profile']['guest']['email'] = $_POST['email'];
}
// process guest opt-in
else if ($_POST['action'] == 'guest_optin') {
	$_SESSION['profile']['guest']['subscribed'] = $_POST['subscribed'];
}

$json = json_encode($_SESSION['profile'], JSON_PRETTY_PRINT);
echo $json;	
