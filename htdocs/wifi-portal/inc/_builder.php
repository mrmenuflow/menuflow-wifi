<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// process device
if ($_POST['action'] == 'device') {
	$_SESSION['profile']['device']['type'] = $_POST['type'];
	$_SESSION['profile']['device']['ico']  = $_POST['ico'];
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
