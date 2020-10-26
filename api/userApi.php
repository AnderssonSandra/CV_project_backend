<?php
//require files
require 'config/Database.php';
require 'classes/Users.php';
require 'classes/ReusableApi.php';

// require files to encode json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
//create instace of reusable class
$reusableApi = new ReusableApi();

// call on function to set headers 
$reusableApi->setHeaders();

//Store requested method in a variable 
$method = $_SERVER['REQUEST_METHOD'];

//Create variable "id" if there is any id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Create Database object and connect
$database = new Database();
$db = $database->connect();

//Create an instance of the class "Users" and send the db connection as a parameter 
$users = new Users($db);

//switch method
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific user
            $result =$users->getOne($id);
        } else {
            //to get all users
            $result = $users->getAll();
        }
        //use function to check if result contain any data
        $reusableApi->getData($result);
        break;
        //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "users" if it isn't empty
        if(
            !empty($data->email) &&
            !empty($data->firstname) &&
            !empty($data->lastname) &&
            !empty($data->password) 
        ){
            $users->email = $data->email;
            $users->firstname = $data->firstname;
            $users->lastname = $data->lastname;
            $users->password = $data->password;
        
            //create user
            if($users->create()) {
                http_response_code(201); //created
                $result = array("message" => "Kursen är skapad");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Det gick tyvärr inte att skapa kursen");
            };
        }
    break;
    //PUT
    case 'PUT':
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // get jwt
        $jwt=isset($data->jwt) ? $data->jwt : "";

        if($jwt){
            // if decode succeed, show user details
            try {
                // decode jwt
                $decoded = JWT::decode($jwt, $key, array('HS256'));
         
                // set user props
                $users->firstname = $data->firstname;
                $users->lastname = $data->lastname;
                $users->email = $data->email;
                $users->password = $data->password;
                $users->id = $decoded->data->id;
                
                // update the user record
                if($users->update()){
                    //re-generate jwt
                    $token = array(
                        "iat" => $issued_at,
                        "exp" => $expiration_time,
                        "iss" => $issuer,
                        "data" => array(
                            "id" => $users->id,
                            "firstname" => $users->firstname,
                            "lastname" => $users->lastname,
                            "email" => $users->email
                        )
                    );
                    $jwt = JWT::encode($token, $key);
                    
                    // set response code
                    http_response_code(200);
                    
                    $result = array(
                        "message" => "User was updated.",
                        "jwt" => $jwt
                    );
                }
                
                // message if unable to update user
                else{
                    // set response code
                    http_response_code(401);
                
                    // show error message
                    $result = array("message" => "Unable to update user.");
                }
            }
         
            // if decode fails, it means jwt is invalid
            catch (Exception $e){
            
                // set response code
                http_response_code(401);
            
                //error message
                $result =array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                );
            }
        }
        else {
            // set response code
            http_response_code(401);
            // message
            $result = array("message" => "Access denied.");
        }
    break;
    case 'DELETE':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Det gick tyvärr inte att radera");
        } else {
            //delete user with a specific id
            if($users->delete($id)) {
                http_response_code(200); //ok
                $result = array("message" => "Kunde inte radera");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Det gick inte att radera");
            }
        }
    break;

}

//Return result as JSON
echo json_encode($result);

//close database-connection 
$db = $database->close();

?>