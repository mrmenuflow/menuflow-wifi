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
$connected_at = date('Y-m-d H:i:s');

// do api call
$rsp = call_api('GET', '/wifi/'.$vendor_id);  
$ui = $rsp['result'][0];

// build profile array
$_SESSION['profile'] = array('venue' => array('serivce_id' => $ui['service_id'], 'location_id'	=> $ui['location_id'], 'connected_at' => $connected_at), 'ap' => array('vendor' => $ui['vendor'], 'vendor_id' => $vendor_id, 'ap_mac' => $mac_wap, 'auth_url' => $ui['auth_url'], 'auth_usr' => $ui['auth_usr'], 'auth_psw' => $ui['auth_psw']), 'device' => array('mac' => $mac_usr, 'type' => '', 'ico' => ''), 'guest' => array('crm_id' => 0, 'name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));
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
	<link rel="stylesheet" href="/inc/_global.css">
	<style>
	body { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: <?php echo $ui['css_page_bg_hex'];?>; } 
	.link { color: <?php echo $ui['css_canvas_link_hex'];?>; }
	.link:hover { color: <?php echo $ui['css_canvas_link_hover_hex'];?>; }
	.launch-screen { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: url('/inc/<?php echo $ui['css_page_bg_img'];?>') center center; background-repeat: no-repeat; background-size: cover; }
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
			<div class="btn btn-lg master-button" id="name_submit" style="margin-top:-7px;">
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
			<div class="btn btn-lg master-button" id="email_submit" style="margin-top:-7px;">
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

<div class="return-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_return" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0 text-center mt-5">
				<h2>
					Welcome back <span id="user"></span><br>
					<span class="connecting"><?php echo $ui['txt_connect_intro'];?></span>
				</h2>
				<div class="spinner-border mt-2" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>
			</div>	
		</div>
	</div>
</div>

<div class="return-optin-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_return_optin" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2>Nice to see you, <span id="user"></span></h2>
				<span class="connecting">Choose an option below to connect to the internet.</span>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_in" style="width:48%;">
				Join the VIP list
			</div>  
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_out" style="margin-left:10px;width:48%;">
				No thanks, connect
			</div>  
		</div>
	</div>
</div>

<!-- landscape content -->
<style>#rotate {height: 100%;overflow: hidden;touch-action: none;background: #000;}@media screen and (min-aspect-ratio:5/3){body {position: fixed;overflow: hidden;touch-action: none;background:#000;}#rotate {display: block;height: 100%;overflow: hidden;}#mf-menu {display: none;} }#rotate .splash {position: fixed;background: #000;width: 100%;height: 100%;z-index: 1060!important;-webkit-transform: translateZ(2000px);transform: translateZ(2000px);display: flex;justify-content: center;align-items: center;}#rotate .splash .content {display: flex;flex-direction: column;margin-left: 25px;color: #fff;font-family: "Manrope",sans-serif;}#rotate .splash .ls_title {display: inline-flex;font-size: 2.2rem;font-weight: 800;line-height: 38px;}#rotate .splash .ls_text {display: inline-flex;font-size: 1rem;font-weight: 500;padding-top: 20px;}</style>
<div id="rotate" class="d-none">
	<div class="splash">
		<svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286 286" style="height:105px;color:#fff;"><defs><style>.cls-1 {stroke-width:0px; fill: #FFF;}.cls-2 {stroke-width:0px; fill: transparent;}</style></defs><g id="Layer_1-2" data-name="Layer 1"><g id="oFb7OA.tif"><g><path class="cls-2" d="M1,286l-1-1V1L1,0h284l1,1v284l-1,1H1ZM143.83,2.82c-18.46,0-36.93,0-55.39.01-17.76,0-28.8,11.13-28.8,29.01,0,26.1,0,52.19,0,78.29v32.26c0,10.9.03,21.8.06,32.69.06,25.85.13,52.58-.19,78.87-.1,8.22,2.63,15.38,7.89,20.7,5.3,5.36,12.85,8.44,20.73,8.44h.28c13.29-.12,27.81-.18,45.68-.18,9.64,0,19.28.02,28.92.03,9.64.02,19.28.03,28.92.03h6.5c16.32-.02,27.74-11.33,27.76-27.51.1-76.76.09-152.66-.01-225.6-.02-15.66-11.68-27.03-27.71-27.04-18.21,0-36.42-.01-54.64-.01ZM22.18,66.62c-5.34,0-9.45.07-13.34.23-1.77.07-4.34,1.6-4.9,2.92-.69,1.61-.36,4.57.71,6.32,1.61,2.65,4.15,4.89,6.59,7.06l.37.32c2.34,2.08,2.78,4.08,1.56,7.16C2.72,116.93.27,144.08,5.86,171.31c4.19,20.41,12.82,39.34,25.64,56.26,1.76,2.32,3.67,3.55,5.53,3.55h0c1.11,0,2.23-.42,3.33-1.26,3.09-2.34,3.25-5.15.54-9.11-.72-1.05-1.44-2.09-2.17-3.14-2.28-3.28-4.63-6.67-6.71-10.17-13.43-22.56-19.14-48.21-16.97-76.25.9-11.61,3.51-22.72,7.98-33.96l1.61-.37c1.02.94,1.98,1.87,2.92,2.78,1.95,1.89,3.79,3.67,5.79,5.15,1.29.95,2.7,1.46,4.07,1.46,3.08,0,5.3-2.43,5.39-5.91.22-8.1.23-16.98.01-27.95-.07-3.79-2.11-5.71-6.05-5.71-2.36,0-4.71-.02-7.07-.03-2.51-.02-5.03-.03-7.54-.03ZM248.09,180.18c-.71,0-1.35.1-1.84.3-1.35.54-2.99,3.41-3.08,5.38-.41,9.22-.26,18.48-.12,27.43.06,3.88,2.04,5.85,5.88,5.85,2.31,0,4.62.02,6.94.04,2.6.02,5.19.04,7.79.04,5.21,0,9.41-.08,13.22-.25,1.88-.08,4.57-1.73,5.24-3.2.71-1.56.03-4.56-1.02-6.27-1.57-2.57-4.06-4.73-6.46-6.82l-.19-.16c-2.51-2.19-2.98-4.4-1.61-7.63,6.06-14.28,9.43-29.65,10.01-45.66,1.23-33.73-8.3-64.27-28.32-90.79-1.81-2.4-3.77-3.67-5.67-3.67-1.05,0-2.12.38-3.18,1.14-1.55,1.11-2.4,2.29-2.6,3.61-.25,1.6.47,3.54,2.14,5.77,19.38,25.9,28.03,55.57,25.71,88.19-.84,11.83-3.49,23.36-8.1,35.24l-1.62.36c-1-.96-1.9-1.89-2.76-2.8-1.85-1.93-3.44-3.59-5.32-4.71-1.41-.84-3.38-1.38-5.02-1.38Z"/><path class="cls-1" d="M88.13,285.1c-8.41,0-16.49-3.29-22.16-9.03-5.64-5.71-8.57-13.37-8.47-22.14.32-26.27.25-53,.19-78.84-.03-10.9-.05-21.8-.05-32.7v-32.26c0-26.1,0-52.19,0-78.29,0-19.12,11.81-31,30.8-31.01,18.46,0,36.93-.01,55.39-.01s36.42,0,54.64.01c17.19,0,29.69,12.22,29.71,29.04.11,72.94.11,148.84.01,225.6-.02,17.36-12.26,29.49-29.76,29.5h-6.51c-9.64,0-19.29-.01-28.93-.03-9.64-.02-19.28-.03-28.92-.03-17.87,0-32.38.06-45.66.18h-.3ZM143.52,16.57c-18.19,0-36.39,0-54.58.02-10.9,0-15.98,5.17-15.98,16.25-.01,28.59,0,57.19,0,85.78v54.68c0,26.89,0,53.77.01,80.66,0,9.81,5.33,15.22,14.97,15.24,18.19.03,36.38.05,54.57.05s36.64-.02,54.96-.05c10.41-.02,15.48-5.17,15.48-15.73,0-73.54,0-147.08,0-220.62,0-11.23-4.93-16.24-15.97-16.25-17.82-.02-35.63-.03-53.45-.03Z"/><path class="cls-1" d="M37.03,233.12c-2.51,0-4.97-1.5-7.13-4.34-13-17.16-21.75-36.36-26-57.07-5.68-27.62-3.18-55.15,7.41-81.82.91-2.3.68-3.41-1.03-4.92l-.36-.32c-2.56-2.26-5.21-4.61-6.98-7.51-1.4-2.3-1.78-5.95-.84-8.15.94-2.21,4.36-4.04,6.66-4.13,3.91-.16,8.05-.23,13.42-.23,2.52,0,5.03.02,7.55.03,2.35.01,4.71.03,7.06.03,5.02,0,7.96,2.8,8.05,7.67.21,11,.21,19.91-.02,28.04-.12,4.55-3.23,7.86-7.39,7.86-1.8,0-3.62-.64-5.26-1.85-2.11-1.56-4-3.39-5.99-5.32-.59-.57-1.19-1.15-1.81-1.74-4.07,10.58-6.47,21.07-7.31,32.01-2.13,27.62,3.48,52.88,16.7,75.07,2.05,3.44,4.38,6.8,6.64,10.05.73,1.05,1.46,2.1,2.18,3.15,3.31,4.85,2.98,8.83-.99,11.83-1.46,1.11-2.99,1.67-4.54,1.67Z"/><path class="cls-1" d="M263.64,221.21c-2.6,0-5.2-.02-7.8-.04-2.31-.02-4.62-.03-6.92-.04-4.92,0-7.79-2.86-7.87-7.82-.14-8.98-.29-18.27.12-27.55.11-2.54,2.03-6.22,4.33-7.15.74-.3,1.61-.45,2.59-.45,2.01,0,4.32.63,6.04,1.66,2.12,1.26,3.88,3.1,5.74,5.04.53.55,1.06,1.11,1.62,1.67,4.2-11.19,6.63-22.07,7.42-33.23,2.28-32.13-6.23-61.35-25.32-86.85-2.03-2.71-2.85-5.09-2.51-7.27.29-1.85,1.44-3.51,3.42-4.93,1.4-1,2.86-1.51,4.34-1.51,2.55,0,5.06,1.54,7.26,4.47,20.31,26.9,29.97,57.87,28.72,92.07-.59,16.26-4.02,31.86-10.17,46.37-1.05,2.46-.79,3.71,1.08,5.34l.19.16c2.52,2.19,5.12,4.45,6.85,7.29,1.36,2.22,2.16,5.87,1.13,8.14-.97,2.13-4.35,4.26-6.97,4.38-3.84.17-8.07.25-13.3.25Z"/><path class="cls-2" d="M142.5,271.26c-18.19,0-36.38-.02-54.57-.05-10.77-.02-16.96-6.3-16.97-17.24-.02-26.36-.02-52.73-.02-79.09v-60.22c0-27.27,0-54.54,0-81.81,0-12.27,5.89-18.24,17.98-18.25,18.2-.02,36.4-.02,54.6-.02s35.62,0,53.44.03c12.09.01,17.97,5.98,17.97,18.25,0,73.54,0,147.08,0,220.62,0,11.58-6.04,17.71-17.47,17.73-18.32.03-36.64.05-54.97.05ZM131.26,249.99c-2.65,0-5.29,0-7.94.03-4.44.04-7.26,2.19-7.36,5.6-.05,1.65.46,3.07,1.48,4.13,1.28,1.33,3.28,2.04,5.79,2.05,6.52.04,13.05.05,19.58.05s13.18-.02,19.78-.05c2.54-.01,4.55-.71,5.83-2.01,1.03-1.05,1.53-2.43,1.51-4.1-.05-3.45-2.83-5.63-7.26-5.67-2.51-.02-5.01-.03-7.52-.03-2.21,0-4.41,0-6.62.01h-5.29c-2,.01-9.99,0-11.99,0ZM143.03,24.03c-1.43,0-2.9.63-4.03,1.73-1.13,1.09-1.77,2.5-1.77,3.86,0,3.3,2.77,6.19,5.92,6.19.11,0,.22,0,.34-.01,2.79-.18,5.45-3.21,5.37-6.11-.08-2.95-2.84-5.64-5.8-5.65l-.02-1v1Z"/><path class="cls-1" d="M142.8,263.86c-6.53,0-13.06-.02-19.59-.05-3.06-.02-5.55-.94-7.22-2.66-1.4-1.45-2.11-3.38-2.04-5.58.13-4.53,3.8-7.49,9.34-7.54,2.69-.02,5.38-.03,8.06-.03,1.87,0,11.88.01,11.88.01,1.85,0,3.7,0,5.55-.01,2.09,0,4.18-.01,6.26-.01,2.54,0,5.08,0,7.63.03,5.54.05,9.17,3.05,9.24,7.64.03,2.2-.69,4.11-2.08,5.53-1.66,1.69-4.17,2.6-7.25,2.61-6.6.03-13.19.05-19.79.05Z"/><path class="cls-1" d="M143.14,37.8c-4.29,0-7.91-3.75-7.92-8.19,0-1.9.87-3.83,2.38-5.3,1.51-1.46,3.48-2.29,5.43-2.29,4.07.01,7.71,3.56,7.82,7.6.11,4.02-3.34,7.91-7.24,8.16-.16,0-.31.02-.47.02Z"/></g></g></g></svg>
		<div class="content">
			<span class="ls_title">Oops, I'm more of a</span>
			<span class="ls_title">portrait kind of app!</span>
			<span class="ls_text">Rotate your device to access wifi.</span>
		</div>
	</div>
</div>

<script src="https://cdn.menuflow.com/app/mnu/jquery-3.4.1.min.js"></script>
<script src="https://cdn.menuflow.com/app/mnu/bootstrap.bundle.5.2.3.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="/inc/_global.js"></script>
</body>
</html>