<?php

//require files
require 'config/Database.php';
require 'classes/Projects.php';
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

//Create an instance of the class "projects" and send the db connection as a parameter 
$projects = new Projects($db);

//swish that us the encloses method
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific project
            $result =$projects->getOne($id);
        } else {
            //get all
            $result = $projects->getAll();
        }
        //use function to check if result contain any data
        $reusableApi->getData($result);
        break;
    //POST
    case 'POST':
        //read submitted data and make php objects
        $data = json_decode(file_get_contents("php://input"));
        
        //send data to props in class "projects" if it isn't empty
        if(
            !empty($data->name) &&
            !empty($data->startDate) &&
            !empty($data->description)
        ){
            $projects->name = $data->name;
            $projects->link = $data->link;
            $projects->github = $data->github;
            $projects->techniques = $data->techniques;
            $projects->startDate = $data->startDate;
            if(empty($data->endDate)) {
                $projects->endDate = null;
            } else {
                $projects->endDate = $data->endDate;
            }
            $projects->description = $data->description;
         
            //create project
            if($projects->create()) {
                http_response_code(201); //created
                $result = array("message" => "Project is created");
            } else {
                http_response_code(503); //Server error
                $result = array("message" => "Coulden't create Project");
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

            //send data to props in class "projects"
            $projects->name = $data->name;
            $projects->link = $data->link;
            $projects->github = $data->github;
            $projects->techniques = $data->techniques;
            $projects->startDate = $data->startDate;
            if(empty($data->endDate)) {
                $projects->endDate = null;
            } else {
                $projects->endDate = $data->endDate;
            }
            $projects->description = $data->description;

            //update 
            if($projects->update($id)) {
                http_response_code(200); //ok
                $result = array("message" => "The Project is updated");
            } else {
                http_response_code(503); //server error
                $result = array("message" => "Coulden´t update Project");
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
            if($projects->delete($id)) {
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