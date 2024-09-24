<?php

$file_content = file_get_contents('ex01.txt');
$elements = str_replace(",", "\n", $file_content);
echo $elements;

?>