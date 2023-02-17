<?php

class Income {
    public $amount;
    public $date;
    public $category;
    public $comment;
    public $userId;

    function __construct($amount,$date,$category,$comment,$userId) {
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
        $this->comment = $comment;
        $this->userId = $userId;
    }



    public function insertAmountIntoDatabase($dbConnection) {
        $sql = "INSERT INTO income (amount, date, category, comment, user_id)
                VALUES (:amount, :date, :category, :comment, :user_id)";
        
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
        if ($this->comment == '') {
            $stmt->bindValue(':comment', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
        }
        $stmt->bindValue(':user_id', $this->userId, PDO::PARAM_INT);
        
        $stmt->execute();
    }

}