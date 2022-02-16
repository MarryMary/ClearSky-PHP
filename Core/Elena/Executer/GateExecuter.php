<?php
require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Executer\ProvideAbility;

function Viewer(String $filename){
    return ProvideAbility::Viewer($filename);
}

function Reader(String $filename, Array $params){
    return ProvideAbility::Reader($filename, $params);
}

function Controller(String $controller_name, String $function_name, Array $params = array()){
    return ProvideAbility::Controller($controller_name, $function_name, $params);
}

function WebAPI(Array $json){
    ProvideAbility::WebAPI($json);
}

function Irregular(String $filename){
    return ProvideAbility::Irregular($filename);
}