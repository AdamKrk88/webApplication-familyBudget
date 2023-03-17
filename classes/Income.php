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

    public static function getCategories($dbConnection, $user_id) {
        $sql = "SELECT category_income.*
                FROM category_income
                WHERE user_id = :user_id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $sql = "SELECT income.amount, income.date, income.category
                FROM income
                WHERE user_id = :user_id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $categoryAndAmountsArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categoryKeyTotalAmountValue = [];
        $categoryTotalAmountValue = [];

        if (!$startDateFromModal && !$endDateFromModal) {
            foreach($categoryAndAmountsArray as $singleExpense) {
        
                if (call_user_func("$class::$method","{$singleExpense['date']}")) {
                    if(array_key_exists($singleExpense['category'], $categoryKeyTotalAmountValue)) {
                        $categoryKeyTotalAmountValue[$singleExpense['category']] = round((double)$categoryKeyTotalAmountValue[$singleExpense['category']] + (double)$singleExpense['amount'],2);
                    }
                    else {
                        $categoryKeyTotalAmountValue[$singleExpense['category']] = (double)$singleExpense['amount'];
                    }
                }

            }
        }
        else {
            foreach($categoryAndAmountsArray as $singleExpense) {
        
                if (call_user_func("$class::$method","{$singleExpense['date']}", $startDateFromModal, $endDateFromModal)) {
                    if(array_key_exists($singleExpense['category'], $categoryKeyTotalAmountValue)) {
                        $categoryKeyTotalAmountValue[$singleExpense['category']] = round((double)$categoryKeyTotalAmountValue[$singleExpense['category']] + (double)$singleExpense['amount'],2);
                    }
                    else {
                        $categoryKeyTotalAmountValue[$singleExpense['category']] = (double)$singleExpense['amount'];
                    }
                }

            }

        }

        foreach($categoryKeyTotalAmountValue as $key => $value) {
            $categoryTotalAmountValue[] = array($key, $value);
        }

        return $categoryTotalAmountValue;
    }

    public static function getTotalIncome($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $categoryAmountForIncomeSection = self::getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal, $endDateFromModal);
        $totalIncome = 0;

        foreach ($categoryAmountForIncomeSection as $incomePerCategory) {
            $totalIncome = round($totalIncome + (double)$incomePerCategory[1], 2);
        }

        return $totalIncome;

    }

}