<?php
namespace TemplateFunctions\Orthia;

class UserFunction
{
    /*
    *Please write template engine using function here.
    */
    public function TestFunction(String $hello)
    {
        if($hello == "world"){
            return "hello";
        }
    }
}