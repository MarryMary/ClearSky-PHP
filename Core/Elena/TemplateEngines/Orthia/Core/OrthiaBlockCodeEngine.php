<?php

namespace Clsk\Elena\TemplateEngines\Orthia\Core;

use Clsk\Elena\TemplateEngines\Orthia\Core\ClearSkyOrthiaException;
use Clsk\Elena\TemplateEngines\Orthia\Core\OrthiaBlockFunction;
use Clsk\Elena\TemplateEngines\Orthia\Core\OrthiaBuildInFunctions;
use Clsk\Elena\TemplateEngines\Orthia\Core\SkipFunctionCheck;
use TemplateFunctions\Orthia\UserFunction;

class OrthiaBlockCodeEngine
{
    private $template;
    private $params;
    private $parsemode = "phper";
    private $isHTMLSC;
    public function Entrance(String $template, Array $params, String $mode = "phper", Bool $isHTMLSC = True)
    {
        $this->template = $template;
        $this->params = $params;
        $this->parse_mode = $mode;
        $this->isHTMLSC = $isHTMLSC;
        $this->MainAnalyzer();
        return $this->template;
    }

    public function MainAnalyzer()
    {
        $template = explode("\n", $this->template);
        $this->template = "";
        $dumping = False;
        $dropping = False;
        $skip_nextblock = False;
        $dump_nextblock = False;
        $type = "string";
        $endcounter = 0;
        $dumper = "";
        $block_start_name = "";
        extract($this->params);
        $BuiltInBlockFunction = new OrthiaBlockFunction($this->params, $this->parsemode, $this->isHTMLSC);
        $CanSkipUsingFunction = new SkipFunctionCheck();
        foreach($template as $key => $line){
            $is_code = False;
            $pattern = '/\{%.+?%\}/';
            preg_match_all($pattern, $line, $variables);
            foreach($variables[0] as $variable) {
                $val = trim($variable);
                $val = str_replace('{%', '', $val);
                $val = str_replace('%}', '', $val);
                $val = trim($val);
                $method_name = substr($val, 0, strcspn($val,'('));
                if ($this->BuiltInFunctionJudgement($method_name)) {
                    if(strpos($method_name,'end') !== false && substr($method_name, 0, 3) && $method_name == "end".trim($block_start_name)){
                        if($endcounter == 0){
                            $dumping = False;
                            $dropping = False;
                            $skip_nextblock = False;
                            $dump_nextblock = False;
                            $is_code = True;
                            $this->template .= $BuiltInBlockFunction->$method_name($dumper)."\n";
                            $dumper = "";
                        }else{
                            $endcounter--;
                        }
                    }else if(!$dumping && !$dropping && $skip_nextblock && $CanSkipUsingFunction->checker($method_name) || !$dumping && !$dropping && $dump_nextblock && $CanSkipUsingFunction->checker($method_name) || !$dumping && !$dropping && !$dump_nextblock && !$skip_nextblock) {
                        if (!$this->JudgeBlockOrLine($method_name)) {
                            $pattern = "{\((.*)\)}";
                            preg_match($pattern, $val, $match);
                            if(isset($match[1])) {
                                if(strpos($match[1],'$') !== false){
                                    if(strpos($match[1],'\\') !== false || !isset($match[1])){
                                        $result = $BuiltInBlockFunction->$method_name($match);
                                    }else{
                                        $result = $BuiltInBlockFunction->$method_name($match[1]);
                                    }
                                }else{
                                    $result = $BuiltInBlockFunction->$method_name($match[1]);
                                }
                            }else if(!$dumping && !$dropping){
                                if(array_key_exists(0, $match)){
                                    if(strpos($match[0], '$') !== false){
                                        if(strpos($match[0], '\\') !== false || !isset($$match[0])){
                                            $result = $BuiltInBlockFunction->$method_name($match[0]);
                                        }else{
                                            $result = $BuiltInBlockFunction->$method_name($$match[0]);
                                        }
                                    }else{
                                        $result = $BuiltInBlockFunction->$method_name($match[0]);
                                    }
                                }else{
                                    $result = $BuiltInBlockFunction->$method_name();
                                }
                            }
                            if (is_string($result) && $type == "string" || is_string($result) && !$dump_nextblock && !$skip_nextblock) {
                                $is_code = True;
                                if(strpos($result,'ORTHIASIGNAL@') !== false){
                                    return $this->template."##".$result;
                                }
                                $this->template .= $result."\n";
                                unset($template[$key]);
                            } else if (is_bool($result) && $result && $type == "bool" || is_bool($result) && $result && !$dump_nextblock && !$skip_nextblock) {
                                $is_code = True;
                                $block_start_name = $method_name;
                                $dumping = True;
                                $dropping = False;
                            } else if (is_bool($result) && !$result && $type == "bool" || is_bool($result) && !$result && !$dump_nextblock && !$skip_nextblock) {
                                $is_code = True;
                                $block_start_name = $method_name;
                                $dumping = False;
                                $dropping = True;
                            }else if(is_array($result) && $type == "array" || is_array($result) && !$dump_nextblock && !$skip_nextblock){
                                $is_code = True;
                                if(count($result) != 0){
                                    if(is_bool($result[0]) && $result[0]){
                                        if(array_key_exists(1, $result)){
                                            $block_start_name = $result[1];
                                        }else {
                                            $block_start_name = $method_name;
                                        }
                                        $skip_nextblock = False;
                                        $dump_nextblock = True;
                                        $dumping = False;
                                        $dropping = False;
                                        $type = "array";
                                    }else if(is_bool($result[0]) && !$result[0]){
                                        if(array_key_exists(1, $result)){
                                            $block_start_name = $result[1];
                                        }else {
                                            $block_start_name = $method_name;
                                        }
                                        $skip_nextblock = True;
                                        $dump_nextblock = False;
                                        $dumping = False;
                                        $dropping = False;
                                        $type = "array";
                                    }
                                }
                            }
                        } else if($this->JudgeBlockOrLine($method_name) && !$dropping && !$dumping && !$skip_nextblock && !$dump_nextblock) {
                            $is_code = True;
                            $BuiltInFunction = new OrthiaBuildInFunctions($this->params, $this->parsemode, $this->isHTMLSC);
                            $pattern = "{\((.*)\)}";
                            preg_match($pattern, $val, $match);
                            if(isset($match[1]) && !$dropping && !$dumping) {
                                $result = $BuiltInFunction->$method_name($match[1]);
                            }else if(!$dropping && !$dumping){
                                $result = $BuiltInFunction->$method_name($match);
                            }
                            if (is_string($result)) {
                                if(strpos($result,'ORTHIASIGNAL@') !== false){
                                    return $this->template."##".$result."\n";
                                }
                                $this->template .= str_replace($variable, $result, $line)."\n";
                            }
                        }
                    }else{
                        if(trim($method_name) == trim($block_start_name)){
                            $endcounter ++;
                        }
                    }
                } else if ($this->UserCreateFunctionJudgement($method_name) && !$dropping && !$dumping) {
                    extract($this->params);
                    $is_code = True;
                    $UserFunction = new UserFunction();
                    $pattern = "{\((.*)\)}";
                    preg_match($pattern, $val, $match);
                    $result = "";
                    if(isset($match[1]) && !$dropping && !$dumping) {
                        if(strpos($match[1], '$') !== false){
                            if(strpos($match[1], '\\') !== false || !isset($match[1])){
                                $result = $UserFunction->$method_name($match[1]);
                            }else{
                                $matched = str_replace("$", "", $match[1]);
                                $result = $UserFunction->$method_name($$matched);
                            }
                        }else{
                            $result = $UserFunction->$method_name($match[1]);
                        }
                    }else if(!$dropping && !$dumping){
                        if(strpos($match, '$') !== false){
                            if(strpos($match, '\\') !== false || !isset($match)){
                                $result = $UserFunction->$method_name($match);
                            }else{
                                $matched = str_replace("$", "", $match);
                                $result = $UserFunction->$method_name($$matched);
                            }
                        }else{
                            $result = $UserFunction->$method_name($match);
                        }
                    }

                    $this->template .= str_replace($variable, $result, $line)."\n";
                } else {
                    if($dumping || $dropping || $dump_nextblock){
                        $is_code = False;
                    }else {
                        $is_code = True;
                    }
                    $result = eval("return ".$val.";");
                    $this->template .= $result."\n";
                }
            }
            if(!$is_code){
                if($dumping || $dump_nextblock){
                    $dumper .= $line."\n";
                }
                if(!$dropping && !$dumping && !$skip_nextblock && !$dump_nextblock) {
                    $this->template .= $line."\n";
                }
            }
        }
    }

    public function JudgeBlockOrLine(String $FunctionName)
    {
        if($this->BuiltInFunctionJudgement($FunctionName)){
            $BuiltInFunction = new OrthiaBuildInFunctions($this->params, $this->parsemode);
            $BuiltInBlockFunction = new OrthiaBlockFunction($this->params, $this->parsemode);
            if(method_exists($BuiltInFunction, $FunctionName)){
                return True;
            }else if(method_exists($BuiltInBlockFunction, $FunctionName)){
                return False;
            }
        }else{
            return False;
        }
    }

    public function BuiltInFunctionJudgement(String $FunctionName)
    {
        $BuiltInFunction = new OrthiaBuildInFunctions($this->params, $this->parsemode);
        $BuiltInBlockFunction = new OrthiaBlockFunction($this->params, $this->parsemode);
        if(method_exists($BuiltInFunction, $FunctionName) || method_exists($BuiltInBlockFunction, $FunctionName)){
            return True;
        }else{
            return False;
        }
    }

    public function UserCreateFunctionJudgement(String $FunctionName)
    {
        $UserFunction = new UserFunction();
        if(method_exists($UserFunction, $FunctionName)){
            return True;
        }else{
            return False;
        }
    }
}