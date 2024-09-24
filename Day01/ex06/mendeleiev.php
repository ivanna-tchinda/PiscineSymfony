<?php

$html_file = "Mendeleiev.html";
$elements_file = "ex06.txt";
$content_file = file_get_contents($elements_file);

$mend_table = build_table();
$html_content = '<html>
    <head>
        <title>Mendeleiev Table</title>
        <style>
            li{
                font-size: 11px;
            }
        </style>
    </head>
    <body>' . $mend_table . '</body>
</html>';

file_put_contents($html_file, $html_content);

function empty_cell($num){
    if(($num > 0 && $num < 17) || ($num > 19 && $num < 30) || ($num > 37 && $num < 48) || $num == 56)
        return true;
    return false;
}

function tab_content(){
    $content = $GLOBALS["content_file"];
    $lines = explode("\n", $content);
    $tab = "";
    $element_index = 0;
    for($row = 0; $row < 7; $row++){
        $tab .= "<tr style='border: solid; height: 100px'>";
        for($col = 0; $col < 18 && $element_index < 88; $col++){
            if(empty_cell($element_index)){
                $tab .= "<td style='border: solid;'>0</td>";
            }
            else{
                $infos = explode(",", $lines[$element_index]);
                $first_arg = explode(" ", $infos[0]);
                $name = $first_arg[0];
                $position = $first_arg[2];
                $tab .= "<td style='border: solid';>" . 
                    "<h4>" . $name . "</h4>" .
                    "<ul style='list-style-type:none;'>" .
                        "<li>" . $position . "</li>" .
                        "<li>" . $position . "</li>" .
                        "<li>" . $position . "</li>" .
                        "<li>" . $position . "</li>" .
                        "<li>" . $position . "</li>" .
                    "</ul>" 
                ."</td>";
            }
            $element_index++;
        }
        $tab .= "</tr>";
    }
    return $tab;
}

function build_table(){
    $content = "<h4 style='text-align:center; margin-top: 50px;'>Tableau periodique des elements chimiques</h4>";
    $table_content = tab_content();
    $content .= "<table style='width: 80%'>" . $table_content . "</table>";
    return $content;
}

?>