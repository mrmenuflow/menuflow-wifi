<?php 

// start session management
session_start();

if (isset($_SESSION['count'])) {
	$_SESSION['count'] = $_SESSION['count']++;
}
else {
	$_SESSION['count'] = 1;
}

echo '<pre>';
	print_r($_SESSION['count']);
echo '</pre><br><hr><br>';

phpinfo();

