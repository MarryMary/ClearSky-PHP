<?php
/*
* QueryBuilder class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd)
* This class is create SQL statement and execution it.
*/
namespace Clsk\Elena\Databases;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Tools\FileReader;
use Clsk\Elena\Exception\ExceptionThrower\ClearSkyQueryBuilderException;

class QueryBuilder{
    private static $debug;
    private static $table;
    private static $col;
    private static $TermsQuery;
    private static $terms = array();
    private static $WhereCalled = False;
    private static $JoinQuery;
    private static $pdo;
    private static $query;
    private static $stmt;
    public function __construct()
    {
        self::$query = "";
        self::$TermsQuery = "";
        self::$JoinQuery = "";
        self::$terms = array();
        self::$WhereCalled = false;
        self::$debug = false;
        self::$stmt = NULL;
        self::$col = ["*"];
        $settings = FileReader::SettingGetter();

        self::$pdo = new \PDO($settings['DBEngine'].':dbname='.$settings['DBName'].';host='.$settings['DBHost'].';charset='.$settings['DBChr'].";port=".$settings['DBPort'], $settings['DBUser'], $settings['DBPass']);
    }

    public static function Table(String $table, Bool $debug = False)
    {
        if(trim($table) != "") {
            self::$table = $table;
            self::$debug = $debug;
            if(FileReader::SettingGetter()["ApplicationTestMode"]){
                self::$debug = True;
            }
            return new static;
        }
        else{
            throw new ClearSkyQueryBuilderException('$table引数へ空白を渡すことは許可されていません。');
            exit(1);
        }
    }

    public function Column(Array $col)
    {
        foreach($col as $cols){
            if(trim($cols) == ""){
                throw new ClearSkyQueryBuilderException('$col引数の配列に空白を混ぜて渡すことは許可されていません。');
                exit(1);
            }
        }
        self::$col = $col;
        return $this;
    }

    public function Where(String $Col, String $Is, String $Term, Bool $IsNot = False)
    {
        if(trim($Col) != "" and trim($Is) != "" and trim($Term) != "") {
            $keyword = " WHERE";
            if ($IsNot) {
                $keyword = " WHERE NOT";
            }
            self::$WhereCalled = True;
            array_push(self::$terms, $Term);
            self::$TermsQuery = $keyword . " `" . trim($Col) . "` " . trim($Is) . " ? ";
            return $this;
        }else{
            throw new ClearSkyQueryBuilderException('$Col引数、$Is引数、$Term引数それぞれに空白を渡すことは許可されていません。');
        }
    }

    public function And(String $Col, String $Is, String $Term, Bool $IsNot = False)
    {
        if(trim($Col) != "" and trim($Is) != "" and trim($Term) != "") {
            if(self::$WhereCalled) {
                $keyword = "AND";
                if ($IsNot) {
                    $keyword = "AND NOT";
                }
                self::$WhereCalled = True;
                array_push(self::$terms, $Term);
                self::$TermsQuery = $keyword . " `" . trim($Col) . "` " . trim($Is) . " ? ";
                return $this;
            }else{
                throw new ClearSkyQueryBuilderException('Whereメソッド呼び出し前にAndメソッドを使用することは許可されていません。');
            }
        }else{
            throw new ClearSkyQueryBuilderException('$Col引数、$Is引数、$Term引数それぞれに空白を渡すことは許可されていません。');
        }
    }

    public function Or(String $Col, String $Is, String $Term, Bool $IsNot = False)
    {
        if(trim($Col) != "" and trim($Is) != "" and trim($Term) != "") {
            if(self::$WhereCalled) {
                $keyword = "OR";
                if ($IsNot) {
                    $keyword = "OR NOT";
                }
                self::$WhereCalled = True;
                array_push(self::$terms, $Term);
                self::$TermsQuery = $keyword . " `" . trim($Col) . "` " . trim($Is) . " ? ";
                return $this;
            }else{
                throw new ClearSkyQueryBuilderException('Whereメソッド呼び出し前にOrメソッドを使用することは許可されていません。');
            }
        }else{
            throw new ClearSkyQueryBuilderException('$Col引数、$Is引数、$Term引数それぞれに空白を渡すことは許可されていません。');
        }
    }

