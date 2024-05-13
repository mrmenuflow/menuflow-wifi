<?php 

/**********************
  VENDOR DETECTION
**********************/

// Unifi WAPs
if (isset( $_GET['id']) && isset($_GET['t'] )) {
	// get url data to work out unifi site
	$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
	$dir = $parsedUrl['path'];
	$path = explode('/',  $dir);
	$path = array_filter($path);
	
	// unifi dataset
	$site_id = $path[3];
	$mac_wap = $_GET['ap'];
	$mac_usr = $_GET['id'];
}
// Mist WAPs
else if (isset($_GET['wlan_id'])) {
	// mist dataset 
	$site_id = $_GET['wlan_id'];
	$mac_wap = $_GET['ap_mac'];
	$mac_usr = $_GET['client_mac'];
}

/**********************
	BUILD MASTER CONFIG 
**********************/

// do api call
$get_site = call_api('GET', '/wifi/'.$site_id.'/vendor');  
$ui = $get_site['result'][0];
$connected_at = date('Y-m-d H:i:s');

// build profile array required by WiFi portal
$_SESSION['profile'] = array('venue' => array('serivce_id' => $ui['service_id'], 'location_id'	=> $ui['location_id'], 'location_name' => $ui['location_name'], 'connected_at' => $connected_at), 'wifi' => array('vendor' => $ui['vendor'], 'ssid' => $ui['ssid'], 'site_id' => $site_id, 'ap_mac' => $mac_wap, 'auth_url' => $ui['auth_url'], 'auth_usr' => $ui['auth_usr'], 'auth_psw' => $ui['auth_psw'], 'auth_key' => $ui['auth_key']), 'device' => array('device_id' => 0, 'mac' => $mac_usr, 'type' => '', 'ico' => ''), 'guest' => array('crm_id' => 0, 'name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));
