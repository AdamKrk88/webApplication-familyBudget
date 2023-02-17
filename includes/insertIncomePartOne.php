<?php
require 'autoloader.php';
session_start();

$_SESSION['amount'] = Validation::test_input($_POST['amount']);
$_SESSION['date'] = Validation::test_input($_POST['date']);