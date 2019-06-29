<?php
include ROOT_DIR . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

function create_jwt_token($data){
  // variables used for jwt
  $key = JWT_KEY;
  $iss = APP_URL;
  $aud = APP_URL;
  $exp = time() + 3600;
  
  
  $token_payload = array(
    "iss" => $iss,
    "aud" => $aud,
    "exp" => $exp,
    "data" => $data
  );
  
  return JWT::encode($token_payload, base64_decode(strtr($key, '-_', '+/')), 'HS256');
}


function verify_jwt_token($jwt){
  if($jwt){
    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, base64_decode(strtr(JWT_KEY, '-_', '+/')), array('HS256'));
        return array(
            "verified" => true,
            "data" => $decoded->data
        );
    } catch (Exception $e){
      // tell the user access denied  & show error message
      return array(
          "verified" => false,
          "error" => $e->getMessage()
      );
  	}
	}
}

/**
 * get access token from header
 * */
function getBearerToken() {
	$headers = getAuthorizationHeader();
	// HEADER: Get the access token from the header
	if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
					return $matches[1];
			}
	}
	return null;
}

/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
}

function checkRequestAuthorization($allowedSender="client"){

	$jwt_token = getBearerToken();
  $token_data = verify_jwt_token($jwt_token);
  if(!$token_data['verified']){
    responseError(400, "Invalid authorization token"); 
    exit();
  }
  
	switch($allowedSender){
		case 'admin':
		  // $user = $token_data
			if($token_data['data']->is_admin !== "1"){
				responseError(401, "Permission denied"); 
				exit();
			}
			break;
			default:
				break;
	}


}

function validateRequestMethod($request){
	if($_SERVER['REQUEST_METHOD'] !== $request){
		responseError(400, "Bad Request");
		exit();
	}
	return true;
}
