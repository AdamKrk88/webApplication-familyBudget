<?php
require 'autoloader.php';
session_start();

$_SESSION['amountExpense'] = Validation::test_input($_POST['amount']);
$_SESSION['dateExpense'] = Validation::test_input($_POST['date']);
$_SESSION['paymentExpense'] = Validation::test_input($_POST['payment']);