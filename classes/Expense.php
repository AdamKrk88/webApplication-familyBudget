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

    public static function getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $sql = "SELECT expense.amount, expense.date, expense.category
                FROM expense
                WHERE user_id = :user_id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $categoryAndAmountsArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categoryKeyTotalAmountValue = [];
        $categoryTotalAmountValue = [];

        if (!$startDateFromModal && !$endDateFromModal) {
            foreach($categoryAndAmountsArray as $singleExpense) {
            // $tra = $singleExpense['date'];
    
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
                // $tra = $singleExpense['date'];
        
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

/*
        foreach($categoryAndAmountsArray as $singleExpense) {
            if (Date::isCurrentMonthDate($singleExpense['date'])) {
                if(array_key_exists($singleExpense['category'], $categoryKeyTotalAmountValue)) {
                    $categoryKeyTotalAmountValue[$singleExpense['category']] = (double)$categoryKeyTotalAmountValue[$singleExpense['category']] + (double)$singleExpense['amount'];
                }
                else {
                    $categoryKeyTotalAmountValue[$singleExpense['category']] = (double)$singleExpense['amount'];
                }
            }

        }
*/
        foreach($categoryKeyTotalAmountValue as $key => $value) {
            $categoryTotalAmountValue[] = array($key, $value);
        }

        return $categoryTotalAmountValue;
    }

    public static function getTotalExpense($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $categoryAmountForExpenseSection = self::getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal, $endDateFromModal);
        $totalExpense = 0;

        foreach ($categoryAmountForExpenseSection as $expensePerCategory) {
            $totalExpense = round($totalExpense + (double)$expensePerCategory[1], 2);
        }

        return $totalExpense;

    }


    public static function getOnePageOfList($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $sql = "SELECT *
                FROM expense
                WHERE user_id = :user_id
                ORDER BY id";

        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  //      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  //      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
       
        $expenseTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $expenseTableForGivenPeriod = [];

    //    foreach($expenseTable as $singleExpense) {
     //   if (call_user_func("$class::$method","{$singleExpense['date']}"))    
     
        if (!$startDateFromModal && !$endDateFromModal) {
            for ($i = 0; $i < count($expenseTable); $i++) {
                if (call_user_func("$class::$method",$expenseTable[$i]['date'])) {
                    $expenseTableForGivenPeriod[] = array(
                                                        'id' => $expenseTable[$i]['id'], 
                                                        'date' => $expenseTable[$i]['date'],
                                                        'category' => $expenseTable[$i]['category'],
                                                        'payment' => $expenseTable[$i]['payment'],
                                                        'comment' => $expenseTable[$i]['comment'],
                                                        'amount' => $expenseTable[$i]['amount']);             
                }
   
            }
        }
        else {
            for ($i = 0; $i < count($expenseTable); $i++) {
                if (call_user_func("$class::$method",$expenseTable[$i]['date'], $startDateFromModal, $endDateFromModal)) {
                    $expenseTableForGivenPeriod[] = array(
                                                        'id' => $expenseTable[$i]['id'], 
                                                        'date' => $expenseTable[$i]['date'],
                                                        'category' => $expenseTable[$i]['category'],
                                                        'payment' => $expenseTable[$i]['payment'],
                                                        'comment' => $expenseTable[$i]['comment'],
                                                        'amount' => $expenseTable[$i]['amount']);             
                }
            }
        }

        return  $expenseTableForGivenPeriod;
    }

}