    public function Join(String $Table2Name, String $Table2ColName, String $TableColName, Bool $OUTER = False, Bool $LEFT = True)
    {
        if(trim($Table2Name) != "" and trim($Table2ColName) != "" and trim($TableColName) != ""){
            $keyword = "INNER JOIN";
            if($OUTER){
                if($LEFT) {
                    $keyword = "LEFT OUTER JOIN";
                }else{
                    $keyword = "RIGHT OUTER JOIN";
                }
            }
            self::$JoinQuery = $keyword . " `" . $Table2Name . "` ON `" . $Table2Name . "`.`" . $Table2ColName . "` = `" . self::$table . "`.`" . $TableColName;
            return $this;
        }else{
            throw new ClearSkyQueryBuilderException('$Table2Name引数、$Table2ColName引数、$TableColName引数それぞれに空白を渡すことは許可されていません。');
        }
    }

    public function Fetch(Bool $mode = False,Bool $OtherQueryUse = False, Bool $IsHTMLSPECIALCHARS = True,Bool $IsOrderBy = False, String $OrderBy = "DESC", String $IsColumn = "Id")
    {
        $orderby = "";
        if($IsOrderBy){
            if(trim($OrderBy) != "" and trim($IsColumn) != ""){
                $orderby = "`".$IsColumn."` DESC";
            }else{
                throw new ClearSkyQueryBuilderException('$IsOrderByフラグをTrueにした場合、$OrderBy引数、$IsColumn引数それぞれに空白を渡すことは許可されていません。');
            }
        }
        $cols = "";
        foreach(self::$col as $col){
            $cols .= $col.", ";
        }
        $cols = rtrim($cols, ",");
        self::$query = "SELECT ".rtrim(trim($cols), ",")." FROM ".trim(self::$table).self::$JoinQuery.self::$TermsQuery.$orderby;
        self::$stmt = self::$pdo->prepare(self::$query);
        foreach(self::$terms as $terms => $values){
            $types = [
                "boolean" => \PDO::PARAM_BOOL,
                "string" => \PDO::PARAM_STR,
                "float" => \PDO::PARAM_STR,
                "integer" => \PDO::PARAM_INT
            ];
            foreach($types as $key=>$pdo_mode) {
                if(gettype($terms) == $key){
                    if($IsHTMLSPECIALCHARS){
                        $values = htmlspecialchars($values);
                    }
                    self::$stmt->bindValue($terms+1, $values, $pdo_mode);
                    break;
                }
            }
        }
        if(self::$debug){
            return $this;
        }else{
            if($mode){
                self::$stmt->execute();
                if($OtherQueryUse){
                    return [self::$stmt->fetch(), $this];
                }else {
                    return [self::$stmt->fetch()];
                }
            }else{
                self::$stmt->execute();
                if($OtherQueryUse){
                    return [self::$stmt->fetchAll(), $this];
                }else {
                    return [self::$stmt->fetchAll()];
                }
            }
        }
        return False;
    }

    public function Delete()
    {
        self::$query = "DELETE FROM ".trim(self::$table).self::$JoinQuery.self::$TermsQuery;
        self::$stmt = self::$pdo->prepare(self::$query);
        foreach(self::$terms as $terms => $values) {
            $types = [
                "boolean" => \PDO::PARAM_BOOL,
                "string" => \PDO::PARAM_STR,
                "float" => \PDO::PARAM_STR,
                "integer" => \PDO::PARAM_INT
            ];
            foreach ($types as $key => $pdo_mode) {
                if (gettype($terms) == $key) {
                    self::$stmt->bindValue($terms + 1, $values, $pdo_mode);
                    break;
                }
            }
        }
        if(self::$debug){
            return [True, $this];
        }else{
            self::$stmt->execute();
            return [True];
        }
    }

