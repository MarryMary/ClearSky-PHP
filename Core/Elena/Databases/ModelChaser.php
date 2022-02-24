<?php
/*
* ModelChaser Class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd).
* This class is providing model system.
* For example, fetch data from sql based on model class name, fetched data to object, and so on.
*/
namespace Clsk\Elena\Databases;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Databases\QueryBuilder;

class ModelChaser
{
    private $col = "*";
    private $val = array();
    private $up = array();
    // Fetch method is most simply function for get data from database.
    // This function is get the first row.
    public function Fetch(Bool $object = true, Bool $where=false, String $terms1="", String $is="=", String $terms2="", Bool $option = false)
    {
        // $where variable is switch of specify the terms or not specify it.
        // Default is "false"(not specify the terms).
        if($where){
            $fetch = QueryBuilder::Table($this->TableChaser())->Where($terms1, $is, $terms2, $option)->Fetch();
        }else{
            $fetch = QueryBuilder::Table($this->TableChaser())->Fetch();
        }

        // $object variable is switch of returned object type or array type.
        // Default is "false"(returned array data.).
        if($object){
            return (object)$fetch;
        }else{
            return $fetch;
        }
    }

    // FetchAll method is get all data from database.
    public function FetchAll(Bool $object = true, Bool $where=false, String $terms1="", String $is="=", String $terms2="", Bool $option = false)
    {
        if($where){
            $fetchall = QueryBuilder::Table($this->TableChaser())->Where($terms1, $is, $terms2, $option)->Fetch("All");
        }else{
            $fetchall = QueryBuilder::Table($this->TableChaser())->Fetch("All");
        }
        if($object){
            return (object)$fetchall;
        }else{
            return $fetchall;
        }
    }

    // Count method is count the rows that match the condition
    public function Count(Bool $where=false, String $terms1="", String $is="=", String $terms2="", Bool $option = false){
        if($where){
            return QueryBuilder::Table($this->TableChaser())->Where($terms1, $is, $terms2, $option)->CountRow();
        }else{
            return QueryBuilder::Table($this->TableChaser())->CountRow();
        }
    }

    // Insert method is insert data to database.
    // If you want to using Insert method, you must call the Val method or Set method before calling Insert method.
    public function Insert(String $mode = "set")
    {
        // $mode variable is switch of data source.
        // If you called Set method, $mode variable must specify the "set".
        // If you called Val method, $mode variable must specify the "val" or other.
        if($mode == "set"){
            QueryBuilder::Table($this->TableChaser())->Col($this->col)->Insert($this->up);
            return true;
        }else{
            QueryBuilder::Table($this->TableChaser())->Col($this->col)->Insert($this->val);
            return true;
        }
    }

    // Col method is specify column of you want to operation.
    public function Col(String $string){
        $this->col = $string;
        return $this;
    }

    // Set method is enter data one by one.
    // You must call this before call to Insert method or Update method.
    public function Set(String $k, String $v){
        $this->up[$k] = $v;
    }

    // Update method is update data on database.
    // If you call this method, you must call the "Set" method.
    public function Update(){
        QueryBuilder::Table($this->TableChaser())->Update($this->up);
        return true;
    }

    // Delete method is delete data on table.
    // This method can't all delete from a safety point of view.
    // You must specify terms.
    public function Delete(String $term1, String $type, String $term2, Bool $option=false){
        QueryBuilder::Table($this->TableChaser())->Where($term1, $type, $term2, $option)->Delete();
        return True;
    }

    //Val method is enter data at once.
    // You must call this before call to Insert method.
    public function Val(String $string){
        array_push($this->val, $string);
    }

    // Table chaser is tracks the table you want to work with from the model class name you decide or the table name you specify in the model.
    private function TableChaser()
    {
        // Here, the caller is searched, and if a table name is specified separately, that is given priority and used.
        $table = get_class($this);
        $table = explode("\\", $table);
        if(isset($this->table)){
            $table = $this->table;
        }else{
            $table = $table[3];
        }
        return $table;
    }
}