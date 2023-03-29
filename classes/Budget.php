<?php

class Budget {


    public static function getTotalBalance($dbConnection, $user_id, $class, $method, $startDateFromModal = '0', $endDateFromModal = '0') {
        $categoryAmountForExpenseSection = Expense::getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal, $endDateFromModal);
        $categoryAmountForIncomeSection = Income::getCategoryAndRelatedAmount($dbConnection, $user_id, $class, $method, $startDateFromModal, $endDateFromModal);
        $totalExpense = 0;
        $totalIncome = 0;
        $balance = 0;

        foreach ($categoryAmountForExpenseSection as $expensePerCategory) {
            $totalExpense = round($totalExpense + (double)$expensePerCategory[1], 2);
        }

        foreach ($categoryAmountForIncomeSection as $incomePerCategory) {
            $totalIncome = round($totalIncome + (double)$incomePerCategory[1], 2);
        }

        $balance = round($totalIncome - $totalExpense, 2);

        return $balance;
       
    }

}