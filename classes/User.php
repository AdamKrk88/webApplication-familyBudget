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
        $this->name = Validation::test_input($this->name);
        $this->email = Validation::test_input($this->email);
        $this->password = trim($this->password);
        
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }
        elseif ($this->name != '') {
            if (!preg_match("/^([a-zA-Z]+)* ?[a-zA-Z]+$/",$this->name)) {
                $this->errors[] = "Only letters and one white space allowed";
            }
        }
        if ($this->email == '') {
            $this->errors[] = 'Email is required';
        }
        elseif ($this->email != '') {
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
        $nameOrEmail = Validation::test_input($nameOrEmail);
        $this->password = trim($this->password);
        
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
        if ($userDataInArray = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify($this->password,$userDataInArray["password"])) {
                return $userDataInArray;
            }
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
        $sql = "SELECT name, email 
                FROM user
                WHERE name = :name OR email = :email";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $userFromDatabase = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($userFromDatabase) && empty($userFromDatabase)) {
            return false;
        }
        
        $this->errors[] = "Provided user exist - please use another one. Unique name and unique email required";
        return true;
    }

    public function validateName() {
        $this->name = Validation::test_input($this->name);
        if ($this->name == '') {
            $this->errors[] = 'Provide new username';
        }
        elseif ($this->name != '' || strlen($this->name) != 0) {
            if (!preg_match("/^[a-zA-Z-' ]*$/",$this->name)) {
                $this->errors[] = "Only letters and white space allowed";
            }
            elseif (strlen($this->name) > 15) {
                $this->errors[] = "Up to 15 characters is allowed";
            }
        }
        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function validateEmail() {
        $this->email = Validation::test_input($this->email);
        if ($this->email == '') {
            $this->errors[] = 'Provide new email';
        }
        elseif ($this->email != '') {
            $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Invalid email format";
            }
        }
        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function validatePassword() {
        $this->password = trim($this->password);
        if ($this->password == '') {
            $this->errors[] = 'Provide new password';
        }
        elseif ($this->password !='') {
            $uppercase = preg_match('@[A-Z]@', $this->password);
            $lowercase = preg_match('@[a-z]@', $this->password);
            $number    = preg_match('@[0-9]@', $this->password);
            $specialChars = preg_match('@[^\w]@', $this->password);
            
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($this->password) < 10 ) {
                $this->errors[] = 'Password invalid';
            }
        }
        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

}