<?php
namespace Clsk\Elena\Router;

use Clsk\Elena\Router\StarNavigator;
use Clsk\Elena\Executer\Provide;
use Clsk\Elena\TemplateEngines\GregorioTemplateEngine;

class ResourceReturn
{
    public static function ResourceRouting()
    {
        StarNavigator::Resource("/resource/{params}/", function($params){ 
            if(is_array($params) && count($params) >= 1){
                $cinderella_root = dirname(__FILE__)."/../../../";
                $resource_dir = "Web/Resources/";
                if(strtolower($params[0]) == "css"){
                    header('Content-Type: text/css;', 'charset=utf-8');
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir."CSS/".$path)){
                        $templateengine = new GregorioTemplateEngine();
                        echo $templateengine->GregorioCore(file_get_contents($cinderella_root.$resource_dir."CSS/".$path), array(), true);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }else if(strtolower($params[0]) == "js" || strtolower($params[0]) == "javascript"){
                    header('Content-Type: text/javascript;', 'charset=utf-8');
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir."JavaScript/".$path)){
                        $templateengine = new GregorioTemplateEngine();
                        echo $templateengine->GregorioCore(file_get_contents($cinderella_root.$resource_dir."JavaScript/".$path), array(), true);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }else if(strtolower($params[0]) == "media" || strtolower($params[0]) == "data"){
                    header('Content-type: image/jpg');
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir."Data/".$path)){
                        echo file_get_contents($cinderella_root.$resource_dir."Data/".$path);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }
            }
         });

         StarNavigator::Resource("/DumpFile/{params}/", function($params){ 
            if(is_array($params) && count($params) >= 1){
                $cinderella_root = dirname(__FILE__)."/../../../";
                $resource_dir = "Web/DumpFile/";
                if(strtolower($params[0]) == "css"){
                    header('Content-Type: text/css;', 'charset=utf-8');
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir."CSS/".$path)){
                        $templateengine = new GregorioTemplateEngine();
                        echo $templateengine->GregorioCore(file_get_contents($cinderella_root.$resource_dir."CSS/".$path), array(), true);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }else if(strtolower($params[0]) == "js" || strtolower($params[0]) == "javascript"){
                    header('Content-Type: text/javascript;', 'charset=utf-8');
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir."JavaScript/".$path)){
                        $templateengine = new GregorioTemplateEngine();
                        echo $templateengine->GregorioCore(file_get_contents($cinderella_root.$resource_dir."JavaScript/".$path), array(), true);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }else if(strtolower($params[0]) == "media" || strtolower($params[0]) == "data"){
                    $path = "";
                    $read = false;
                    foreach($params as $param){
                        if($read == false){
                            $read = true;
                        }else{
                            $path .= $param."/";
                        }
                    }
                    $path = rtrim($path, "/");
                    if(array_key_exists(1, $params) && file_exists($cinderella_root.$resource_dir.$path)){
                        header('Content-type: image/jpg');
                        echo file_get_contents($cinderella_root.$resource_dir.$path);
                        exit;
                    }else{
                        Provide::Irregular("NotFound");
                    }
                }
            }
         });
         return false;
    }  
}