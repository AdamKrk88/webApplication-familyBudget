<?php
require 'autoloader.php';
session_start();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();


if ($_GET['expenseOrIncome'] == "Income") {
    if ($_GET['isModal']) {
        $dataToUpdateFirstPage = Income::getOnePageOfList($connection, $_SESSION['userId'], "Date",  $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
    }
    else {
        $dataToUpdateFirstPage = Income::getOnePageOfList($connection, $_SESSION['userId'], "Date",  $_GET['timePeriod']);
    }
}

else if ($_GET['expenseOrIncome'] == "Expense") {
    if ($_GET['isModal']) {
        $dataToUpdateFirstPage = Expense::getOnePageOfList($connection, $_SESSION['userId'], "Date",  $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
    }
    else {
        $dataToUpdateFirstPage = Expense::getOnePageOfList($connection, $_SESSION['userId'], "Date",  $_GET['timePeriod']);
    }
}

echo json_encode($dataToUpdateFirstPage);