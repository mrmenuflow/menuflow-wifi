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

$('body').on('click', '#view_terms', function(e) {
	$('.offcanvas').animate({height:'90%'}, 500);
	$('.terms_info').fadeIn(800);
	$('.offcanvas').css("background","rgba(239, 223, 218, 1)");
	$('.close_terms').fadeIn(700);
});

$('body').on('click', '.close_terms', function(e) {
	$('.offcanvas').animate({ height:'225px' }, 500);
	$('.close_terms').fadeOut(400);
	$('.terms_info').fadeOut(400);
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