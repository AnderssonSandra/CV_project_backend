<?php
//class Database
class Database {
    //database settings localhost 
    private $host = 'localhost';
    private $db_name = 'cv_project';
    private $username = 'sandra';
    private $password = 'cv_database_sandra';
    private $conn;
    

    /*database settings miun.se 
    private $host = 'studentmysql.miun.se';
    private $db_name = 'saan1906';
    private $username = 'saan1906';
    private $password = '97xgd5u6';
    private $conn;
    */
    
    //connect to database
    public function connect() {
        //close connections
        $this->conn = null;
        //connect with PDO
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Connection error
        } catch(PDOExcetion $e) {
            echo "Connection Error" . $e->getMessage();
        }
        //return
        return $this->conn;
    }
    //close connection to database
    public function close() {
        $this->conn = null;
    }
}
?>