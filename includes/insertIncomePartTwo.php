<?php
require 'autoloader.php';
session_start();
$_SESSION['categoryIncome'] = Validation::test_input($_POST['category']);
$_SESSION['commentIncome'] = Validation::test_input($_POST['comment']);
//$_SESSION['comment'] = $_POST['comment'];

$error = Validation::incomeExpenseCommentCheck($_SESSION['commentIncome']);
//var_dump($_SESSION['comment']);
//var_dump($error);
if(!$error) {
    $income = new Income($_SESSION['amountIncome'], $_SESSION['dateIncome'], $_SESSION['categoryIncome'], $_SESSION['commentIncome'], $_SESSION['userId']);

    $database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
    $connection = $database->getConnectionToDatabase();

    $income->insertAmountIntoDatabase($connection);
}
else {
    echo json_encode($error);
}