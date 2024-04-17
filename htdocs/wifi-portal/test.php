<?php 

// start session management
session_start();

$count = isset($_SESSION['count']) ? $_SESSION['count'] : 1;

echo $count;

$_SESSION['count'] = $count++;
echo '<pre>';
	print_r($_SESSION['test']);
echo '</pre><hr>';

phpinfo();

