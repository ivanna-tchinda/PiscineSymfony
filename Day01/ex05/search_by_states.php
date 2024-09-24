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

function get_key($state){
    $capitals = $GLOBALS["capitals"];
    foreach($capitals as $cap){
        $key = key($capitals);
        if($state == $capitals[$key]){
            return $key;
        }
        next($capitals);
    }
}

function is_a_capital($capital){
    $states = $GLOBALS["states"];
    $capitals = $GLOBALS["capitals"];
    if (in_array($capital, $capitals)){
        $state_initials = get_key($capital);
        if(!in_array($state_initials, $states)){
            return false;
        }
        echo "$capitals[$state_initials] is the capital of " .  array_search($state_initials, $states) . "\n";
        return true;
    }
}

function is_a_state($state){
    $states = $GLOBALS["states"];
    $capitals = $GLOBALS["capitals"];
    if (array_key_exists($state, $states)){
        $state_initials = $states[$state];
        echo "$capitals[$state_initials] is the capital of $state\n";
        return true;
    }
    return false;
}

function search_by_states($str){
    $states = $GLOBALS["states"];
    $capitals = $GLOBALS["capitals"];

    $state = explode(",", $str);
    
    foreach($state as $st){
        $trimmed = trim($st);
        switch(true)
        {
            case is_a_state($trimmed):
                break;
            case is_a_capital($trimmed):
                break;
            default:
                echo $trimmed . " is neither a capital nor a state.\n";
                break;
        }
    }
}

?>