<?php

namespace App\Helpers;

class UtilityHelper {

	public static function generateRandomCharacters(int $count = 3)
    {
        return bin2hex(openssl_random_pseudo_bytes($count));
    }

    public static function convertToCharacters(int $number)
    {
	    if ($number <= 0) return '';

	    $final_character = '';
	             
	    while($number != 0){
	       $position = ($number - 1) % 26;
	       $number = intval(($number - $position) / 26);
	       $final_character = chr(65 + $position) . $final_character;
	    }
	    
	    return $final_character;
	        
	}

}