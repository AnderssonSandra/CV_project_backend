<?php
class Works {
    //database connection
    private $conn;

    //props
    public $id;
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

    //get all work from database
    function getAll() {
        $query = "SELECT * FROM cv_work ORDER BY CASE WHEN endDate IS NULL THEN 0 ELSE 1 END, endDate DESC";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific work from database
    function getOne($id) {
        $query = "SELECT * FROM cv_work WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create work
    function create() {
        $query = "INSERT INTO cv_work 
        SET 
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
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->workplace=htmlspecialchars(strip_tags($this->workplace));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        if($this->endDate == null) {
            $this->endDate;
        } else {
            $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        };
        $this->buzzwords=htmlspecialchars(strip_tags($this->buzzwords));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values
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

    //update work
    function update($id) {
        $query= "UPDATE cv_work
        SET 
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
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->workplace=htmlspecialchars(strip_tags($this->workplace));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        if($this->endDate == null) {
            $this->endDate;
        } else {
            $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        };
        $this->buzzwords=htmlspecialchars(strip_tags($this->buzzwords));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
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

    //delete a specific work
    function delete($id) {
        $query= "DELETE FROM cv_work WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>