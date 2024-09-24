<?php

function array2hash($arrays) {
    $hash_array = array();
    foreach($arrays as $array){
        $hash_array[$array[1]] = $array[0];
    }
    print_r($hash_array);
}

?>