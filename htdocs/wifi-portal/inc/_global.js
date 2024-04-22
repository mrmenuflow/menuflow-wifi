jQuery.fn.shake = function(intShakes, intDistance, intDuration) {  
	intShakes = intShakes || 5;
	intDistance = intDistance || 2;
	intDuration = intDuration || 1000;
	this.each(function() {
		$(this).css("position","relative"); 
		for (var x=1; x<=intShakes; x++) {
			$(this).animate({left:(intDistance*-1)}, (((intDuration/intShakes)/4)))
			.animate({left:intDistance}, ((intDuration/intShakes)/2))
			.animate({left:0}, (((intDuration/intShakes)/4)));
		}
	});
	return this;
};

// function to change button icon
function changeButton(target, state) {
	if (state == 'clicked') {
		$(target).html('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon" style="width:30px;margin-top:-5px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>');
	}
	else if (state == 'success') {
		$(target).html('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="icon" style="width:19px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>');
	}
	else if (state == 'stop') {
		$(target).html('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon" style="width:19px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15m.002 0h-.002" /></svg>');
	}
}   

// screen rotate 
window.addEventListener("orientationchange", function() {
	if ( window.orientation == 90 || window.orientation == -90 ) {
		$('#rotate').removeClass('d-none');
		$('.launch-screen').addClass('d-none');
	}
	else {
		$('#rotate').addClass('d-none');
		$('.launch-screen').removeClass('d-none');
	}
}, false);

// launch transitions
$('.launch-screen').fadeIn(1850);

// action sheet 
var as_launch = new bootstrap.Offcanvas('#privacy');
var as_guest = new bootstrap.Offcanvas('#guest_name');
var as_email = new bootstrap.Offcanvas('#guest_email');
var as_optin = new bootstrap.Offcanvas('#guest_optin');
var as_connect = new bootstrap.Offcanvas('#guest_connect');
var as_return = new bootstrap.Offcanvas('#guest_return');
var as_return_optin = new bootstrap.Offcanvas('#guest_return_optin');

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

// check device for repeat visit 
$.ajax({
	url: '/inc/_builder',
	type: 'POST',
	data: { action: 'check_device', locale: locale, type: dev, ico: ico },
	success: function (rsp) {  
		data = JSON.parse(rsp);
		crm_id = data.guest.crm_id;
		crm_name = data.guest.name.split(' ');
		crm_optin = data.guest.subscribed;

		// workout start screen to show
		if (crm_id > 0) {
			if (crm_optin == 0) {
				// personalize screen 
				$('#guest_return_optin #user').text(crm_name[0]);
				
				// show return connecting with optin screen
				setTimeout(function(){ as_return_optin.show(); }, 500);		
			}
			else {
				// personalize screen 
				$('#guest_return #user').text(crm_name[0]);
				
				// show return connecting screen
				setTimeout(function(){ as_return.show(); }, 500);
			
				// crm update		
				$.ajax({
					url: '/inc/_do_crm_update',
					type: 'POST',
					data: { action: 'guest_crm' },
					success: function (rsp) { }
				});
			}
		}
		else {
			setTimeout(function(){ as_launch.show(); }, 500);
		}
	}
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
	// validate name is longer than 2 chars
	if (name.length > 1) {
		$.ajax({
			url: '/inc/_builder',
			type: 'POST',
			data: { action: 'guest', name: name, locale: locale },
			success: function (rsp) {
				changeButton('#name_submit', 'success');
				setTimeout(function(){   
					as_guest.hide();
				}, 500);		
				setTimeout(function(){   
					as_email.show();
				}, 1200);									 
			}
		});
	}
	else {
		// error check shake button
		$(this).shake();
	}
});	

function validateEmail(email) {
	var regex = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g);
	if (!regex.test(email)) {
		return false;
	}
	else {
		return true;
	}
}

// move to opt-in screen
$('body').on('click', '#email_submit', function(e) {
	var email = $('.guest_email').val();
	var btn_txt = $('#email_submit').text();
	
	// change button state
	changeButton(this, 'clicked');
	
	// validate name is longer than 2 chars
	if (validateEmail(email)) {
		// check email DNS MX record
		$.ajax({
			url: '/inc/_dns_check',
			type: 'POST',
			data: { email: email },
			success: function (rsp) {
				if (rsp == 200) {
					$.ajax({
						url: '/inc/_builder',
						type: 'POST',
						data: { action: 'guest_email', email: email },
						success: function (rsp) {
							// change button state
							changeButton('#email_submit', 'success');
							// transition screens
							setTimeout(function(){   
								as_email.hide();
							}, 500);		
							setTimeout(function(){   
								as_optin.show();
							}, 1200);									 
						}
					});
				}	
				else {
					// error check shake button
					changeButton('#email_submit', 'stop');
					setTimeout(function(){   
						$('.guest_email').val('');
						$('#email_submit').html(btn_txt);
					}, 1200);		
				}
			}
		});
	}
	else {
		// error check shake button
		$(this).shake();
	}
});	

// move to connecting screen
$('body').on('click', '#opt_in,#opt_out', function(e) {
	if (this.id == 'opt_in') { var	subscribed = 1; } else { var subscribed = 0; }
	$.ajax({
		url: '/inc/_builder',
		type: 'POST',
		data: { action: 'guest_optin', subscribed: subscribed },
		success: function (rsp) {
			setTimeout(function(){
				// show connecting screen   
				as_connect.show();
				
				// crm update		
				$.ajax({
					url: '/inc/_do_crm_update',
					type: 'POST',
					data: { action: 'guest_crm' },
					success: function (rsp) { }
				});
				
				// do guest auth
				
			}, 500);	 		
		}
	});
});	