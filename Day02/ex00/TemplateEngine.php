<?php

class TemplateEngine{
    function createFile($fileName, $templateName, $parameters){
        $content = file_get_contents($templateName);
        $pattern = "/\{[^\]]*\}/";
        echo preg_grep($pattern, $content);
        file_put_contents($fileName, $content);
    }
}

?>