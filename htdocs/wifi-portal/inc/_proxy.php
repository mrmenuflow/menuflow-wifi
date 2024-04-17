<?php

// start session management
if(!isset($_SESSION)) {
  session_start();
}

// include regional data
include_once $_SERVER['DOCUMENT_ROOT'].'/../common/environment.php';

// function to call api and return result as array
function call_api($method, $endpoint, $data = null) {
  $env = new Environment();
  $api_key = $env->getGoApiKey();
  $server  = $env->getGoApiDevServer();
  $endpoint = $server.$endpoint;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $endpoint);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'x-api-key:'.$api_key, 'Content-Type: application/json' ));
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
  $result = curl_exec($ch);
  curl_close($ch);
  return json_decode($result, true);
}
