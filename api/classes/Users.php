<?php
class Users {
    //database connection
    private $conn;

    //props
    public $id;
    public $firstname;
    public $lastname;
    public $email;
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
        email = :email,
        firstname = :firstname,
        lastname = :lastname,
        password = :password
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->password=htmlspecialchars(strip_tags($this->password));

        //bind values
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":firstname", $this->firstname);
        $statement->bindParam(":lastname", $this->lastname);

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
    //function update($id) {
        // update a user record
    public function update(){
 
        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";
    
        // if no posted password, do not update the password
        $query = "UPDATE cv_user
                SET
                    email = :email,
                    firstname = :firstname,
                    lastname = :lastname
                    {$password_set}
                WHERE id = :id";
    
            // prepare the query
            $statement = $this->conn->prepare($query);
    
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->email=htmlspecialchars(strip_tags($this->email));
        
            // bind the values from the form
            $statement->bindParam(':firstname', $this->firstname);
            $statement->bindParam(':lastname', $this->lastname);
            $statement->bindParam(':email', $this->email);
    
            // hash the password before saving to database
            if(!empty($this->password)){
                $this->password=htmlspecialchars(strip_tags($this->password));
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                $statement->bindParam(':password', $password_hash);
            }
        
            // unique ID of record to be edited
            $statement->bindParam(':id', $this->id);
        
            // execute the query
            if($statement->execute()){
                return true;
            }
        
        return false;
    }
        /*$query= "UPDATE cv_user
        SET 
        email=:email,
        firstname=:firstname,
        lastname=:lastname,
        password=:password
        WHERE
        id = $id
        ";

        //prepare query
        $statement= $this->conn->prepare($query);

        //sanitaze from tags 
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->password=htmlspecialchars(strip_tags($this->password));

        //bind values 
        $statement->bindParam(":email", $this->email);
        $statement->bindParam(":firstname", $this->firstname);
        $statement->bindParam(":lastname", $this->lastname);
        $statement->bindParam(":password", $this->password);

        //execute query
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }*/ 

    //delete a specific user
    function delete($id) {
        $query= "DELETE FROM cv_user WHERE id = $id";
        $statement = $this->conn->prepare($query);
        $statement ->execute();
        return $statement;
    }

    // check if given email exist in the database
    function emailExists(){
 
        // query to check if email exists
        $query = "SELECT id, firstname, lastname, password
                FROM cv_user
                WHERE email = ?
                LIMIT 0,1";
    
        // prepare the query
        $statement = $this->conn->prepare( $query );
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind given email value
        $statement->bindParam(1, $this->email);
    
        // execute the query
        $statement->execute();
    
        // get number of rows
        $num = $statement->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $statement->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
    
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
    }
}
?>