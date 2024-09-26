<?php


class TemplateEngine{
    function createFile($fileName, HotBeverage $text){
        $templateName = "template.html";
        $content = file_get_contents($templateName);
        $pattern = '/{(.*?)}/';

        $reflection = new ReflectionClass($text);

        $a = "";

        $content = explode("\n", $content);
        
        foreach($content as $line){
            preg_match_all($pattern, $line, $tab);
            if(!$tab[1]){
                continue;
            }
            $element = $tab[1];

            switch ($element[0]) {
                case 'nom':
                    $a = $reflection->getMethod('getName')->getName();
                    break;

                case 'price':
                    $a = $reflection->getMethod('getPrice')->getName();
                    break;

                case 'resistance':
                    $a = $reflection->getMethod('getResistance')->getName();
                    break;

                case 'description':
                    $a = $reflection->getMethod('getDescription')->getName();
                    break;
                    
                case 'comment':
                    $a = $reflection->getMethod('getComment')->getName();
                    break;
                    
                default:
                    break;
            }
            $line = preg_replace($pattern, $text->$a(), $line);
            file_put_contents($fileName, $line, FILE_APPEND);
        }
    }
}

?>