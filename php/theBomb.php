<?php

/**
 * Created by PhpStorm.
 * User: tmgreyHat
 * Date: 8/17/2018
 * Time: 8:31 AM
 */
require "Config.php";

/**
 * Class theBomb
 */
class theBomb extends  Config {


    public $sqlQuery;

    //public $theCon;

    /**
     * @return mixed
     */
    public function getSqlQuery()
    {
        return $this->sqlQuery;
    }

    /**
     * @param mixed $sqlQuery
     */
    public function setSqlQuery($sqlQuery)
    {
        $this->sqlQuery = $sqlQuery;
    }





    /**
     * @param $tableName
     * @param $cols
     * @param $values
     * @return string
     */
    public function universalSaveData($tableName, $cols, $values){

        $theCon =self::getTheCon();

        $sqlQuery = "insert into $tableName ($cols) VALUES ($values)";

        if($theCon->exec($sqlQuery)){

        }else{

            echo $theCon->errorInfo()."<hr/>";

        }

        return $theCon->lastInsertId();

    }


    /**
     * @param $tableName
     * @param $assoc
     * @return string
     */
    public function saveData($tableName, $assoc){

        $theCon =self::getTheCon();

        $fields_arr = array();

        foreach ($assoc as $key=>$val){

            array_push($fields_arr, "". $key ."");

        }

        $fields = implode(",", $fields_arr);

        $placeHolders = array();

        foreach ($assoc as $key =>$val) {

            array_push($placeHolders, "'".$val."'");

        }


        $values = implode(",", $placeHolders);

        $sql = "insert into $tableName ($fields) values  ($values) ";

       // echo $sql; die;
        $theCon->exec($sql);


        return  $theCon->lastInsertId();




    }


    public function update($table, $assoc, $conditions){

        $cols = array();

        foreach ($assoc as $key=>$val) {

            $cols[] = " $key= '$val' ";
        }

        //universalUpdate($tableName, $newValues, $conditions)
//        $sql = "update $table SET ".implode(',', $cols)."$conditions";

        return var_dump($cols);

        //return $this->universalUpdate($table, implode(',', $cols), "$conditions");

    }




    /**
     * @param $sqlQuery
     * @return array
     */
    public function universalFetchData($sqlQuery){

        $theCon =self::getTheCon();

        $result = array();

        $statement = $theCon->prepare($sqlQuery);

        $statement->execute();

        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }

        return $result;

    }



    public function  fetchColumns($tableName, $columns){

        $this->setSqlQuery("select  $columns from  $tableName");

        $theCon =self::getTheCon();

        $result = array();

        $statement = $theCon->prepare($this->getSqlQuery());

        $statement->execute();

        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }

        return $result;

    }

    public function fetchWithConditions($tableName, $columns, $conditions){
        $this->setSqlQuery("select $columns from $tableName where $conditions");
        $theCon =self::getTheCon();
        $result = array();
        $statement = $theCon->prepare($this->getSqlQuery());
        $statement->execute();
        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }
        return $result;
    }

    public function fetchWithJoinsAndConditions($tableName, $columns,$joins, $conditions){

        $this->setSqlQuery("select $columns from $tableName $joins where $conditions");
        $theCon =self::getTheCon();

        $result = array();

        $statement = $theCon->prepare($this->getSqlQuery());

        $statement->execute();

        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }

        return $result;


    }

    public function fetchWithJoins($tableName, $columns, $joins){

        $this->setSqlQuery("select $columns from $tableName $joins");
        $theCon =self::getTheCon();

        $result = array();

        $statement = $theCon->prepare($this->getSqlQuery());

        $statement->execute();

        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }

        return $result;
    }


    public function fetchWithID($tableName, $key, $value){


    }

    public function fetchAll($tableName){

        $this->setSqlQuery("select * from $tableName");
        $theCon =self::getTheCon();

        $result = array();

        $statement = $theCon->prepare($this->getSqlQuery());

        $statement->execute();

        while ($row = $statement->fetch()){

            $result[] = array_map('utf8_encode', $row);
        }

        return $result;


    }



    /**
     * @param $sqlQuery
     * @return int
     */
    public function numberOfRecords($sqlQuery){

        $theCon =self::getTheCon();

        $statement = $theCon->prepare($sqlQuery);

        $statement->execute();

        return $statement->rowCount();

    }

    public function totalRecordsMatching($tableName, $columns, $conditions){

        $theCon =self::getTheCon();
        $this->setSqlQuery("select $columns from $tableName where $conditions");

        $statement = $theCon->prepare($this->getSqlQuery());

        $statement->execute();
        return $statement->rowCount();


    }

    /**
     * @param $tableName
     * @param $keyColumn
     * @param $keyValue
     * @return int
     */
    public function universalDelete($tableName, $keyColumn, $keyValue){
 //$theCon =self::getTheCon();

        return self::getTheCon()->exec("delete from $tableName where $keyColumn = '$keyValue'");


    }


    /**
     * @param $tableName
     * @param $newValues
     * @param $conditions
     * @return int
     */
    public function universalUpdate($tableName, $newValues, $conditions){

        return self::getTheCon()->exec("update $tableName set $newValues where $conditions");
    }




    /**
     * @param $tableName
     * @return int
     */
    public function cleanTableData($tableName){

        $theCon =self::getTheCon();

        return $theCon->exec("delete from $tableName");


    }

    public function cleanData($data){


        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');


        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);


        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);


        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);


        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {    $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        return $data;

    }

    public function randomPass()
    {
        $alphabet = "qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM0987654321";
        $pass = array();

        $alphalen = strlen($alphabet)-1;

        for ($i=0; $i<9; $i++){

            $n = rand(0, $alphalen);
            $pass [] = $alphabet[$n];
        }

        return  implode($pass);


    }

    public function sendSMS($destinations, $message){


// BulkSMS Webservice username for sending SMS.
//Get it from User Configuration. Its case sensitive.

        $username = 'tapstango007';

// Webservices token for above Webservice username
        $token = '0fe906e0c3459933743a3ad5a3bc4b82';

// BulkSMS Webservices URL
        $bulksms_ws = 'http://portal.bulksmsweb.com/index.php?app=ws';


        // send via BulkSMS HTTP API

        $ws_str = $bulksms_ws . '&u=' . $username . '&h=' . $token . '&op=pv';
        $ws_str .= '&to=' . urlencode($destinations) . '&msg='.urlencode($message);
        $ws_response = @file_get_contents($ws_str);

        //echo $ws_response;

        return 1;
    }



}