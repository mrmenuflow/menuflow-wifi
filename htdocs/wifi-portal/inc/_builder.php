<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

// set array
$_SESSION['profile'] = array('venue' => array('serivce_id' => 0, 'location_id'	=> 0, 'connected_at' => ''), 'ap' => array('vendor' => '', 'vendor_id' => '', 'ap_mac' => '', 'auth_url' => '', 'auth_usr' => '', 'auth_psw' => ''), 'device' => array('mac' => '', 'type' => '', 'ico' => ''), 'guest' => array('name' => '', 'email' => '', 'locale' => '', 'dob_day' => '', 'dob_mth' => '', 'zip' => '', 'subscribed' => 0));

$json = json_encode($_SESSION['profile'], JSON_PRETTY_PRINT);

echo '<pre>'.$json.'</pre>';	

?>
<style>
body { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: <?php echo $ui['css_page_bg_hex'];?>; } 
.link { color: <?php echo $ui['css_canvas_link_hex'];?>; }
.link:hover { color: <?php echo $ui['css_canvas_link_hover_hex'];?>; }
.launch-screen { color: <?php echo $ui['css_canvas_txt_hex'];?>; background: url('<?php echo $ui['css_page_bg_img'];?>') center center;}
.master-button { border-color: <?php echo $ui['css_canvas_btn_hex'];?>; background: <?php echo $ui['css_canvas_btn_hex'];?>; }
.master-button:hover { border-color: <?php echo $ui['css_canvas_btn_hover_hex'];?>; background: <?php echo $ui['css_canvas_btn_hover_hex'];?>; }
h2 { color: <?php echo $ui['css_canvas_title_hex'];?>; } 
.offcanvas, .offcanvas.sm { border: 3px solid <?php echo $ui['css_canvas_brdr_hex'];?>; background: <?php echo $ui['css_canvas_bg_hex'];?>; }
p { color: <?php echo $ui['css_canvas_intro_hex'];?>;}
</style>