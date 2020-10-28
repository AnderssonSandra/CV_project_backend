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

//Create variable "id" if there is any id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Create Database object and connect
$database = new Database();
$db = $database->connect();

//Create an instance of the class "Users" and send the db connection as a parameter 
$users = new Users($db);

//switch methods
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific user
            $result =$users->getOne($id);
        } else {
            //get all
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
            !empty($data->username) &&
            !empty($data->password) 
        ){
            $users->username = $data->username;
            $users->password = $data->password;
        
            //create user
            if($users->create()) {
                http_response_code(201); //created
                $result = array("message" => "User is created");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Coulden't create user");
            };
        }
    break;
    //PUT
    case 'PUT':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); //not extended
            $result = array("message" => "Send an ID to update");
        } else {
            $data = json_decode(file_get_contents("php://input"));

            //send data to props in class "Users"
            $users->username = $data->username; 
            $users->password = $data->password;

            //update 
            if($users->update($id)) {
                http_response_code(200); //ok
                $result = array("message" => "The user is updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t update user");
            }
        }
    break;
    case 'DELETE':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Send an ID to update");
        } else {
            //delete user with a specific id
            if($users->delete($id)) {
                http_response_code(200); //ok
                $result = array("message" => "Deleted");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t delete");
            }
        }
    break;
}

//Return result as JSON
echo json_encode($result);

//close database-connection 
$db = $database->close();

?>