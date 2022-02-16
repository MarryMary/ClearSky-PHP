<?php
namespace Clsk\Elena\TemplateEngines\Orthia;

use Clsk\Elena\Exception\ExceptionThrower\ClearSkyOrthiaException;
use Clsk\Elena\TemplateEngines\Orthia\OrthiaBuildInFunctions;
use TemplateFunctions\Orthia\UserFunction;

class Analyzer
{
    private $param;
    private $template;
    private $prepared;
    public function Main(String $template, Array $param = array(), Bool $mode = False)
    {
        if(count($param) != 0 && !$mode){
            $this->template = $template;
            $this->param = $param;
            $this->FunctionParser();
            $this->VariableInserter();
            return $this->prepared;
        }else if(count($param) == 0){
            $this->template = $template;
            $this->param = array();
            $this->FunctionParser();
            return $this->prepared;
        }else{
            $this->template = $template;
            $this->param = $param;
            $this->VariableInserter();
            return $this->prepared;
        }
    }

    private function FunctionParser()
    {
        $block_template = "";
        $delete_from_next_block = False;
        $loop_from_next_block = False;
        $eachloop_from_next_block = False;
        $each_array = array();
        $loop = 0;
        foreach($this->param as $key => $value) {
            $$key = $value;
        }
        $template = explode("\n", $this->template);
        foreach($template as $key => $line){
            if(substr_count(trim($line), "@") == 1){
                if($delete_from_next_block){
                    $delete_from_next_block = False;
                }
                $Build_In_Function = new OrthiaBuildInFunctions($this->param);
                $UsersFunction = new UserFunction($this->param);
                $line = ltrim($line, "@");
                $pattern = "{\((.*)\)}";
                preg_match($pattern, $line,$match);
                $method = substr($line, 0, strcspn($line,'('));
                if(method_exists($Build_In_Function, $method)){
                    $result = call_user_func_array(array($Build_In_Function, $method), trim(explode(",", $match[1])));
                    if(is_bool($result) and $result and $ifend || is_bool($result) and !$result){
                        $delete_from_next_block = True;
                    }else if(is_bool($result) and $result){
                        $delete_from_next_block = False;
                    }else if(is_int($result)){
                        $loop_from_next_block = True;
                        $loop = $result;
                    }else if(is_array($result)){
                        $eachloop_from_next_block = True;
                        $each_array = $result;
                    }else{
                        if(isset($$result)){

                            $$result = False;
                        }else {
                            $this->prepared .= $result . "\n";
                            unset($template[$key]);
                        }
                    }
                }else if(method_exists($UsersFunction, $method)){
                    call_user_func_array(array($UsersFunction, $method), trim(explode(",", $match[1])));
                }else{
                    throw new ClearSkyOrthiaException("Orthia Template Function '".trim($method)."' is not defined.");
                    exit(1);
                }
            }else{
                if(!$delete_from_next_block) {
                    $this->prepared .= $line . "\n";
                }
                unset($template[$key]);
            }
        }
        return True;
    }

    private function VariableInserter()
    {
        $prepared = "";
        $template = explode("\n", $this->prepared);
        $flag = False;
        foreach($template as $line){
            $pattern = '/\{{.+?\}}/';
            preg_match_all($pattern, $line, $variable);
            foreach($variable[1] as $val){
                $val_trimed = trim($val);
                if(isset($$val)){
                    foreach($variable[0] as $val_include_block){
                        if(strpos($val_include_block, $val) !== false){
                            $prepared .= str_replace($val_include_block, $val_trimed, $line)."\n";
                            $flag = True;
                            break;
                        }
                    }
                }
            }
            if(!$flag){
                $prepared .= $line."\n";
            }
        }
        return $prepared;
    }
}