<?php
require 'autoloader.php';
session_start();
$_SESSION['categoryExpense'] = Validation::test_input($_POST['category']);
$_SESSION['commentExpense'] = Validation::test_input($_POST['comment']);

$error = Validation::incomeExpenseCommentCheck($_SESSION['commentExpense']);
if(!$error) {
    $expense = new Expense($_SESSION['amountExpense'], $_SESSION['dateExpense'], $_SESSION['paymentExpense'], $_SESSION['categoryExpense'], $_SESSION['commentExpense'], $_SESSION['userId']);

    $database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
    $connection = $database->getConnectionToDatabase();

    $expense->insertAmountIntoDatabase($connection);
}
else {
    echo json_encode($error);
}