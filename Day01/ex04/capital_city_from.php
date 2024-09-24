<?php

$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];
$capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka',
];

function capital_city_from($state_arg){
    $states = $GLOBALS["states"];
    $capitals = $GLOBALS["capitals"];
    if (array_key_exists($state_arg, $GLOBALS["states"])){
        $state_initials = $states[$state_arg];
        echo "$capitals[$state_initials]\n";
    }
    else{
        echo "Unknown\n";
    }
}


?>