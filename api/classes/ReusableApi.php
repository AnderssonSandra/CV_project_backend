<?php
class ReusableApi {
    public $result;

    function getData ($result) {
        if(sizeof($result) > 0) {
            http_response_code(200); //OK 
        } else {
            http_response_code(404); //can´t find data
            $result = array("message" => "Hittade inga kurser");
        } 
    }

    function setHeaders() {
        header('Content-Type: application/json;'); //json data
        header('Access-Control-Allow-Origin: http://studenter.miun.se'); //reach from every domain 
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS'); //allow metods
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With'); //allow headers 
    }
}

?>