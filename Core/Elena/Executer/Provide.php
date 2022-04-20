<?php
namespace Clsk\Elena\Executer;

use Clsk\Elena\Request\RequestReceptor;
use Clsk\Elena\TemplateEngines\Orthia\Core\OrthiaGate;
use Clsk\Elena\Tools\FileReader;

class Provide
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
            $engine = new OrthiaGate();
            return $engine->CallAnalyzer(file_get_contents($cinderella_docroot.$filename.".php"), $params, "phper");
        }else{
            echo self::Irregular("NotFound");
        }
    }
    
    public static function Controller(String $controller_name, String $function_name){
        $controller_ns = "Web\Programs\Controllers\\".$controller_name;
        $userController = new $controller_ns;
        $userController->$function_name(AliveFactor::Analyzer($controller_name, $function_name));
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
        // http or https?
        $isHTTPS = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://');

        // domain?
        $host = $_SERVER['HTTP_HOST'];

        $settings = FileReader::SettingGetter();

        // get exclude address pattern from framework setting.
        $exclude = ltrim(rtrim($settings["APPURL"], "/"), "/");
        $exclude = ltrim(ltrim($exclude, $isHTTPS), $host);
        $exclude = str_replace("\\", "/", $exclude);
        $exclude = ltrim(rtrim($exclude, "/"), "/");

        header("Location: /".$exclude."/".$to);
    }
}