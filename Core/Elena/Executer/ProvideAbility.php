<?php
namespace Clsk\Elena\Executer;

use Clsk\Elena\Request\RequestReceptor;
use Clsk\Elena\TemplateEngines\GregorioTemplateEngine;
use Clsk\Elena\Tools\FileReader;

class ProvideAbility
{
    public static function Viewer(String $filename){
        $request = new RequestReceptor();
        $cinderella_docroot = dirname(__FILE__)."/../../../Web/Templates/Normal/";
        $filename = ltrim(ltrim($filename, "/"), "\\");
        if(file_exists($cinderella_docroot.$filename.".php")){
            return file_get_contents($cinderella_docroot.$filename.".php");
        }
    }
    
    public static function Reader(String $filename, Array $params = array()){
        $cinderella_docroot = dirname(__FILE__)."/../../../Web/Templates/Normal/";
        $filename = ltrim(ltrim($filename, "/"), "\\");
        if(file_exists($cinderella_docroot.$filename.".php")){
            $engine = new GregorioTemplateEngine();
            return $engine->GregorioCore(file_get_contents($cinderella_docroot.$filename.".php"), $params);
        }else{
            echo self::Irregular("NotFound");
        }
    }
    
    public static function Controller(String $controller_name, String $function_name, Array $params = array()){
        $controller_ns = "Web\Programs\Controllers\\".$controller_name;
        $userController = new $controller_ns;
        $userController->$function_name(AliveFactor::Analyzer($controller_name, $function_name, $params));
    }
    
    public static function WebAPI(Array $json){
        header("Access-Control-Allow-Origin: *");
        echo json_encode($json);
    }
    
    public static function Irregular(String $filename){
        $Irregular_docroot = dirname(__FILE__)."/../../../Web/Templates/IrregularCase/";
        $filename = ltrim(ltrim($filename, "/"), "\\");
        if(file_exists($Irregular_docroot.$filename.".php")){
            return file_get_contents($Irregular_docroot.$filename.".php");
        }
    }

    public static function JumpTo(String $to = "/"){
        $settings = FileReader::RouteSettingGetter();
        $exclude = ltrim(rtrim($settings["Exclusion"], "/"), "/");
        header("Location: /".$exclude.$to);
    }
}