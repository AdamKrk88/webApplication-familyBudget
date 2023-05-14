<?php

class NumberFormatterBudget {
    
    public static function checkIfNumberHasDecimalPart($number) {
        return is_numeric($number) && floor($number) != $number;
    }

    public static function getDecimal($number) {
        if ($number >= 0) {   
            return $number - floor($number); 
        }
        else {
            return ceil($number) - $number;
        }
    }

    public static function formatNumberInBudget($number) {
        if (self::checkIfNumberHasDecimalPart($number)) {
            return number_format($number, 2,'.','');
        }
        else {
            return number_format($number, 0, '.','');
        }
    }

}