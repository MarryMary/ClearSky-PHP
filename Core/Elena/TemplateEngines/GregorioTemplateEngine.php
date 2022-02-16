<?php
namespace Clsk\Elena\TemplateEngines;
require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Session\Session;
use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class GregorioTemplateEngine
{
    private $codedump = False;
    private $code = Null;
    private $prepared = Null;
    private $codecount = 0;
    private $param;
    public function GregorioCore(String $template, Array $param = array(), Bool $mode=false){
        $template = explode("\n", $template);
        $this->param = $param;
        $this->GregorioAnalyzer($template, $mode);
        return $this->prepared;
    }

    private function GregorioAnalyzer(Array $templates, bool $mode=false){
        foreach($this->param as $key => $value) {
            $$key = $value;
        }
        Session::Start();
        $i = 0;
        Session::Insert("csrfToken", UUIDFactory::generate());
        foreach($templates as $template){
            $i += 1;
            if(substr(ltrim($template), 0, 1) === '@' && !$mode){
                if(substr_count($template, "@") == 1){
                    if(strpos($template,'csrfGuard') !== false){
                        $template = '<input type="hidden" id="csrfGuard" name="csrfPosting" value="'.Session::SessionReader("csrfToken").'">';
                        if($this->codedump){
                            $this->code .= '$template;';
                            $this->code .= "\n";
                        }else{
                            $this->prepared .= $template;
                        }
                    }else if(strpos($template,'resource') !== false){
                        $template = str_replace('@resource(', '', $template);
                        $template = str_replace(')', '', $template);
                        $template = explode(",", $template);
                        $setting = FileReader::SettingGetter();
                        if(count($template) != 0){
                            $template[0] = rtrim($template[0], "/");
                            $template[0] = str_replace("\\", "/", $template[0]);
                            if(count($template) == 1){

                                $templated = "<link rel='stylesheet' href='".rtrim(trim($setting["APPURL"]), "/")."/resource/".trim($template[0])."/'>";                                                        
                                if($this->codedump){
                                    $this->code .= '$templated;';
                                    $this->code .= "\n";
                                }else{
                                    $this->prepared .= $templated;
                                }
                            }else if(count($template) == 2){
                                if(trim(strtolower($template[1])) == "css"){
                                    $templated = "<link rel='stylesheet' href='".rtrim(trim($setting["APPURL"]), "/")."/resource/css/".trim(trim($template[0]), "/")."/'>";
                                }else if(trim(strtolower($template[1])) == "js" || trim(strtolower($template[1])) == "javascript"){
                                    $templated = "<script type='text/javascript' src='".rtrim(trim($setting["APPURL"]), "/")."/resource/js/".trim(trim($template[0]), "/")."/'></script>";
                                }else if(trim(strtolower($template[1])) == "data" || trim(strtolower($template[1])) == "media"){
                                    $templated = "<img src='".rtrim(trim($setting["APPURL"]), "/")."/resource/media/".trim(trim($template[0]), "/")."/' alt='media'>";
                                }                        
                                if($this->codedump){
                                    $this->code .= '$templated;';
                                    $this->code .= "\n";
                                }else{
                                    $this->prepared .= $templated;
                                }
                            }else{
                                $templated = "<link rel='stylesheet' href='".rtrim(trim($setting["APPURL"]), "/")."/resource/".$template[0]."/'>";                     
                                if($this->codedump){
                                    $this->code .= '$templated;';
                                    $this->code .= "\n";
                                }else{
                                    $this->prepared .= $templated;
                                }
                            }
                        }
                    }else if(strpos($template,'frame') !== false){
                        $template = str_replace('@frame(', '', $template);
                        $template = str_replace(')', '', $template);
                        $template = explode(",", $template);
                        if(count($template) != 0){
                            $template[0] = rtrim($template[0], "/");
                            $template[0] = str_replace("\\", "/", $template[0]);
                            $template[0] = trim($template[0]);
                            if(count($template) == 1){
                                $cinderella_root = dirname(__FILE__)."/../../../Web/Templates/Normal/";
                                if(file_exists($cinderella_root.$template[0])){
                                    $frame_template = file_get_contents($cinderella_root.$template[0]);
                                    if($this->codedump){
                                        $this->code .= '$frame_template;';
                                        $this->code .= "\n";
                                    }else{
                                        $this->prepared .= $frame_template;
                                    }
                                }else{
                                    if($this->codedump){
                                        $this->code .= "\n";
                                    }else{
                                        $this->prepared .= "";
                                    }
                                }
                            }else if(count($template) == 2){
                                if(trim(strtolower($template[1])) == "reader"){
                                    $cinderella_root = dirname(__FILE__)."/../../../Web/Templates/Normal/";
                                    if(file_exists($cinderella_root.$template[0])){
                                        $frame_template = file_get_contents($cinderella_root.$template[0]);
                                        $params = array();
                                        if(count($template) == 3)
                                        {
                                            $param = explode(";", $template[2]);
                                            $i = 0;
                                            foreach($param as $p){
                                                $k_v = explode("=", $p);
                                                if(count($k_v) == 2){
                                                    $params[$k_v[0]] = $params[$k_v[1]];
                                                }else{
                                                    $params[$i] = $params[$k_v[0]];
                                                    $i++;
                                                }
                                            }
                                        }
                                        if($this->codedump){
                                            $te = new GregorioTemplateEngine();
                                            $this->code .= $te->GregorioCore($frame_template, $params);
                                            $this->code .= "\n";
                                        }else{
                                            $te = new GregorioTemplateEngine();
                                            $this->prepared .= $te->GregorioCore($frame_template, $params);
                                        }
                                    }else{
                                        if($this->codedump){
                                            $this->code .= "\n";
                                        }else{
                                            $this->prepared .= "";
                                        }
                                    }
                                }
                            }else{
                                $cinderella_root = dirname(__FILE__)."/../../../Web/Templates/Normal/";
                                if(file_exists($cinderella_root.$template[0])){
                                    $frame_template = file_get_contents($cinderella_root.$template[0]);
                                    if($this->codedump){
                                        $this->code .= '$frame_template;';
                                        $this->code .= "\n";
                                    }else{
                                        $this->prepared .= $frame_template;
                                    }
                                }else{
                                    if($this->codedump){
                                        $this->code .= "\n";
                                    }else{
                                        $this->prepared .= "";
                                    }
                                }
                            }
                        }
                    }else{
                        $this->code .= str_replace('@', '', ltrim($template));
                        $this->codecount += 1;
                        $this->codedump = True;
                    }
                }else if(substr_count($template, "@") == 2){
                    $this->code .= str_replace('@', '', ltrim($template));
                    $this->codedump = True;
                }else if(substr_count($template, "@") == 3){
                    $this->codedump = True;
                    $this->codecount -= 1;
                    if($this->codecount == 0){
                        $this->code .= str_replace('@', '', ltrim($template));
                        eval($this->code);
                        $this->codedump = False;
                        $this->code = "";
                    }else{
                        $this->code .= str_replace('@', '', ltrim($template));
                    }
                }
            }else{
                if($this->codedump){
                    if(strpos($template,'"') !== false){
                        $pattern = '/\{{.+?\}}/';
                        preg_match_all($pattern, $template, $variable);
                        foreach($variable[0] as $val){
                            $binder = str_replace('{{', '', $val);
                            $binder = str_replace('}}', '', $binder);
                            $binder = trim($binder);
                            if (strpos($binder,'$') !== false) {
                                $template = str_replace($val, "'.".$binder.".'", $template);
                            }else{
                                $setting = FileReader::SettingGetter();
                                if(array_key_exists($binder, $setting)){
                                    $template = str_replace("{{".$binder."}}", $setting[$binder], $template);
                                }else{
                                    $template = $val;
                                }
                            }
                        }
                        $this->code .= '$this->prepared .= \''.$template.'\';';
                    }else{
                        $pattern = '/\{{.+?\}}/';
                        preg_match_all($pattern, $template, $variable);
                        foreach($variable[0] as $val){
                            $binder = str_replace('{{', '', $val);
                            $binder = str_replace('}}', '', $binder);
                            $binder = trim($binder);
                            if (isset($$binder)) {
                                $template = str_replace($val, '".'.$$binder.'."', $template);
                            }else{
                                $setting = FileReader::SettingGetter();
                                if(array_key_exists($binder, $setting)){
                                    $template = str_replace("{{".$binder."}}", $setting[$binder], $template);
                                }else{
                                    $template = $val;
                                }
                            }
                        }
                        $this->code .= '$this->prepared .= "'.$template.'";';
                        $this->code .= "\n";
                    }
                }else{
                    $pattern = '/\{{.+?\}}/';
                    preg_match_all($pattern, $template, $variable);
                    $isReplaced = False;
                    foreach($variable[0] as $val) {
                        $binder = str_replace('{{', '', $val);
                        $binder = str_replace('}}', '', $binder);
                        $binder = str_replace('$', '', $binder);
                        $binder = trim($binder);
                        if (isset($$binder)) {
                            $template = str_replace($val, $$binder, $template);
                        }else{
                            $setting = FileReader::SettingGetter();
                            if(array_key_exists($binder, $setting)){
                                $template = str_replace("{{".$binder."}}", $setting[$binder], $template);
                            }else{
                                $template = $val;
                            }
                        }
                    }
                    $this->prepared .= $template;
                    }
                }
            }
        return True;
    }

    public function GetGregorioVersion(){
        return [1, "1.0.0"];
    }

}