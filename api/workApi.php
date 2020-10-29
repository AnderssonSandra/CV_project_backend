<?php

//require files
require 'config/Database.php';
require 'classes/Works.php';
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

//Create an instance of the class "Works" and send the db connection as a parameter 
$works = new Works($db);

//switch methods
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific work
            $result =$works->getOne($id);
        } else {
            //get all
            $result = $works->getAll();
        }
        //use funtion to check if result contain any data
        $reusableApi->getData($result);
        break;
        //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "works" if it isn't empty
        if(
            !empty($data->title) &&
            !empty($data->workplace) &&
            !empty($data->startDate) &&
            !empty($data->description)
        ){
            $works->title = $data->title;
            $works->workplace = $data->workplace;
            $works->startDate = $data->startDate;
            if(empty($data->endDate)) {
                $works->endDate = null;
            } else {
                $works->endDate = $data->endDate;
            }
            $works->buzzwords = $data->buzzwords;
            $works->description = $data->description;
        
            //create work
            if($works->create()) {
                http_response_code(201); //created
                $result = array("message" => "Work is created");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Coulden't create Work");
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

            //send data to props in class "works"
            $works->title = $data->title;
            $works->workplace = $data->workplace;
            $works->startDate = $data->startDate;
            if(empty($data->endDate)) {
                $works->endDate = null;
            } else {
                $works->endDate = $data->endDate;
            }
            $works->buzzwords = $data->buzzwords;
            $works->description = $data->description;

            //update 
            if($works->update($id)) {
                http_response_code(200); //ok
                $result = array("message" => "The work is updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t update work");
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
            if($works->delete($id)) {
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