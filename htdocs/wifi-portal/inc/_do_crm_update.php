<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');
	
// if crm_id is zero (possible new guest)
if ($_SESSION['profile']['guest']['crm_id'] == 0) {
	
	// check if we have match for email address
	$check_email = call_api('GET', '/wifi/'.$_SESSION['profile']['guest']['email'].'/crm');
	
	// no email match
	if ($check_email['status'] == 404) {
		$email_match = 0;
		if ($_SESSION['profile']['device']['device_id'] == 0) {
			$device_match = 0;
		}
		else {
			$device_match = 1;
			$device_id = $_SESSION['profile']['device']['device_id'];
		}
	}
	else {
		$email_match = 1;
		$crm_id = $check_email['result'][0]['id'];
	}	
		
	// email or device not found
	if ($email_match == 0 && $device_match == 0) {
		// add wifi crm
		$data_crm = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'email' => $_SESSION['profile']['guest']['email'],'subscribed' => $_SESSION['profile']['guest']['subscribed']));
		$api_crm = call_api('POST', '/wifi/'.$_SESSION['profile']['venue']['serivce_id'].'/crm', $data_crm); 
		
		if ($api_crm['status'] == 201) {
			// get new crm_id
			$crm_id = $api_crm['crm_id'];
			
			// add profile
			$data_profile = json_encode(array('name' => $_SESSION['profile']['guest']['name'],'email' => $_SESSION['profile']['guest']['email'],'locale' => $_SESSION['profile']['guest']['locale'],'dob_day' => $_SESSION['profile']['guest']['dob_day'],'dob_mth' => $_SESSION['profile']['guest']['dob_mth'],'zip' => $_SESSION['profile']['guest']['zip']));
			$api_crm_profile = call_api('POST', '/wifi/'.$crm_id.'/profile', $data_profile); 
			
			// add device
			$data_device = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'mac_address' => $_SESSION['profile']['device']['mac'],'device_type' => $_SESSION['profile']['device']['type'],'icon' => $_SESSION['profile']['device']['ico']));
			$api_crm_device = call_api('POST', '/wifi/'.$crm_id.'/device', $data_device); 
			
			// add visit
			$data_visit = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'device_id' => $api_crm_device['device_id'],'duration' => 0));
			$api_crm_visit = call_api('POST', '/wifi/'.$crm_id.'/visit', $data_visit); 
		}		
	} 
	// profile found but new device
	else if ($email_match == 1 && $device_match == 0) {
		// update wifi_crm
		$data_crm_upd = json_encode(array('subscribed' => $_SESSION['profile']['guest']['subscribed']));
		$api_upd_crm = call_api('PUT', '/wifi/'.$crm_id.'/crm', $data_crm_upd); 

		// add device
		$data_device = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'mac_address' => $_SESSION['profile']['device']['mac'],'device_type' => $_SESSION['profile']['device']['type'],'icon' => $_SESSION['profile']['device']['ico']));
		$api_crm_device = call_api('POST', '/wifi/'.$crm_id.'/device', $data_device); 
		
		// add visit
		$data_visit = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'device_id' => $api_crm_device['device_id'],'duration' => 0));
		$api_crm_visit = call_api('POST', '/wifi/'.$crm_id.'/visit', $data_visit); 
	}	
}

// this is a returning guest (has crm_id) so just update visit data
else {
	// update wifi_crm
	$data_crm_upd = json_encode(array('subscribed' => $_SESSION['profile']['guest']['subscribed']));
	$api_upd_crm = call_api('PUT', '/wifi/'.$_SESSION['profile']['guest']['crm_id'].'/crm', $data_crm_upd); 
	
	// add visit
	$data_visit_upd = json_encode(array('location_id' => $_SESSION['profile']['venue']['location_id'],'device_id' => $_SESSION['profile']['device']['device_id'],'duration' => 0));
	$api_crm_visit = call_api('POST', '/wifi/'.$_SESSION['profile']['guest']['crm_id'].'/visit', $data_visit_upd); 
}
