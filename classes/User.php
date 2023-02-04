<?php

/**
 * User who can use Budget Manager webside after registration
 */

 class User {

    /**
     * User ID
     * @var integer
     */
    public $id;

    /**
     * User name
     * @var string
     */
    public $name;

    /**
     * User email
     * @var string
     */
    public $email;

    /**
     * User password
     * @var string
     */
    public $password;

    /**
     * Error messages while registration 
     * @var array 
     */
    public $errors = [];
 
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }




    public function validateRegistration() {
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }
        elseif ($this->name != '') {
            $this->name = $this->test_input($this->name);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$this->name)) {
            $this->errors[] = "Only letters and white space allowed";
        }
        }
        if ($this->email == '') {
            $this->errors[] = 'Email is required';
        }
        elseif ($this->email != '') {
            $this->email = $this->test_input($this->email);
            $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format";
            }
        }

        if ($this->password == '') {
            $this->errors[] = 'Password is required';
        }
        elseif ($this->password !='') {
            $uppercase = preg_match('@[A-Z]@', $this->password);
            $lowercase = preg_match('@[a-z]@', $this->password);
            $number    = preg_match('@[0-9]@', $this->password);
            $specialChars = preg_match('@[^\w]@', $this->password);
            
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($this->password) < 10 ) {
                $this->errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character. Length at least 10 characters';
            }
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function validateLogin($nameOrEmail) {
        if ($nameOrEmail == '') {
            $this->errors[] = 'Name or email is required';
        }
        
        if ($this->password == '') {
            $this->errors[] = 'Password is required';
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function identifyUserInDatabase($dbConnection, $nameOrEmail) {
        $sql = "SELECT *
                FROM user
                WHERE name = :name OR email = :email";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':name', $nameOrEmail, PDO::PARAM_STR);
        $stmt->bindValue(':email', $nameOrEmail, PDO::PARAM_STR);
        $stmt->execute();
    //    $stmt->setFetchMode(PDO::FETCH_CLASS,'User');
        if ($userDataInArray = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return password_verify($this->password,$userDataInArray["password"]);
        }
        return false;
    }


    public function insertUserIntoDatabase($dbConnection) {
        $sql = "INSERT INTO user (name, email, password)
                VALUES (:name, :email, :password)";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function checkIfUserExistInDatabase($dbConnection) {
        $sql = "SELECT name 
                FROM user
                WHERE name = :name";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->execute();
        $userFromDatabase = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($userFromDatabase) && empty($userFromDatabase)) {
            return false;
        }
        
        $this->errors[] = "Provided username exist - please use another one";
        return true;
     //   var_dump(isset($userFromDatabase));
    //    exit;
    }

}