<?php
namespace Clsk\Elena\TemplateEngines\Orthia;
require dirname(__FILE__)."/../../../../vendor/autoload.php";

class OrthiaUsersFunctionReader
{
    public function Read(String $function, Array $variable)
    {
        $UserFunctionReader = new UserFunction();
        return call_user_func_array(array($UserFunctionReader, $function), $variable);
    }
}