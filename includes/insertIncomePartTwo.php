<?php
require 'autoloader.php';
session_start();
$_SESSION['category'] = Validation::test_input($_POST['category']);
$_SESSION['comment'] = Validation::test_input($_POST['comment']);
//$_SESSION['comment'] = $_POST['comment'];

$error = Validation::incomeExpenseCommentCheck($_SESSION['comment']);
//var_dump($_SESSION['comment']);
//var_dump($error);
if(!$error) {
    $income = new Income($_SESSION['amount'], $_SESSION['date'], $_SESSION['category'], $_SESSION['comment'], $_SESSION['userId']);

    $database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
    $connection = $database->getConnectionToDatabase();

    $income->insertAmountIntoDatabase($connection);
}
else {
    echo json_encode($error);
}