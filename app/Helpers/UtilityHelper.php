<?php

namespace App\Helpers;

class UtilityHelper {

	public static function generateRandomCharacters($count = 3)
    {
        return bin2hex(openssl_random_pseudo_bytes($count));
    }

}