<?php

// start session management 
session_start();

// include api proxy
include_once($_SERVER['DOCUMENT_ROOT'].'/inc/_proxy.php');

echo '<pre>';
print_r($_SESSION['profile']);