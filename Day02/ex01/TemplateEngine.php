<?php

class TemplateEngine{
    function createFile($fileName, $text){
        $content = '<!DOCTYPE html>
<html>
	<head>
		<title>{nom}</title>
	</head>
	<body>
        '. $text->readData() . 
'   </body>
</html>';
        file_put_contents($fileName, $content);
    }
}

?>