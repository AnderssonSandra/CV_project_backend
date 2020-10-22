<?php
class Works {
    //database connection
    private $conn;

    //props
    public $id;
    public $userId;
    public $title;
    public $workplace;
    public $startDate;
    public $endDate;
    public $buzzwords;
    public $description;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all projects from database
    function getAll() {
        $query = "SELECT * FROM cv_work";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific project from database
    function getOne($id) {
        $query = "SELECT * FROM cv_work WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create project
    function create() {
        $query = "INSERT INTO cv_work 
        SET 
        userId = :userId,
        title = :title,
        workplace = :workplace,
        startDate = :startDate,
        endDate = :endDate,
        buzzwords = :buzzwords,
        description = :description
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->userId=htmlspecialchars(strip_tags($this->userId));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->workplace=htmlspecialchars(strip_tags($this->workplace));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->buzzwords=htmlspecialchars(strip_tags($this->buzzwords));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values
        $statement->bindParam(":userId", $this->userId);
        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":workplace", $this->workplace);
        $statement->bindParam(":startDate", $this->startDate);
        $statement->bindParam(":endDate", $this->endDate);
        $statement->bindParam(":buzzwords", $this->buzzwords);
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
        $query= "UPDATE cv_work
        SET 
        userId = :userId,
        title = :title,
        workplace = :workplace,
        startDate = :startDate,
        endDate = :endDate,
        buzzwords = :buzzwords,
        description = :description
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->userId=htmlspecialchars(strip_tags($this->userId));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->workplace=htmlspecialchars(strip_tags($this->workplace));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->buzzwords=htmlspecialchars(strip_tags($this->buzzwords));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
        $statement->bindParam(":userId", $this->userId);
        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":workplace", $this->workplace);
        $statement->bindParam(":startDate", $this->startDate);
        $statement->bindParam(":endDate", $this->endDate);
        $statement->bindParam(":buzzwords", $this->buzzwords);
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
        $query= "DELETE FROM cv_work WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>