<?php

function array2hash_sorted($arrays){
    $hash_array = array();
    foreach($arrays as $array){
        $hash_array[$array[0]] = $array[1];
    }
    krsort($hash_array);
    print_r($hash_array);
}

?>