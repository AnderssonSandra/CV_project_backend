<?php
class Info {
    //database connection
    private $conn;

    //props
    public $id;
    public $phone;
    public $name;
    public $lastname;
    public $email;
    public $linkedin;
    public $introduction;
    public $description;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all infos from database
    function getAll() {
        $query = "SELECT * FROM cv_info";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific info from database
    function getOne($id) {
        $query = "SELECT * FROM cv_info WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create info
    function create() {
        $query = "INSERT INTO cv_info 
        SET 
        name = :name,
        lastname = :lastname,
        email = :email,
        phone = :phone,
        linkedin = :linkedin,
        introduction = :introduction,
        description = :description
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->linkedin=htmlspecialchars(strip_tags($this->linkedin));
        $this->introduction=htmlspecialchars(strip_tags($this->introduction));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values        
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":lastname", $this->lastname);
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":phone", $this->phone);
        $statement->bindParam(":linkedin", $this->linkedin);
        $statement->bindParam(":introduction", $this->introduction);
       $statement->bindParam(":description", $this->description);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //update info
    function update($id) {
        $query= "UPDATE cv_info
        SET 
        name = :name,
        lastname = :lastname,
        email = :email,
        phone = :phone,
        linkedin = :linkedin,
        introduction = :introduction,
        description = :description
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->linkedin=htmlspecialchars(strip_tags($this->linkedin));
        $this->introduction=htmlspecialchars(strip_tags($this->introduction));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":lastname", $this->lastname);
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":phone", $this->phone);
        $statement->bindParam(":linkedin", $this->linkedin);
        $statement->bindParam(":introduction", $this->introduction);
        $statement->bindParam(":description", $this->description);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    //delete a specific info
    function delete($id) {
        $query= "DELETE FROM cv_info WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>