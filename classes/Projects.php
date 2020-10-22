<?php
class Projects {
    //database connection
    private $conn;

    //props
    public $id;
    public $userId;
    public $name;
    public $link;
    public $techniques;
    public $startDate;
    public $endDate;
    public $description;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all projects from database
    function getAll() {
        $query = "SELECT * FROM cv_project";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific project from database
    function getOne($id) {
        $query = "SELECT * FROM cv_project WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create project
    function create() {
        $query = "INSERT INTO cv_project 
        SET 
        userId = :userId,
        name = :name,
        link = :link,
        techniques = :techniques,
        startDate = :startDate,
        endDate = :endDate,
        description = :description
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->userId=htmlspecialchars(strip_tags($this->userId));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->techniques=htmlspecialchars(strip_tags($this->techniques));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values
        $statement->bindParam(":userId", $this->userId);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":link", $this->link);
        $statement->bindParam(":techniques", $this->techniques);
        $statement->bindParam(":startDate", $this->startDate);
        $statement->bindParam(":endDate", $this->endDate);
        $statement->bindParam(":description", $this->description);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //update project
    function update($id) {
        $query= "UPDATE cv_project
        SET 
        userId = :userId,
        name = :name,
        link = :link,
        techniques = :techniques,
        startDate = :startDate,
        endDate = :endDate,
        description = :description
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->userId=htmlspecialchars(strip_tags($this->userId));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->techniques=htmlspecialchars(strip_tags($this->techniques));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
        $statement->bindParam(":userId", $this->userId);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":link", $this->link);
        $statement->bindParam(":techniques", $this->techniques);
        $statement->bindParam(":startDate", $this->startDate);
        $statement->bindParam(":endDate", $this->endDate);
        $statement->bindParam(":description", $this->description);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    //delete a specific project
    function delete($id) {
        $query= "DELETE FROM cv_project WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>