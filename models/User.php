<?php
class User {
    private $conn;
    private $table = "users";
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $created_at;
    public $modified_at;

    public function __construct($db){
        $this->conn = $db;

        
    }
    public function register(){
        $query = 'INSERT INTO '
        .$this->table.' 
        SET 
        firstname = :firstname, 
        lastname = :lastname,
        email = :email,
        password = :password';

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);


        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()){
            return true;
        }
        printf('Error: %s.\n',$stmt->error);
        return false;
    }
    public function verifyEmail(){
        $query = 'SELECT 
        id,
        firstname,
        lastname,
        password 
        FROM 
        '.$this->table.'
         WHERE email = ? 
        LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);
        
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num > 0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];

            return true;
        }
        return false;
    }
    public function update(){
        $set_password = !empty($this->password) ? 'password = :password': '';
        $query = 'UPDATE '. $this->table .' SET firstname = :firstname, lastname = :lastname,
 email = :email, '. $set_password .' WHERE id = :id';
 
        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        if(!empty($this->password)){
            $this->password = htmlspecialchars(strip_tags($this->password));
        }

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        
        if(!empty($this->password)){
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
            
        }
       

        if($stmt->execute()){
            return true;
        }
         printf('Error: %s.\n',$stmt->error);
        return false;
    
    }
}
