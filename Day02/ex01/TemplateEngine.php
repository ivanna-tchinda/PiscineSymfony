<?php

class TemplateEngine{
    function createFile($fileName, $text){
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