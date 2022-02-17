<?php
namespace Clsk\Elena\TemplateEngines\Orthia;
require dirname(__FILE__)."/../../../../vendor/autoload.php";

use Clsk\Elena\Session\Session;
use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class OrthiaBuildInFunctions
{
    public $params;
    public $key_name;
    public $value_name;

    public function __construct(String $param)
    {
        $this->params = $param;
    }

    public function if($terms)
    {
        $result = eval($terms);
        if(is_bool($result)){
            return $result;
        }else{
            return False;
        }
    }

    public function elif($terms)
    {
        $result = eval($terms);
        if(is_bool($result)){
            return $result;
        }else{
            return False;
        }
    }

    public function else($condition)
    {
        return True;
    }

    public function for()
    {
        //TODO
    }

    public function foreach($array, $get)
    {
        if(strpos($get,'=>') !== false){
            $key_and_value = explode("=>", $get);
            $this->key_name = trim($key_and_value[0]);
            $this->value_name = trim($key_and_value[1]);
          }else{
              $this->key_name = "key";
              $this->value_name = "value";
          }
    }

    public function csrf_token()
    {
        $token = UUIDFactory::generate();
        Session::Insert("csrfToken", $token);
        return "<input type='hidden' name='csrfPosting' id='csrfPosting' value='".trim($token)."'>";
    }

    public function endforeach()
    {
        return "eachloop_from_next_block";
    }

    public function endfor()
    {
        return "loop_from_next_block";
    }

    public function endif()
    {
        return "delete_from_next_block";
    }

    public static function resource()
    {
        //TODO
    }

    public static function frame()
    {
        //TODO
    }
}