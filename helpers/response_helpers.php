<?php
function responseError($responseCode, $errorMessage){
  http_response_code($responseCode);
  echo json_encode(array("error" => $errorMessage));
}

function responseSuccess($successData){
  http_response_code(200);
  if( gettype($successData) !== "array" ){
    echo json_encode(array($successData));
  } else {
    echo json_encode($successData);
  }
}