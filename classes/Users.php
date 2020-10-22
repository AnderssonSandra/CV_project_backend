<?php
class Users {
    //database connection
    private $conn;

    //props
    public $id;
    public $email;
    public $phone;
    public $password;
    public $description;

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
        email = :email,
        phone = :phone,
        password = :password,
        description = :description
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":phone", $this->phone);
        $statement->bindParam(":password", $this->password);
        $statement->bindParam(":description", $this->description);

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
        email=:email,
        phone=:phone,
        password=:password,
        description=:description
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":phone", $this->phone);
        $statement->bindParam(":password", $this->password);
        $statement->bindParam(":description", $this->description);

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