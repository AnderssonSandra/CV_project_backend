<?php
//require files
require 'config/Database.php';
require 'classes/Users.php';
require 'classes/ReusableApi.php';

//create instace of reusable class
$reusableApi = new ReusableApi();

// call on function to set headers 
$reusableApi->setHeaders();

//Store requested method in a variable 
$method = $_SERVER['REQUEST_METHOD'];

//Create Database object and connect
$database = new Database();
$db = $database->connect();

//Create an instance of the class "Users" and send the db connection as a parameter 
$user = new Users($db);


// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->email = $data->email;
$email_exists = $user->emailExists();

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// check if email exists and if password is correct
if($email_exists && password_verify($data->password, $user->password)){

    $token = array(
        "iat" => $issued_at,
        "exp" => $expiration_time,
        "iss" => $issuer,
        "data" => array(
            "id" => $user->id,
            "email" => $user->email,
            "firstname" => $user->firstname,
            "surname" => $user->lastname
        )
    );

    // set response code
    http_response_code(200);
    
    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );
//login failed
} else{
    // set response code
        http_response_code(401);
    
        // tell the user login failed
        echo json_encode(array("message" => "Login failed.")
    );
}


?>