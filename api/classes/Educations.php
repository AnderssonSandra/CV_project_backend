<?php
class Educations {
    //database connection
    private $conn;

    //props
    public $id;
    public $education;
    public $school;
    public $startDate;
    public $endDate;
    public $description;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get all educations from database
    function getAll() {
        $query = "SELECT * FROM cv_education ORDER BY CASE WHEN endDate = '0000-00-00' THEN 0 ELSE 1 END, endDate DESC";

        //prepare and execute statement
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //get one specific education from database
    function getOne($id) {
        $query = "SELECT * FROM cv_education WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    //create education
    function create() {
        $query = "INSERT INTO cv_education 
        SET 
        education = :education,
        school = :school,
        startDate = :startDate,
        endDate = :endDate,
        description = :description
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->education=htmlspecialchars(strip_tags($this->education));
        $this->school=htmlspecialchars(strip_tags($this->school));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values
        $statement->bindParam(":education", $this->education);
        $statement->bindParam(":school", $this->school);
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

    //update education
    function update($id) {
        $query= "UPDATE cv_education
        SET 
        education = :education,
        school = :school,
        startDate = :startDate,
        endDate = :endDate,
        description = :description
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->education=htmlspecialchars(strip_tags($this->education));
        $this->school=htmlspecialchars(strip_tags($this->school));
        $this->startDate=htmlspecialchars(strip_tags($this->startDate));
        $this->endDate=htmlspecialchars(strip_tags($this->endDate));
        $this->description=htmlspecialchars(strip_tags($this->description));

        //bind values 
        $statement->bindParam(":education", $this->education);
        $statement->bindParam(":school", $this->school);
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

    //delete a specific education
    function delete($id) {
        $query= "DELETE FROM cv_education WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }
}
?>