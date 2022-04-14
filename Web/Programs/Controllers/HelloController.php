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
        $title = "test";
        $variable_test = "これは変数";
        $array = ["this is ", "array"];
        $access_test = ["Access" => ["test", "a" => "Hello!"]];
        $vardump_test = ["if you showing array, ", "neary moving var_dump"];
        $test = "hello!";
        echo Provide::Reader("Hello", compact("lang", "title", "variable_test", "array", "access_test", "test"));
    }
}