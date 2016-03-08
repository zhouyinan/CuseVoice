<?php
function verify_recaptcha($response = ''){
  $ch = curl_init();
  curl_setopt_array($ch,array(
    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
      'secret' => C('RECAPTCHA_SERVER_SECRET'),
      'response' => $response?:$_POST['g-recaptcha-response'],
      'remoteip' => $_SERVER['REMOTE_ADDR']
    ),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_COOKIESESSION => false,
  ));
  $resultFromGoogle = json_decode(curl_exec($ch),true);
  curl_close($ch);
  return $resultFromGoogle['success'];
}
