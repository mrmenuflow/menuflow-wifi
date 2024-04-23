<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// include pusher php sdk
include_once $_SERVER['DOCUMENT_ROOT'].'/inc/Pusher/autoload.php';

// setup push api
$options = array(
	'cluster' => 'mt1','useTLS' => true
);
$pusher = new Pusher\Pusher(
	'eb15de95aa36b7912593','4ae166c1ffae74c7b775','1792454', $options
);

// check if device repeat visitor
if ($_POST['action'] == 'check_device') {
	// do api call
	$data = json_encode(array('mac_addr' => $_SESSION['profile']['device']['mac']));
	$rsp2 = call_api('GET', '/wifi/'.$_SESSION['profile']['venue']['location_id'].'/device', $data);  
		
	// set crm_id to zero if not found
	if ($rsp2['result'][0]['crm_id'] == '') { 
		$rsp2['result'][0]['crm_id'] = 0;   
	} 
	// device mac already exists 
	if ($rsp2['result'][0]['id'] == '') { 
		$rsp2['result'][0]['id'] = 0;  
	}
	
	// update profile
	$_SESSION['profile']['guest']['crm_id'] = $rsp2['result'][0]['crm_id'];
	$_SESSION['profile']['guest']['locale'] = $_POST['locale'];
	$_SESSION['profile']['guest']['name'] = $rsp2['result'][0]['name'];
	$_SESSION['profile']['guest']['email'] = $rsp2['result'][0]['email'];
	$_SESSION['profile']['guest']['subscribed'] = $rsp2['result'][0]['subscribed'];
	$_SESSION['profile']['device']['type'] = $_POST['type'];
	$_SESSION['profile']['device']['ico'] = $_POST['ico'];
	$_SESSION['profile']['device']['device_id'] = $rsp2['result'][0]['id'];
	
	// send message to pusher
	$channel_id = 'WIFI-'.$_SESSION['profile']['venue']['location_id'];
	$pusher->trigger($channel_id, 'connect', $_SESSION['profile']['device']);
}
// process guest
else if ($_POST['action'] == 'guest') {
	$_SESSION['profile']['guest']['name'] = $_POST['name'];
	
	// send message to pusher
	$channel_id = 'WIFI-'.$_SESSION['profile']['venue']['location_id'];
	$data = array('mac' => $_SESSION['profile']['device']['mac'], 'type' => $_SESSION['profile']['device']['type'], 'name' => $_POST['name']);
	$pusher->trigger($channel_id, 'name', $data);
}
// process guest email
else if ($_POST['action'] == 'guest_email') {
	$_SESSION['profile']['guest']['email'] = $_POST['email'];
	
	// send message to pusher
	$channel_id = 'WIFI-'.$_SESSION['profile']['venue']['location_id'];
	$data = array('mac' => $_SESSION['profile']['device']['mac'], 'type' => $_SESSION['profile']['device']['type'], 'name' => $_SESSION['profile']['guest']['name'], 'email' => $_POST['email']);
	$pusher->trigger($channel_id, 'email', $data);
}
// process guest opt-in
else if ($_POST['action'] == 'guest_optin') {
	$_SESSION['profile']['guest']['subscribed'] = $_POST['subscribed'];
	
	// send message to pusher
	$channel_id = 'WIFI-'.$_SESSION['profile']['venue']['location_id'];
	$data = array('mac' => $_SESSION['profile']['device']['mac'], 'type' => $_SESSION['profile']['device']['type'], 'name' => $_SESSION['profile']['guest']['name'], 'email' => $_SESSION['profile']['guest']['email'], 'subscribed' => $_POST['subscribed']);
	$pusher->trigger($channel_id, 'optin', $data);
}

// process guest authed
else if ($_POST['action'] == 'authed') {	
	// send message to pusher
	$channel_id = 'WIFI-'.$_SESSION['profile']['venue']['location_id'];
	$data = array('mac' => $_SESSION['profile']['device']['mac'], 'type' => $_SESSION['profile']['device']['type'], 'name' => $_SESSION['profile']['guest']['name'], 'email' => $_SESSION['profile']['guest']['email'], 'subscribed' => $_SESSION['profile']['guest']['subscribed']);
	$pusher->trigger($channel_id, 'authed', $data);
}


$json = json_encode($_SESSION['profile'], JSON_PRETTY_PRINT);
echo $json;	
