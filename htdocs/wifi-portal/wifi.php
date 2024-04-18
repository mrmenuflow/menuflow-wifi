<?php

// start session management
session_start();

// get url data to work out unifi site
$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
$dir = $parsedUrl['path'];
$path = explode('/',  $dir);
$lid_traget = $path[2];



echo '<pre>';

echo $parsedUrl.'<hr>';
echo $lid_traget.'<hr>';
print_r($path);

print_r($_GET);

// build profile array
if (!isset($_SESSION['profile'])) {
	$_SESSION['profile'] = array('venue' => array('serivce_id' => 0, 'location_id'	=> 0, 'connected_at' => ''), 'ap' => array('vendor' => '', 'vendor_id' => '', 'ap_mac' => '', 'auth_url' => '', 'auth_usr' => '', 'auth_psw' => ''), 'device' => array('mac' => '', 'type' => '', 'ico' => ''), 'guest' => array('name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));
}

print_r($_SESSION['profile'] );
echo '</pre>';
exit;

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// get lang pack
$translation = json_decode(file_get_contents('inc/_lang_wifi.json'), true);
$trans = $translation['en'];

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
</head>
<body>
<div class="launch-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="privacy" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close_terms float-end">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
				</svg>
				<h2><?php echo $trans['modal_cookie_title'];?></h2>
				<p class="mt-3"><?php echo $trans['modal_cookie_intro'];?></p>
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
				<?php echo $trans['modal_cookie_btn_2'];?>
			</div>  
		</div>
	</div>
</div>

<div class="name-screen">
	<div class="offcanvas sm offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_name" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2>Bonjour Mon Ami</h2>
				<p class="mt-1">Enter your name below, s'il vous plait.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input class="" type="text" name="guest_name" placeholder="your name...">
			<div class="btn btn-lg master-button" data-bs-dismiss="offcanvas" aria-label="Close" id="name_submit" style="margin-top:-7px;">
				Continue
			</div>  
		</div>
	</div>
</div>

<div class="email-screen">
	<div class="offcanvas sm offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_email" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2>email address</h2>
				<p class="mt-1">Please enter your email address below.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input type="email" name="guest_name" placeholder="you@youremail.com">
			<div class="btn btn-lg master-button" data-bs-dismiss="offcanvas" aria-label="Close" id="email_submit" style="margin-top:-7px;">
				Continue
			</div>  
		</div>
	</div>
</div>

<div class="optin-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_optin" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
				<h2>Join LMA private circle!</h2>
				<p class="mt-3">Have your fresh take on the way we live La Maison Ani London with insider updates on our latest news and happenings.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_out" style="width:48%;">
				No not now
			</div>  
			<div class="btn btn-lg master-button mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_in" style="margin-left:10px;width:48%;">
				Yes, merci
			</div>  
		</div>
	</div>
</div>

<div class="connecting-screen">
	<div class="offcanvas offcanvas-bottom m-0" data-bs-backdrop="false" tabindex="-1" id="guest_connect" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0 text-center mt-5">
				<h2>
					merci<br>
					<span class="connecting">We are connecting you to the internet...</span>
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
<script src="inc/_global.js" id="app" sid="<?php echo $_SESSION['config']['service_id'];?>" lid="<?php echo $_SESSION['config']['location_id'];?>" mac="<?php echo $_SESSION['config']['mac'];?>" vnd="<?php echo $_SESSION['config']['vendor'];?>"></script>

</body>
</html>