<?php

// function to check DNS MX records 
function domain_exists($email, $record = 'MX'){
	$data = explode('@', $email);
	$user = $data[0];
	$domain = $data[1];
	return checkdnsrr($domain, $record);
}

// workout response 
if(domain_exists($_POST['email'])) {
	echo 200;
}
else {
	echo 500;
}
