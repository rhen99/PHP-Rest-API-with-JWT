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


$database = new Database;
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents('php://input'));

$user->email = $data->email;

$email_exist = $user->verifyEmail();

if($email_exist && password_verify($data->password, $user->password)){

    $token = array(
        'iss' => $iss,
        'aud' => $aud,
        'iat' => $iat,
        'nbf' => $nbf,
        'data' => array(
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email
        )
    );
    http_response_code(200);

    $jwt = JWT::encode($token, $key);

    echo json_encode(
        array(
            'message' => 'Logged in Successfully',
            'jwt' => $jwt
        )
    );
}else{
    http_response_code(401);

    echo json_encode(
        array(
            'message' => 'Login Failed'
        )
    );
}