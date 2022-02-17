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
                    $result = call_user_func_array(array($Build_In_Function, $method), explode(",", trim($match[1])));
                    if(is_bool($result) && $result && $ifend && $loop_from_next_block && !$eachloop_from_next_block || is_bool($result) and !$result && $loop_from_next_block && !$eachloop_from_next_block){
                        $delete_from_next_block = True;
                        unset($template[$key]);
                    }else if(is_bool($result) && $result && $loop_from_next_block && !$eachloop_from_next_block){
                        $delete_from_next_block = False;
                        unset($template[$key]);
                    }else if(is_int($result) && $loop_from_next_block && !$eachloop_from_next_block){
                        $loop_from_next_block = True;
                        $loop = $result;
                        unset($template[$key]);
                    }else if(is_array($result) && $loop_from_next_block && !$eachloop_from_next_block){ 
                        $eachloop_from_next_block = True;
                        $each_array = $result;
                        unset($template[$key]);
                    }else{
                        if(isset($$result)){
                            $$result = False;
                            $prepared = "";
                            $block_template;
                            if($result == "loop_from_next_block"){
                                for($i = 0; $i <= $loop; $i++){
                                    $analyzer_instance = new Analyzer();
                                    $prepared .= $analyzer_instance->Main($block_template, $this->param) . "\n";
                                }
                            }else if($result == "eachloop_from_next_block"){
                                $key_name = $Build_In_Function->$key_name;
                                $value_name = $Build_In_Function->$value_name;
                                foreach($each_array as $$key_name => $$value_name){
                                    $params = [
                                        $key_name => $$key_name,
                                        $value_name => $$value_name
                                    ];
                                    $params = array_merge($params, $this->param);
                                    $analyzer_instance = new Analyzer();
                                    $prepared .= $analyzer_instance->Main($block_template, $params) . "\n";
                                }
                            }else if($result == "delete_from_next_block"){
                                //TODO
                            }
                            $this->prepared .= $prepared . "\n";
                            unset($template[$key]);
                        }else {
                            $this->prepared .= $result . "\n";
                            unset($template[$key]);
                        }
                    }
                }else if(method_exists($UsersFunction, $method)){
                    call_user_func_array(array($UsersFunction, $method), explode(",", trim($match[1])));
                }else{
                    throw new ClearSkyOrthiaException("Orthia Template Function '".trim($method)."' is not defined.");
                    exit(1);
                }
            }else{
                if($loop_from_next_block || $eachloop_from_next_block){
                    $block_template .$line . "\n";
                }
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