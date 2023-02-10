<?php

class Income {
    public $amount;
    public $date;
    public $category;
    public $comment;

    function __construct($amount,$date,$category,$comment) {
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
        $this->comment = $comment;
    }



    public function insertAmountIntoDatabase($dbConnection) {
        $sql = "INSERT INTO income (amount, date, category, comment)
                VALUES (:amount, :date, :category, :comment)";
        
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
        if ($this->comment == '') {
            $stmt->bindValue(':comment', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
        }
        
        $stmt->execute();
    }

}