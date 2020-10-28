<?php

//require files
require 'config/Database.php';
require 'classes/Educations.php';
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

//Create an instance of the class "Educations" and send the db connection as a parameter 
$educations = new Educations($db);

//switch methods
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific education
            $result =$educations->getOne($id);
        } else {
            //get all 
            $result = $educations->getAll();
        }
        //use function to check if result contain any data
        $reusableApi->getData($result);
        break;
    //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "educations" if it isn't empty
        if(
            !empty($data->education) &&
            !empty($data->school) &&
            !empty($data->startDate) &&
            !empty($data->description)
        ){
            $educations->education = $data->education;
            $educations->school = $data->school;
            $educations->startDate = $data->startDate;
            $educations->endDate = $data->endDate;
            $educations->description = $data->description;
        
            //set NULL if endDate empty
            if (empty($educations->endDate)) {
                $educations->endDate = NULL;
           }
            //create user
            if($educations->create()) {
                http_response_code(201); //created
                $result = array("message" => "Education is created");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Coulden't create Education");
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

            //send data to props in class "educations"
            $educations->education = $data->education;
            $educations->school = $data->school;
            $educations->startDate = $data->startDate;
            $educations->endDate = $data->endDate;
            $educations->description = $data->description;

            //update 
            if($educations->update($id)) {
                http_response_code(200); //ok
                $result = array("message" => "The Education is updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t update Education");
            }
        }
    break;
    case 'DELETE':
        //error because no id
        if(!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Send an ID to delete");
        } else {
            //delete user with a specific id
            if($educations->delete($id)) {
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