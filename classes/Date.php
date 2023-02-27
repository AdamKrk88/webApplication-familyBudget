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

        $firstDayOfPreviousMonth = date('Y-m-01', strtotime('-1 month'));
        
        $date = new DateTime($firstDayOfPreviousMonth);
        $lastDayOfPreviousMonth = $date->format('Y-m-t'); 
        
        if ($firstDayOfPreviousMonth <= $dateFromDatabase && $lastDayOfPreviousMonth >= $dateFromDatabase) {
            return true;
        }
        else {
            return false;
        } 
    }

}