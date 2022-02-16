<?php
require dirname(__FILE__)."/../vendor/autoload.php";

use Clsk\Elena\Router\ResourceReturn;
use Clsk\Elena\Exception\ClskException;
use Clsk\Elena\Request\RequestReceptor;

$cinderella_root = dirname(__FILE__)."/../Web/Settings/Routing.php";
include dirname(__FILE__)."/../Core/Elena/Executer/GateExecuter.php";

if(file_exists($cinderella_root)){
    try{
        $receptor = new RequestReceptor();
        if($receptor->IsSend() && !$receptor->RequestSearch("csrfPosting")){
            echo Irregular("Forbidden");
            exit;
        }
        include $cinderella_root;
        $result = ResourceReturn::ResourceRouting();
        if(!$result){
            echo Irregular("NotFound");
        }
    }catch(\Throwable $e){
        ClskException::ExceptionViewer($e);
    }
}else{
    echo "Fatal error: routing file can't read.";
}