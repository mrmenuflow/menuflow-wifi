<?php

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// get lang pack
$translation = json_decode(file_get_contents('inc/_lang_wifi.json'), true);
$trans = $translation['en'];
?>
<html>
<head>
	<!-- platform v4 -->
	<title>WiFi by Menuflow</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">
	<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/bootstrap.5.2.3.min.css">
	<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/swiper-bundle.min.8.3.0.css">
	<link rel="stylesheet" href="https://cdn.menuflow.com/app/mnu/global.css">
	<link rel="stylesheet" href="https://use.typekit.net/ngt4nfe.css">
	<style>
		body {
			height: 100%;
			color: #746A60;
			font-family: "ltc-caslon-pro", serif;
			font-weight: 400;
			background: #efded7;
		}
		.link {
			color: #746A60;
		}
		.link:hover {
			color: #59524a;
		}
		.launch-screen {
			overflow: hidden;
			display: flex;
			align-items: center;
			justify-content: center;
			position: fixed;
			top: 0;
			right: 0;
			left: 0;
			bottom: 0;
			height: 100%;
			font-family: Manrope, sans-serif;
			font-size: 13px;
			line-height: 20px;
			color: #746A60;
			background-color: transparent;
			background: url(inc/la-maison-ani-backdrop.png) center center;
			background-repeat: no-repeat;
			background-size: cover;
		}

		@-webkit-keyframes "pulse" {
			0% {
					opacity: 0.8;
					-webkit-transform: scale(1.1);
					transform: scale(1.1);
			}
			50% {
				opacity: 0.5;
				-webkit-transform: scale(0.8);
				transform: scale(0.8);
			}
			100% {
				opacity: 0.2;
				-webkit-transform: scale(1);
				transform: scale(1);
			}
		}
		.privacy, .privacy.on, #opt_in, #opt_out { 
			color: #fff; 
			background: #000; 
			padding: 1rem 1rem;
			border-radius: 7.5px;
			font-family: "ltc-caslon-pro", serif;
			font-style: italic;
			font-size: 21px;
			font-weight: 600;
			line-height: 13px;
			letter-spacing: -1px;
			text-transform: lowercase;
			
			color: #FFF;
			height: 52px;
			max-height: 52px;
		}
		.privacy { color: #FFF; border: 1px solid <?php echo $_SESSION['config']['navbar_item_bg_active_hex'];?>; background: #FFF; text-transform: normal!important; }
			/* basket modal styles */
		.modal-header { background: <?php echo $_SESSION['config']['navbar_bg_hex'];?>; color: <?php echo $_SESSION['config']['navbar_item_hex'];?>; }
		h2 { 
			color: #746A60;
			font-family: "ltc-caslon-pro", serif;
			font-weight: 700;
			font-style: normal;
			font-size: 1.688rem;
			line-height: 1.35;
			margin-bottom: 8px; 
			text-transform: lowercase;
			letter-spacing: -1px;
		}
		.cookie_info h6 {
			
		}
		.cookie_info { 
			display: none;
			font-family: Manrope, sans-serif;
			font-weight: 400;
		}
		.cookie_info .switch {
			float:right;
			height:24px;
			margin-top:-1px;
			margin-right: 5px;
		}
	</style>
</head>
<body>

<div class="launch-screen" style="display:none;">
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:225px;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="privacy" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close_terms float-end" style="display:none;width:28px;color:#746A60">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>

					<h2><?php echo $trans['modal_cookie_title'];?></h2>
					<p class="mt-3"><?php echo $trans['modal_cookie_intro'];?></p>
			</div>	
			<div class="w-100 p-2 pt-0 cookie_info">
				<p class="mt-3">
					When you visit any website, it may store or retrieve information on your browser, mostly in the form of cookies. 
					This information might be about your preferences or device and is mostly used to make the site work. 
					The information does not usually directly identify you, but it can give you a more personalised menu experience.
					Shown below are the cookies this service uses.
				</p>
				
			</div>
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="w-100 btn btn-lg mt-3 privacy on" data-bs-dismiss="offcanvas" aria-label="Close" id="ok" style="border-color: #BC9D95;background:#BC9D95">
				<?php echo $trans['modal_cookie_btn_2'];?>
			</div>  
		</div>
	</div>
</div>


<div class="name-screen">
	<!--<div class="logo"></div>-->
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:195px!important;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="guest_name" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close float-end" style="display:none;width:28px;color:#746A60">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>
					<h2>Bonjour Mon Ami</h2>
					<p class="mt-1" style="font-family: Manrope, sans-serif;">Enter your name below, s'il vous plait.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input class="" type="text" name="guest_name" placeholder="your name..." style="padding: 1rem 1rem;font-family: Manrope, sans-serif;width:60%;font-size: 18px;line-height: 54px;height:54px;border: 3px  solid #e6d5cd!important;border-radius:8.5px;background: #f1e8e4; color: #746A60;font-weight:500;">
			<div class="btn btn-lg privacy on" data-bs-dismiss="offcanvas" aria-label="Close" id="name_submit" style="border-color: #BC9D95;background:#BC9D95;margin-top:-7px;">
				Continue
			</div>  
		</div>
	</div>
</div>

<div class="email-screen">
	<!--<div class="logo"></div>-->
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:195px;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="guest_email" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close float-end" style="display:none;width:28px;color:#746A60">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>
					<h2>email address</h2>
					<p class="mt-1" style="font-family: Manrope, sans-serif;">Please enter your email address below.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<input type="email" name="guest_name" placeholder="you@youremail.com" style="padding: 1rem 1rem;font-family: Manrope, sans-serif;width:60%;font-size: 21px;line-height: 54px;height:54px;border: 3px  solid #e6d5cd!important;border-radius:8.5px;background: #f1e8e4; color: #746A60;font-weight:500;">
			<div class="btn btn-lg privacy on" data-bs-dismiss="offcanvas" aria-label="Close" id="email_submit" style="border-color: #BC9D95;background:#BC9D95;margin-top:-7px;">
				Continue
			</div>  
		</div>
	</div>
</div>

<div class="optin-screen">
	<!--<div class="logo"></div>-->
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:260px;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="guest_optin" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="close float-end" style="display:none;width:28px;color:#746A60">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
					</svg>
					<h2>Join LMA private circle!</h2>
					<p class="mt-3" style="font-family: Manrope, sans-serif;">Have your fresh take on the way we live La Maison Ani London with insider updates on our latest news and happenings.</p>
			</div>	
		</div>
		<div class="offcanvas-footer p-3 pt-0 pb-4">
			<div class="btn btn-lg mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_out" style="width:48%;border-color: #BC9D95;background:#BC9D95">
				No not now
			</div>  
			<div class="btn btn-lg mt-3" data-bs-dismiss="offcanvas" aria-label="Close" id="opt_in" style="margin-left:10px;width:48%;border-color: #BC9D95;background:#BC9D95">
				Yes, merci
			</div>  
		</div>
	</div>
</div>

<div class="connecting-screen">
	<!--<div class="logo"></div>-->
	<div class="offcanvas offcanvas-bottom m-0" style="border: 3px  solid #e6d5cd!important;border-radius:18.5px;height:225px;background: rgba(239, 223, 218, 0.75);" data-bs-backdrop="false" tabindex="-1" id="guest_connect" aria-labelledby="offcanvasBottomLabel">
		<div class="offcanvas-body">
			<div class="w-100 p-2 pb-0 text-center mt-5">

				<h2>merci<br><span style="font-size: 20px;">We are connecting you to the internet...</span></h2>
				<div class="spinner-border mt-2" role="status" style="width:1.5rem;height:1.5rem; color: #746A60;">
					<span class="visually-hidden">Loading...</span>
				</div>
			</div>	
		</div>
	</div>
</div>

<div class="offcanvas offcanvas-bottom p-0" style="height:86%;" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="menu" aria-labelledby="offcanvasScrollingLabel">
	<div class="offcanvas-body p-0"></div>
</div>

<script src="https://cdn.menuflow.com/app/mnu/jquery-3.4.1.min.js"></script>
<script src="https://cdn.menuflow.com/app/mnu/bootstrap.bundle.5.2.3.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.menuflow.com/app/mnu/ekko-lightbox-1.8.3-1.js"></script>
<script>
$('.launch-screen').fadeIn(1850);

setTimeout(function(){ 
	as_launch.show(); 
}, 2000);

window.scrollTo(0,1);

// action sheet 
var as_launch  = new bootstrap.Offcanvas('#privacy');
var as_guest 	 = new bootstrap.Offcanvas('#guest_name');
var as_email 	 = new bootstrap.Offcanvas('#guest_email');
var as_optin 	 = new bootstrap.Offcanvas('#guest_optin');
var as_connect = new bootstrap.Offcanvas('#guest_connect');

// browser lang
var userLang = navigator.language || navigator.userLanguage;
var locale_list = ['en', 'es', 'pt', 'it', 'fr', 'ar'];
var locale = userLang.split('-')[0];

// check locale support list
if($.inArray(locale, locale_list) != -1) { var user_locale = locale; } else { var user_locale = 'en'; }

$('body').on('click', '#policy', function(e) {
	$('.offcanvas').animate({height:'90%'}, 500);
	$('.cookie_info').fadeIn(800);
	$('.offcanvas').css("background","rgba(239, 223, 218, 1)");
	$('.close_terms').fadeIn(700);
});

$('body').on('click', '.close_terms', function(e) {
		$('.offcanvas').animate({height:'225px'}, 500);
		$('.close_terms').fadeOut(400);
		$('.cookie_info').fadeOut(400);
});

$('body').on('click', '#ok', function(e) {
	setTimeout(function(){ 
		as_guest.show(); 
	}, 700);
});	

$('body').on('click', '#name_submit', function(e) {
	setTimeout(function(){ 
		as_email.show(); 
	}, 700);
});	

$('body').on('click', '#email_submit', function(e) {
	setTimeout(function(){ 
		as_optin.show(); 
	}, 700);
});	

$('body').on('click', '#opt_in,#opt_out', function(e) {
	setTimeout(function(){ 
		as_connect.show(); 
	}, 700);
});	
</script>
</body>
</html>
			