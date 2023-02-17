<?php

class Validation {


    public static function test_input($data) {
        $data = trim($data);
      //  $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

      public static function incomeExpenseCommentCheck($comment) {
        $error = "";
        if ($comment != '' || strlen($comment) != 0) {
          if (!preg_match("/^[a-z0-9\040\.\-\/]+$/i",$comment)) {    // \x5C backslash
            $error = "Only letters, numbers, space, forward slash, period and dash allowed in the comment";
            return $error;
          }
        }
        return $error;       
      }
}