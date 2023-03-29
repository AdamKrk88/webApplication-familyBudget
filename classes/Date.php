<?php

class Date {

    public static function isCurrentMonthDate($dateFromDatabase) {
        $dateCurrent = date('Y-m-d');
        $date = new DateTime($dateCurrent);
        $lastDayOfCurrentMonth = $date->format('Y-m-t'); 
        $firstDayOfCurrentMonth = date('Y-m-01');
        
        if ($firstDayOfCurrentMonth <= $dateFromDatabase && $lastDayOfCurrentMonth >= $dateFromDatabase) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function isPreviousMonthDate($dateFromDatabase) {
    /*
        $firstDayOfPreviousMonth = date('Y-m-01', strtotime('-1 month'));
        $date = new DateTime($firstDayOfPreviousMonth);
        $lastDayOfPreviousMonth = $date->format('Y-m-t'); 
    */

        $firstDayOfPreviousMonthAsObject = new DateTime("first day of last month");
        $lastDayOfPreviousMonthAsObject = new DateTime("last day of last month");
        $firstDayOfPreviousMonth = $firstDayOfPreviousMonthAsObject->format('Y-m-d');
        $lastDayOfPreviousMonth =   $lastDayOfPreviousMonthAsObject->format('Y-m-d'); 
        
        if ($firstDayOfPreviousMonth <= $dateFromDatabase && $lastDayOfPreviousMonth >= $dateFromDatabase) {
            return true;
        }
        else {
            return false;
        } 
    }

    public static function isCurrentYearDate($dateFromDatabase) {

        $firstDayOfCurrentYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDayOfCurrentYear = date('Y-m-d', strtotime('last day of december this year'));
        
        if ($firstDayOfCurrentYear <= $dateFromDatabase && $lastDayOfCurrentYear >= $dateFromDatabase) {
            return true;
        }
        else {
            return false;
        } 
    }

    public static function isTimePeriodProvidedByUser($dateFromDatabase, $startDateFromModal, $endDateFromModal) {
   
        if ($startDateFromModal <= $dateFromDatabase && $endDateFromModal >= $dateFromDatabase) {
            return true;
        }
        else {
            return false;
        } 
    }

}