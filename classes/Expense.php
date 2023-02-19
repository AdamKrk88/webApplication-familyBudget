<?php

class Expense {
    public $amount;
    public $date;
    public $payment;
    public $category;
    public $comment;
    public $userId;

    function __construct($amount,$date,$payment,$category,$comment,$userId) {
        $this->amount = $amount;
        $this->date = $date;
        $this->payment = $payment;
        $this->category = $category;
        $this->comment = $comment;
        $this->userId = $userId;
    }



    public function insertAmountIntoDatabase($dbConnection) {
        $sql = "INSERT INTO expense (amount, date, payment, category, comment, user_id)
                VALUES (:amount, :date, :payment, :category, :comment, :user_id)";
        
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':payment', $this->payment, PDO::PARAM_STR);
        $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
        if ($this->comment == '') {
            $stmt->bindValue(':comment', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
        }
        $stmt->bindValue(':user_id', $this->userId, PDO::PARAM_INT);
        
        $stmt->execute();
    }

    public static function getCategories($dbConnection, $user_id) {
        $sql = "SELECT category_expense.*
                FROM category_expense
                WHERE user_id = :user_id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPayments($dbConnection, $user_id) {
        $sql = "SELECT payment_expense.*
        FROM payment_expense
        WHERE user_id = :user_id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}