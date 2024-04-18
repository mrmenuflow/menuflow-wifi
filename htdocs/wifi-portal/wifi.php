<?php

// start session management
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// get url data to work out unifi site
$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
$dir = $parsedUrl['path'];
$path = explode('/',  $dir);
$path = array_filter($path);

// unifi dataset
$vendor_id = $path[3];
$mac_wap = $_GET['ap'];
$mac_usr = $_GET['id'];

$vendor_id = 'oqqxft2t';
$mac_wap = 'e0:63:da:26:4a:ff';
$mac_usr = '78:76:89:17:7c:32';
$connected_at = date('Y-m-d H:i:s');

// do api call
$rsp = call_api('GET', '/wifi/'.$vendor_id);  
$ui = $rsp['result'][0];

// build profile array
//if (!isset($_SESSION['profile'])) {
	$_SESSION['profile'] = array('venue' => array('serivce_id' => $ui['service_id'], 'location_id'	=> $ui['location_id'], 'connected_at' => $connected_at), 'ap' => array('vendor' => $ui['vendor'], 'vendor_id' => $vendor_id, 'ap_mac' => $mac_wap, 'auth_url' => $ui['auth_url'], 'auth_usr' => $ui['auth_usr'], 'auth_psw' => $ui['auth_psw']), 'device' => array('mac' => $mac_usr, 'type' => '', 'ico' => ''), 'guest' => array('name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));
//}
?>
<html>
<head>
	<title>Guest WiFi by Menuflow</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">
	<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/bootstrap.5.2.3.min.css">
	<link rel="stylesheet" href="https://use.typekit.net/ngt4nfe.css">
	<link rel="stylesheet" href="inc/_global.css">
	<style>
	body { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: <?php echo $ui['css_page_bg_hex'];?>; } 
	.link { color: <?php echo $ui['css_canvas_link_hex'];?>; }
	.link:hover { color: <?php echo $ui['css_canvas_link_hover_hex'];?>; }
	.launch-screen { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: url('inc/<?php echo $ui['css_page_bg_img'];?>') center center; background-repeat: no-repeat; background-size: cover; }
	.master-button { border-color: <?php echo $ui['css_canvas_btn_hex'];?>; background: <?php echo $ui['css_canvas_btn_hex'];?>; }
	.master-button:hover { border-color: <?php echo $ui['css_canvas_btn_hover_hex'];?>; background: <?php echo $ui['css_canvas_btn_hover_hex'];?>; }
	h2 { color: <?php echo $ui['css_canvas_title_hex'];?>; } 
	.offcanvas, .offcanvas.sm { border: 3px solid <?php echo $ui['css_canvas_brdr_hex'];?>!important; background: <?php echo $ui['css_canvas_bg_hex'];?>; }
	p { color: <?php echo $ui['css_canvas_intro_hex'];?>;}
	</style>
</head>
<body>
<div class="launch-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="privacy" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close_terms float-end">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
				</svg>
				<h2><?php echo $ui['txt_launch_title'];?></h2>
				<p class="mt-3"><?php echo htmlspecialchars_decode($ui['txt_launch_intro']);?></p>
			</div>	
			<div class="w-100 p-2 pt-0 terms_info">
				<p class="mt-3">
					When you visit any website, it may store or retrieve information on your browser, mostly in the form of cookies. 
					This information might be about your preferences or device and is mostly used to make the site work. 
					The information does not usually directly identify you, but it can give you a more personalised menu experience.
					Shown below are the cookies this service uses.
				</p>
			</div>
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="w-100 btn btn-lg mt-3 master-button" data-bs-dismiss="offcanvas" aria-label="Close" id="ok">
				<?php echo $ui['txt_launch_btn'];?>
			</div>  
		</div>
	</div>
</div>

<div class="name-screen">
	<div class="offcanvas sm offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_name" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2><?php echo $ui['txt_name_title'];?></h2>
				<p class="mt-1"><?php echo $ui['txt_name_intro'];?></p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input class="guest_name" type="text" name="guest_name" placeholder="<?php echo $ui['txt_name_ph'];?>">
			<div class="btn btn-lg master-button" data-bs-dismiss="offcanvas" aria-label="Close" id="name_submit" style="margin-top:-7px;">
				<?php echo $ui['txt_name_btn'];?>
			</div>  
		</div>
	</div>
</div>

<div class="email-screen">
	<div class="offcanvas sm offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_email" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2><?php echo $ui['txt_email_title'];?></h2>
				<p class="mt-1"><?php echo $ui['txt_email_intro'];?></p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input class="guest_email" type="email" name="guest_email" placeholder="<?php echo $ui['txt_email_ph'];?>">
			<div class="btn btn-lg master-button" data-bs-dismiss="offcanvas" aria-label="Close" id="email_submit" style="margin-top:-7px;">
				<?php echo $ui['txt_email_btn'];?>
			</div>  
		</div>
	</div>
</div>

<div class="optin-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_optin" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2><?php echo $ui['txt_optin_title'];?></h2>
				<p class="mt-3"><?php echo $ui['txt_optin_intro'];?></p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_out" style="width:48%;">
				<?php echo $ui['txt_optin_btn_1'];?>
			</div>  
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_in" style="margin-left:10px;width:48%;">
				<?php echo $ui['txt_optin_btn_2'];?>
			</div>  
		</div>
	</div>
</div>

<div class="connecting-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_connect" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0 text-center mt-5">
				<h2>
					<?php echo $ui['txt_connect_title'];?><br>
					<span class="connecting"><?php echo $ui['txt_connect_intro'];?></span>
				</h2>
				<div class="spinner-border mt-2" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>
			</div>	
		</div>
	</div>
</div>

<script src="https://cdn.menuflow.com/app/mnu/jquery-3.4.1.min.js"></script>
<script src="https://cdn.menuflow.com/app/mnu/bootstrap.bundle.5.2.3.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="inc/_global.js"></script>

</body>
</html>