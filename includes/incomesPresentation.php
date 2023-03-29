<?php
require 'autoloader.php';
session_start();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();

if (!$_GET['isModal']) {
    $categoryTotalAmountValue = Income::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod']);
}
else {
    $categoryTotalAmountValue = Income::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);      
} 
   
echo json_encode($categoryTotalAmountValue);