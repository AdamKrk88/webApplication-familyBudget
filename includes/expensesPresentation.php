<?php
require 'autoloader.php';
session_start();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();
$categoryTotalAmountValue = Expense::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
echo json_encode($categoryTotalAmountValue);