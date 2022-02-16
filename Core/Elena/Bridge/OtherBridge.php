<?php
/*
* 他言語接続部分
* 他言語に処理を委任する際に呼び出すクラスです。。
*/
namespace Clsk\Elena\Bridge;

use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class OtherBridge
{
    public function Connect(String $to, String $lang, Array $val = array()){
        $uuid = UUIDFactory::generate();
        $cinderella_root = dirname(__FILE__)."/../../../Web/OtherLang";

        if(is_array($val) && count($val) != 0){
            FileReader::ArrayToJsonFile("Web/OtherLang/Src/".$uuid.".json", $val);
        }

        exec($lang." ".$cinderella_root.$to." ".$uuid, $return);

        return $return;
    }
}