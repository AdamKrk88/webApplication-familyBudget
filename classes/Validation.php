<?php

class Validation {

    public static function test_input($data) {
        $data = trim($data);
      //  $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

      public static function incomeExpenseCommentCheck($comment) {
        $error = "";
        if ($comment != '' || strlen($comment) != 0) {
          if (!preg_match("/^[a-z0-9\040\.\-\/]+$/i",$comment)) {    // \x5C backslash
            $error = "Only letters, numbers, space, forward slash, period and dash allowed in the comment";
            return $error;
          }
        }
        return $error;       
      }

      public static function checkIfCategoryExistInDatabase($dbConnection, $category, $expenseOrIncome) {
        $sql = "SELECT category 
                FROM category_$expenseOrIncome
                WHERE category = :category";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $categoryFromDatabase = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($categoryFromDatabase) && empty($categoryFromDatabase)) {
            return false;
        }
        
        return true;
    }

    public static function checkIfPaymentExistInDatabase($dbConnection, $payment) {
      $sql = "SELECT payment 
              FROM payment_expense
              WHERE payment = :payment";
      $stmt = $dbConnection->prepare($sql);
      $stmt->bindValue(':payment', $payment, PDO::PARAM_STR);
      $stmt->execute();
      $paymentFromDatabase = $stmt->fetch(PDO::FETCH_ASSOC);
      if (isset($paymentFromDatabase) && empty($paymentFromDatabase)) {
          return false;
      }
      
      return true;
  }

  public static function checkIfExpenseIncomeIdExistInDatabase($dbConnection, $id, $user_id, $expenseOrIncome) {
    $sql = "SELECT id 
            FROM $expenseOrIncome
            WHERE id = :id AND user_id = :user_id";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $expenseFromDatabase = $stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($expenseFromDatabase) && empty($expenseFromDatabase)) {
        return false;
    }
    
    return true;
  }

    public static function checkIfNumberOfCategoriesIsAboveThreshold($dbConnection, $expenseOrIncome) {
      $sql = "SELECT COUNT(*) as amount_categories 
              FROM category_{$expenseOrIncome}";
      $stmt = $dbConnection->prepare($sql);
      $stmt->execute();

      $numberOfCategoriesArray = $stmt->fetch(PDO::FETCH_ASSOC);
      $numberOfCategories = (int)$numberOfCategoriesArray['amount_categories'];
      
      if ($numberOfCategories < 18) {
        return false;
      }
      else {
        return true;
      }
    }

    public static function checkIfNumberOfPaymentOptionsIsAboveThreshold($dbConnection) {
      $sql = "SELECT COUNT(*) as amount_payments 
              FROM payment_expense";
      $stmt = $dbConnection->prepare($sql);
      $stmt->execute();

      $numberOfPaymentsArray = $stmt->fetch(PDO::FETCH_ASSOC);
      $numberOfPayments = (int)$numberOfPaymentsArray['amount_payments'];
      
      if ($numberOfPayments < 5) {
        return false;
      }
      else {
        return true;
      }
    }

      public static function validateCategory($dbConnection, $category, $expenseOrIncome) {
        $errors = [];
        $category = Validation::test_input($category);
        if ($category == '') {
          $errors[] = 'Provide category to add';
        }
        elseif ($category != '') {
          if (!preg_match("/^([a-zA-Z]+)* ?[a-zA-Z]+$/",$category)) {
            $errors[] = "Only letters and one white space allowed";
          }
          elseif (strlen($category) > 20) {
            $errors[] = "Up to 20 characters is allowed";
          }
          else {
            $category = strtolower($category);
            $category = ucfirst($category);
            if (self::checkIfCategoryExistInDatabase($dbConnection, $category, $expenseOrIncome)) {
              $errors[] = "Error. Category exists";
            }
            elseif (self::checkIfNumberOfCategoriesIsAboveThreshold($dbConnection, $expenseOrIncome)) {
              $errors[] = "Error. Maximum number of categories: 18";
            }
          }
      }

      $result = array($category, $errors);
      return $result;
      }

      public static function validatePayment($dbConnection, $payment) {
        $errors = [];
        $payment = Validation::test_input($payment);
        if ($payment == '') {
          $errors[] = 'Provide payment option to add';
        }
        elseif ($payment != '') {
          if (!preg_match("/^([a-zA-Z]+)* ?[a-zA-Z]+$/",$payment)) {
            $errors[] = "Only letters and one white space allowed";
          }
          elseif (strlen($payment) > 15) {
            $errors[] = "Up to 15 characters is allowed";
          }
          else {
            $payment = strtolower($payment);
            $payment = ucfirst($payment);
            if (self::checkIfPaymentExistInDatabase($dbConnection, $payment)) {
              $errors[] = "Error. Payment option exists";
            }
            elseif (self::checkIfNumberOfPaymentOptionsIsAboveThreshold($dbConnection)) {
              $errors[] = "Error. Maximum number of options: 5";
            }
          }
      }

      $result = array($payment, $errors);
      return $result;
      }

      public static function validateId($dbConnection, $id, $user_id, $expenseOrIncome) {
        $errors = [];
        $id = Validation::test_input($id);
        if ($id == '') {
          $errors[] = "Provide id for $expenseOrIncome to be removed";
        }
        elseif ($id != '') {
          if (!preg_match("/^([0-9]+)*[0-9]+$/",$id)) {
            $errors[] = "Only number allowed";
          }
          else {
            if (!self::checkIfExpenseIncomeIdExistInDatabase($dbConnection, $id, $user_id, $expenseOrIncome)) {
              $errors[] = "Error. No $expenseOrIncome with this id";
            }
          }
      }

      $result = array($id, $errors);
      return $result;
      }

      public static function validateIdAndCategory($dbConnection, $id, $user_id, $categoryProvided, $expenseOrIncome) {
        $resultForIdValidation = self::validateId($dbConnection, $id, $user_id, $expenseOrIncome);
        $idValidated = $resultForIdValidation[0];
        $errors = $resultForIdValidation[1];
        if (empty($errors)) {

          $sql = "SELECT category 
                  FROM $expenseOrIncome
                  WHERE id = :id";
          $stmt = $dbConnection->prepare($sql);
          $stmt->bindValue(':id', $idValidated, PDO::PARAM_INT);
          $stmt->execute();
          $currentCategory = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($currentCategory["category"] === $categoryProvided) {
            $errors[] = "Current category. No change";
          } 
        }

      $result = array($idValidated, $errors);
      return $result;
      }

      public static function validateComment($comment) {
        $errors = [];
        $comment = Validation::test_input($comment);
        
        if ($comment == '') {
          $comment = NULL;
        }
        elseif ($comment != '') {
          if (!preg_match("/^[a-z0-9\040\.\-\/]+$/i",$comment)) {
            $errors[] = "Comment invalid";
          }
          elseif (strlen($comment) > 25) {
            $errors[] = "Up to 25 characters is allowed";
          }
      }

      $result = array($comment, $errors);
      return $result;
      }

}