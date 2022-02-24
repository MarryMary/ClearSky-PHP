<?php
namespace Web\Programs\Controllers;

/* HelloController コントローラー
* ここに処理を記述します。
*
*/
use Clsk\Elena\Session\Session;
use Clsk\Elena\Tools\TimeToTime;
use Clsk\Elena\Tools\FileReader;
use Clsk\Elena\Request\RequestReceptor;
use Clsk\Elena\Executer\Provide;

class HelloController{
    public function Index()
    {
        $lang = "ja";
        $title = "Hello!";
        $variable_test = "これは変数";
        $array = ["これは", "配列"];
        $access_test = ["Access" => ["test", "a" => "こんにちは。"]];
        $vardump_test = ["配列をそのまま表示させると", "var_dumpと同じ動作になります。"];
        echo Provide::Reader("Hello", compact("lang", "title", "variable_test", "array", "access_test"));
    }
}