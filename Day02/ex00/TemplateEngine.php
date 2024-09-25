<?php

class TemplateEngine{
    function createFile($fileName, $templateName, $parameters){
        $content = file_get_contents($templateName);
        $pattern = '/{(.*?)}/';
        $content = explode("\n", $content);
        foreach($content as $line){
            preg_match_all($pattern, $line, $tab);
            $element = $tab[1];
            if($element)
            {
                echo $parameters[$element[0]] . "\n";
                $line = preg_replace($pattern, $parameters[$element[0]], $line);
            }
            file_put_contents($fileName, $line, FILE_APPEND);
        }
    }
}

?>