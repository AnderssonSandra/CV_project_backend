<?php
class Users {
    //database connection
    private $conn;

    //props
    public $id;
    public $username;
    public $password;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all users from database
    function getAll() {
        $query = "SELECT * FROM cv_user";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific user from database
    function getOne($id) {
        $query = "SELECT * FROM cv_user WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create user
    function create() {
        $query = "INSERT INTO cv_user 
        SET 
        username = :username,
        password = :password
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));

        //bind values
        $statement->bindParam(":username", $this->username);

        //hash password and bind values
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $statement->bindParam(':password', $password_hash);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //update user
    function update($id) {
        $query= "UPDATE cv_user
        SET 
        username=:username,
        password=:password
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));

        //bind values 
        $statement->bindParam(":username", $this->username);
        
        //hash password and bind values
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $statement->bindParam(':password', $password_hash);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    } 

    //delete a specific user
    function delete($id) {
        $query= "DELETE FROM cv_user WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>