<?php
require 'autoloader.php';
session_start();
$_SESSION['category'] = $_POST['category'];
$_SESSION['comment'] = $_POST['comment'];

$income = new Income($_SESSION['amount'], $_SESSION['date'], $_SESSION['category'], $_SESSION['comment']);

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();

$income->insertAmountIntoDatabase($connection);