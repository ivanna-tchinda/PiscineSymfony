<?php

// include "TemplateEngine.php";

class TemplateEngine{
    function createFile(HotBeverage $text){
        $content = '<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
        '. $text->readData() . 
'   </body>
</html>';
        file_put_contents($fileName, $content);
    }
}

?>