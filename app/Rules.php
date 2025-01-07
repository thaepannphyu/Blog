<?php 
namespace app;
class Rules {
 public function required($value)
{
    if (is_array($value)) {
        // Check if the array is empty or the file size is zero or not set
        if (empty($value) || !isset($value['size']) || $value['size'] <= 0) {
            return 1; // Validation failed
        }
    } else {
        // Check if the value is empty after removing leading and trailing spaces
        $value = preg_replace('/^\s+|\s+$/', '', $value);
        if ($value === '' || is_null($value)) {
            return 1; // Validation failed
        }
    }
    return 0; // Validation passed
}


    //string length
    public function lengthNotGreaterThan($value, $rule)
    {
        if (strlen($value) > $rule) {
            return 1;
        }
        return 0;
    }
    public function lengthBetween($value,$min,$max){
        if(strlen($value) < $min || strlen($value) > $max) {
           return 1;
        }
        return 0;
    }
    public function date($value)
    {
        if(!preg_match('/^(1\d{3}|2\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $value)) {
            return 1;
        }
        return 0;
    }
    public function notToday($value)
    {
        $date = new \DateTime($value);
        $today = new \DateTime();
        if($date > $today) {
            return 1;            
        }
        return 0;
    }
    public function ageWithin($value,$rule)
    {
        $date = new \DateTime($value);
        $maxInterval = DateInterval::createFromDateString("$rule years");
        $today = new DateTime();
        $limit = (clone $today)->sub($maxInterval);
        if( $limit  >=   $date ) {
            return 1;        
        }
        return 0;        
    }
    public function fileSize($value, $max)
    {
        if($value > ($max * 1048576)) {
            return 1;
        }
        return 0;
    }
    public function fileType($value, $rule)
    {
        if(!in_array($value, $rule)) {
              print_r(in_array($value, $rule));
           return 1;
        }
        return 0;
    }
    public function isNumber($value)
    {
        if(!is_numeric($value)) {
            return 1;
        }
        return 0;
    }
    public function specialCharacter($value)
    {
        if(preg_match('/[^a-zA-Z0-9\s\p{L}\'-]/u', $value)) {
            return 1;
        }
        return 0;
    }
    public function checkEmail($value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 1;
        }
        return 0;
    }
    public function notContainNumber($value)
    {
        if(preg_match('/\d/', $value)){
            return 1;
        }
        return 0;
    }
}