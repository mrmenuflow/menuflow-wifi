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
	$site_id = $_SESSION['profile']['wifi']['site_id'];
	$duration = 240; // mins to allow
	$controller_ver = '8.1.113';
	
	$unifi_connection = new UniFi_API\Client($controller_usr, $controller_psw, $controller_url, $site_id, $controller_ver, false);
	$set_debug_mode = $unifi_connection->set_debug(false);
	$loginresults   = $unifi_connection->login();
	$auth_result = $unifi_connection->authorize_guest($_SESSION['profile']['device']['mac'], $duration, 200000, 200000, null, $_SESSION['profile']['wifi']['ap_mac']);
	
	echo json_encode($auth_result, JSON_PRETTY_PRINT);
}

// mist authentication
else if ($_SESSION['profile']['wifi']['vendor'] == 'mist') {
	// build required dataset
	$secret = $_SESSION['profile']['wifi']['auth_key']; 
	$authorize_min = 1440;  // 7 days Duration (in minutes) 
	$download_kbps = 0;  		 // Download limit (in kbps) per client. 
	$upload_kbps = 0;  			 // Upload limit (in kbps) per client. 
	$quota_mbytes = 0;  		 // Quota (in mbytes) per client. (0 = unlimited)
	$context = sprintf('%s/%s/%s/%d/%d/%d/%d',
		$_SESSION['profile']['wifi']['site_id'], $_SESSION['profile']['wifi']['ap_mac'], $_SESSION['profile']['device']['mac'],
		$authorize_min,
		$download_kbps, $upload_kbps, $quota_mbytes
	);
	$token = urlencode(base64_encode($context));
	$extra = '&forward=' . urlencode('https://google.com'); // URL the user is forwarded to after authorisation		
	$expires = time() + 120;  // The time until which the authorisation URL is valid
	$payload = sprintf('expires=%d&token=%s%s', $expires, $token, $extra);
	$signature = urlencode(base64_encode(hash_hmac('sha1', $payload, $secret, true)));
	
	// build auth url
	$redirect_url = sprintf('http://portal.mist.com/authorize?signature=%s&%s', $signature, $payload);

	// execute url
	$result = file_get_contents($redirect_url);
}