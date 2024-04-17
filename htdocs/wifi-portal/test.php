<?php 

// start session management
session_start();

$_SESSION['test'] = array();
array_push($_SESSION['test'], $_SERVER['SERVER_ADDR']);

echo '<pre>';
	print_r($_SESSION['test']);
echo '</pre>';

phpinfo();

