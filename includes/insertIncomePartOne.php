<?php
require 'autoloader.php';
session_start();

$_SESSION['amountIncome'] = Validation::test_input($_POST['amount']);
$_SESSION['dateIncome'] = Validation::test_input($_POST['date']);