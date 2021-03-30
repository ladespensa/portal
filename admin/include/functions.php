<?php




function generate_string($strength = 4) {

    $input = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input2 = '0123456789';

    $input_length = strlen($input);
    $input_length2 = strlen($input2);
    
    $random_string = '';
    
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];

        $random_character2 = $input2[mt_rand(0, $input_length2 - 1)];

        $random_string .= $random_character.$random_character2;
    }
 
    return $random_string;
}



?>