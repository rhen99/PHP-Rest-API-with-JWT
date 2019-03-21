<?php
include_once('../../config/Database.php');
include_once('../../models/User.php');
include_once('../../config/core.php');
include_once('../../lib/php-jwt-master/src/BeforeValidException.php');
include_once('../../lib/php-jwt-master/src/ExpiredException.php');
include_once('../../lib/php-jwt-master/src/SignatureInvalidException.php');
include_once('../../lib/php-jwt-master/src/JWT.php');
use \Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: http://localhost/php%20rest%20api/');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

$data = json_decode(file_get_contents('php://input'));

$jwt = isset($data->jwt) ? $data->jwt : '';

if($jwt){
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256')); 
        http_response_code(200);
        echo json_encode(array(
            'message' => 'Access Granted',
            'data' => $decoded->data
        ));       
    } catch (Exception $e) {
        http_response_code(401);
        
        json_encode(array(
            'message' => 'Access Denied',
            'error' => $e->getMessage()
        ));
    }
}