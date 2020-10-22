<?php

//require files
require '../config/Database.php';
require '../classes/Works.php';
require '../classes/ReusableApi.php';

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

//Create an instance of the class "works" and send the db connection as a parameter 
$works = new Works($db);

//swish that us the encloses method
switch($method) {
    //GET
    case 'GET':
        if(isset($id)) {
            //if there is an id, get specific user
            $result =$works->getOne($id);
        } else {
            //to get all works
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
            $works->userId = $data->userId; 
            $works->title = $data->title;
            $works->workplace = $data->workplace;
            $works->startDate = $data->startDate;
            $works->endDate = $data->endDate;
            $works->buzzwords = $data->buzzwords;
            $works->description = $data->description;
        
            //create user
            if($works->create()) {
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
            //error because no id
            if(!isset($id)) {
                http_response_code(510); //not extended
                $result = array("message" => "kunde inte uppdatera kursen eftersom inget id skickades med");
            } else {
                $data = json_decode(file_get_contents("php://input"));

                //send data to props in class "works"
                $works->userId = $data->userId; 
                $works->title = $data->title;
                $works->workplace = $data->workplace;
                $works->startDate = $data->startDate;
                $works->endDate = $data->endDate;
                $works->buzzwords = $data->buzzwords;
                $works->description = $data->description;

                //update 
                if($works->update($id)) {
                    http_response_code(200); //ok
                    $result = array("message" => "poesten är uppdaterad");
                } else {
                    http_response_code(503); //server error
                    $result = array("message" => "det gick inte att uppdatera posten");
                }
            }
            break;
            case 'DELETE':
                //error because no id
                if(!isset($id)) {
                    http_response_code(510); 
                    $result = array("message" => "Det gick tyvärr inte att radera");
                } else {
                    //delete user with a specific id
                    if($works->delete($id)) {
                        http_response_code(200); //ok
                        $result = array("message" => "Raderad");
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