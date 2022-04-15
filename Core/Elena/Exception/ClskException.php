<?php
namespace Clsk\Elena\Exception;

use Clsk\Elena\TemplateEngines\GregorioTemplateEngine;

class ClskException
{
    public static function ExceptionViewer($e)
    {
        if (PHP_SAPI == 'cli'){
            $str = "ClearSky Reading Exception";
            echo "\033[41m$str\033[0m";
            echo "\n";
            echo "\n";
            echo "Exception is:";
            echo "\n";
            echo $e->getMessage();
            echo "\n";
            echo "Traceback is:";
            echo "\n";
            echo $e->getTraceAsString();
            exit;
        }else{
            $template = file_get_contents(dirname(__FILE__)."/ExceptionTemplate.html");
            $template = str_replace("{{MESSAGE}}", htmlspecialchars($e->getMessage()), $template);
            $template = str_replace("{{LINE}}", htmlspecialchars($e->getLine()), $template);
            $template = str_replace("{{TRACEBACK}}", trim(htmlspecialchars($e->getTraceAsString())), $template);
            if(file_exists($e->getFile())) {
                $highlight = explode("\n", file_get_contents(htmlspecialchars($e->getFile())));
                $template = str_replace("{{HIGHLIGHT}}", trim(htmlspecialchars($highlight[$e->getLine() - 1])), $template);
                $template = str_replace("{{FILE}}", htmlspecialchars($e->getFile()), $template);
            }else{
                $template = str_replace("{{HIGHLIGHT}}", "FAILED TO CHASE THE CORRESPONDING FILE.", $template);
                $template = str_replace("{{FILE}}", "FAILED TO CHASE THE CORRESPONDING FILE.", $template);
            }
            echo $template;
            exit;
        }
    }
}