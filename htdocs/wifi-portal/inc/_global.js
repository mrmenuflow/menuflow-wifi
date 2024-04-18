// transitions
$('.launch-screen').fadeIn(1850);
setTimeout(function(){ as_launch.show(); }, 2000);

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
if($.inArray(locale, locale_list) != -1) { var user_locale = locale; } else { var user_locale = 'en'; }

// device detection
var ipad = /ipad/i.test(navigator.userAgent.toLowerCase());
var iphone = /iphone/i.test(navigator.userAgent.toLowerCase());
var ipod = /ipod/i.test(navigator.userAgent.toLowerCase());
var android = /android/i.test(navigator.userAgent.toLowerCase());
var mac = /macintosh/i.test(navigator.userAgent.toLowerCase());
var pc = /windows/i.test(navigator.userAgent.toLowerCase());
if (ipad) { var dev = 'iPad'; var ico = 'apl'; } else if (iphone) { var dev = 'iPhone'; var ico = 'apl'; } else if (ipod) { var dev = 'iPod'; var ico = 'apl'; } else if (android) { var dev = 'Android'; var ico = 'dro'; } else if (mac) { var dev = 'Macbook'; var ico = 'apl'; } else if (pc) { var dev = 'PC Windows'; var ico = 'win'; } else { var dev = 'unknown'; var ico = 'unk';}

// update device array
$.ajax({
	url: 'inc/_builder',
	type: 'POST',
	data: { action: 'device', type: dev, ico: ico },
	success: function (rsp) {  }
});

// expand terms
$('body').on('click', '#view_terms', function(e) {
	$('.offcanvas').animate({height:'90%'}, 500);
	$('.terms_info').fadeIn(800);
	$('.offcanvas').css("background","rgba(239, 223, 218, 1)");
	$('.close_terms').fadeIn(700);
});

// hide terms
$('body').on('click', '.close_terms', function(e) {
	$('.offcanvas').animate({ height:'225px' }, 500);
	$('.close_terms').fadeOut(400);
	$('.terms_info').fadeOut(400);
});

// move to name screen
$('body').on('click', '#ok', function(e) {
	setTimeout(function(){ 
		as_guest.show(); 
	}, 700);
});	

// move to email screen
$('body').on('click', '#name_submit', function(e) {
	var name = $('.guest_name').val();
	$.ajax({
		url: 'inc/_builder',
		type: 'POST',
		data: { action: 'guest', name: name, locale: locale },
		success: function (rsp) {
			setTimeout(function(){   
				as_email.show();
			}, 500);	 
		}
	});
});	

// move to opt-in screen
$('body').on('click', '#email_submit', function(e) {
	var email = $('.guest_email').val();
	$.ajax({
		url: 'inc/_builder',
		type: 'POST',
		data: { action: 'guest_email', email: email },
		success: function (rsp) {
			setTimeout(function(){   
				as_optin.show();
			}, 500);	 
		}
	});
});	

// move to connecting screen
$('body').on('click', '#opt_in,#opt_out', function(e) {
	if (this.id == 'opt_in') { var	subscribed = 1; } else { var subscribed = 0; }
	$.ajax({
		url: 'inc/_builder',
		type: 'POST',
		data: { action: 'guest_optin', subscribed: subscribed },
		success: function (rsp) {
			setTimeout(function(){   
				as_connect.show(); 
			}, 500);	 
		}
	});
});	