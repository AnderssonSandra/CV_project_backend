<?php

//require files
require 'config/Database.php';
require 'classes/Info.php';
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


//Create Database object and connect
$database = new Database();
$db = $database->connect();

//Create an instance of the class "infos" and send the db connection as a parameter 
$infos = new Info($db);

//switch methods
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific info
            $result =$infos->getOne($id);
        } else {
            //get all
            $result = $infos->getAll();
        }
        //use function to check if result contain any data
        $reusableApi->getData($result);
        break;
    //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "Info"
        $infos->name = $data->name;
        $infos->lastname = $data->lastname;
        $infos->email = $data->email;
        $infos->phone = $data->phone;
        $infos->linkedin = $data->linkedin;
        $infos->introduction = $data->introduction;
        $infos->description = $data->description;
        
        //create info
        if($infos->create()) {
            http_response_code(201); //created
            $result = array("message" => "Info is created");
        } else {
            http_response_code(503); //Server error
            $result = array("message" => "Coulden't create Info");
        };
    break;
    //PUT
    case 'PUT':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); //not extended
            $result = array("message" => "Send an ID to update");
        } else {
            $data = json_decode(file_get_contents("php://input"));

            //send data to props in class "infos"
            $infos->name = $data->name;
            $infos->lastname = $data->lastname;
            $infos->email = $data->email;
            $infos->phone = $data->phone;
            $infos->linkedin = $data->linkedin;
            $infos->introduction = $data->introduction;
            $infos->description = $data->description;

            //update 
            if($infos->update($id)) {
                http_response_code(200); //ok
                $result = array("message" => "The Info is updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t update Info");
            }
        }
    break;
    //DELETE
    case 'DELETE':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Send an ID to delete");
        } else {
            //delete user with a specific id
            if($infos->delete($id)) {
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