    public function LastInsertId()
    {
        return self::$pdo->lastInsertId();
    }

    public function Update(Array $set, Bool $IsHTMLSPECIALCHARS = True)
    {
        $values = array();
        $setquery = "";
        foreach($set as $col=>$value){
            if($IsHTMLSPECIALCHARS){
                $value = htmlspecialchars($value);
            }
            array_push($values, $value);
            $setquery .= $col." = ?,";
        }
        $setquery = rtrim($setquery, ",");
        self::$query = "UPDATE ".trim(self::$table)." SET ".trim($setquery)." FROM ".trim(self::$table).self::$JoinQuery.self::$TermsQuery;
        foreach(self::$terms as $terms => $values) {
            $types = [
                "boolean" => \PDO::PARAM_BOOL,
                "string" => \PDO::PARAM_STR,
                "float" => \PDO::PARAM_STR,
                "integer" => \PDO::PARAM_INT
            ];
            foreach ($types as $key => $pdo_mode) {
                if (gettype($values) == $key) {
                    self::$stmt->bindValue($terms + 1, $values, $pdo_mode);
                    break;
                }
            }
        }
        if (self::$debug) {
            return [True, $this];
        } else {
            self::$stmt->execute();
            return [True];
        }
    }

    public function Insert(Array $insert_value, Bool $IsHTMLSPECIALCHARS)
    {
        $values = array();
        $setquery = "";
        $paramquery = "";
        foreach($insert_value as $col => $value){
            if($IsHTMLSPECIALCHARS){
                $value = htmlspecialchars($value);
            }
            array_push($values, $value);
            $setquery .= $col.",";
            $paramquery .= "?,";
        }
        $setquery = rtrim($setquery, ",");
        self::$query = "INSERT INTO ".trim(self::$table)." (".trim($setquery).") VALUES (".rtrim(trim($paramquery), ",").")";
        self::$stmt = self::$pdo->prepare(self::$query);
        echo self::$query;
        foreach($values as $k => $v){
            $types = [
                "boolean" => \PDO::PARAM_BOOL,
                "string" => \PDO::PARAM_STR,
                "float" => \PDO::PARAM_STR,
                "integer" => \PDO::PARAM_INT
            ];
            foreach ($types as $type => $pdo_mode) {
                if (gettype($v) == $type) {
                    self::$stmt->bindValue($k + 1, $v, $pdo_mode);
                    break;
                }
            }
        }
        if(self::$debug){
            return [True, $this];
        }else{
            self::$stmt->execute();
            return [True];
        }
    }


    public function CountRow()
    {
        return self::$stmt->rowCount();
    }

    public function Create(Array $columns = array())
    {
        self::$query = "CREATE TABLE ".self::$table;
        $queries = "(";
        foreach($columns as $key => $value){
            $queries .= "`".$key."` ".$value.",";
        }
        $queries = rtrim($queries, ",");
        $queries .= ")";
        self::$query = self::$query.$queries;
        self::$stmt = self::$pdo->prepare(self::$query);
        if(!self::$debug){
            self::$stmt->execute();
            return [True];
        }else{
            return [True, $this];
        }
    }

    public function Drop(Bool $IfExists = True)
    {
        $exists = "";
        if($IfExists){
            $exists = "IF EXISTS";
        }

        $query = "DROP TABLE ".$exists." ".trim(self::$table);
        self::$pdo->prepare($query)->execute();
        return [True];
    }

    public function ToSQL(Bool $view=False)
    {
        if($view){
            echo self::$query;
        }else{
            return [self::$query];
        }
    }

    public function GetParams(Bool $view=False){
        if($view){
            var_dump(self::$terms);
        }else{
            return [self::$terms];
        }
    }
}
