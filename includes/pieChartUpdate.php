<?php
require 'autoloader.php';
session_start();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();
$dataToUpdatePieChart =[];
$incomeCategories = [];
$percentagePerCategory = [];
$singlePercentage = 0;
$backgroundColorForPieChart = [];

if ($_GET['expenseOrIncome'] == "Income") {
    if ($_GET['isModal']) {
        $categoryTotalAmountValue = Income::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
        $incomeExpense = Income::getTotalIncome($connection, $_SESSION['userId'], "Date", $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
    }
    else {
        $categoryTotalAmountValue = Income::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod']);
        $incomeExpense = Income::getTotalIncome($connection, $_SESSION['userId'], "Date", $_GET['timePeriod']);
    }

    $categoryTotalAmountValueLength = count($categoryTotalAmountValue);
    
}
elseif ($_GET['expenseOrIncome'] == "Expense") {
    if ($_GET['isModal']) {
        $categoryTotalAmountValue = Expense::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
        $incomeExpense = Expense::getTotalExpense($connection, $_SESSION['userId'], "Date", $_GET['timePeriod'], $_GET['startDateFromModal'], $_GET['endDateFromModal']);
    }
    else {
        $categoryTotalAmountValue = Expense::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", $_GET['timePeriod']);
        $incomeExpense = Expense::getTotalExpense($connection, $_SESSION['userId'], "Date", $_GET['timePeriod']);
    }

    $categoryTotalAmountValueLength = count($categoryTotalAmountValue);
    
}

for ($i = 0; $i < $categoryTotalAmountValueLength; $i++) {
    $incomeCategories[$i] = $categoryTotalAmountValue[$i][0];
    $singlePercentage = ((double)$categoryTotalAmountValue[$i][1]) / $incomeExpense;
    $percentagePerCategory[$i] = round($singlePercentage * 100, 2);

    switch ($i) {
        case 0:
            $backgroundColorForPieChart[$i] = "#ffccff";
            break;
        case 1:
            $backgroundColorForPieChart[$i] = "#bf80ff";
            break;
        case 2:
            $backgroundColorForPieChart[$i] = "#ff80ff";
            break;
        case 3:
            $backgroundColorForPieChart[$i] = "#df9fbf";
            break;
        case 4:
            $backgroundColorForPieChart[$i] = "#ff80bf";
            break;
        case 5:
            $backgroundColorForPieChart[$i] = "#ff80aa";
            break;
        case 6:
            $backgroundColorForPieChart[$i] = "#df9f9f";
            break;
        case 7:
            $backgroundColorForPieChart[$i] = "#ff8080";
            break;
        case 8:
            $backgroundColorForPieChart[$i] = "#ffbf80";
            break;
        case 9:
            $backgroundColorForPieChart[$i] = "#ffdf80";
            break;
        case 10:
            $backgroundColorForPieChart[$i] = "#dfff80";
            break;
        case 11:
            $backgroundColorForPieChart[$i] = "#80ff80";
            break;
        case 12:
            $backgroundColorForPieChart[$i] = "#80ffe5";
            break;
        case 13:
            $backgroundColorForPieChart[$i] = "#80ccff";
            break;
        case 14:
            $backgroundColorForPieChart[$i] = "#8080ff";
            break;
        case 15:
            $backgroundColorForPieChart[$i] = "#b3b3cc";
            break;
        case 16:
            $backgroundColorForPieChart[$i] = "#9fbfdf";
            break;
        case 17:
            $backgroundColorForPieChart[$i] = "#80bfff";
            break;
    }
}

$dataToUpdatePieChart['incomeCategories'] = $incomeCategories;
$dataToUpdatePieChart['percentagePerCategory'] = $percentagePerCategory;
$dataToUpdatePieChart['backgroundColorForPieChart'] = $backgroundColorForPieChart;

echo json_encode($dataToUpdatePieChart);