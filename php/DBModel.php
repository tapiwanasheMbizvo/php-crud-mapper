<?php
/**
 * Created by PhpStorm.
 * User: tmgreyHat
 * Date: 1/18/2019
 * Time: 10:42 AM
 */


/**
 * Class DBModel
 *
 * this model is the one responsible for implementing basic CRUD ops , so every
 * other Object shall be inheriting from this model
 */
class DBModel{

    public $theHand;
    public $table;
    public $key;
    public $response = array();


    /**
     * DBModel constructor.
     * @param $theHand
     * @param $table
     * @param $key
     *
     * When constructing a db model, we need the table name, and the primary key of that table,
     * thehand is the db handler with the db functions
     */
    public function __construct($theHand, $table, $key)
    {
        $this->theHand = $theHand;
        $this->table = $table;
        $this->key = $key;
    }


    /**
     * @param $assoc
     * @return false|string
     */
    public function create($assoc){

        $this->theHand->saveData($this->table, $assoc)>0 ? $this->response["success"] = true: $this->response["success"] =false;

        return json_encode($this->response);



    }

    /**
     * @param $id
     * @return false|string
     */
    public function getOne($id){

        $vars = $this->theHand->universalFetchData("select * from ".$this->table." where ".$this->key." = $id ");
        $var = array_pop($vars);

        return json_encode($var);

    }

    /**
     * @return false|string
     */
    public function getAll(){

        return json_encode($this->theHand->fetchAll($this->table));
    }

    /**
     * @param $id
     * @return false|string
     */
    public function delete($id){

        $this->theHand->universalDelete($this->table, $this->key, $id)>0 ? $this->response["success"]= true: $this->response["success"]= false;

        return json_encode($this->response);
    }

    /**
     * @return false|string
     */
    public function deleteAll(){

        $this->theHand->cleanTabledata($this->table)>0 ? $this->response["success"] = true: $this->response["success"]= false;

        return json_encode($this->response);
    }


    /**
     * @param $data
     * @param $id
     * @return false|string
     */
    public function update($data, $id){

        $cols = array();

        //return var_dump($data);


        foreach ($data as $key=>$val) {


            $cols [] = "$key= '$val' ";
        }

        $sql = "update $this->table SET ". implode(',', $cols)."  where $this->key= $id ";

        $this->theHand->getTheCon()->exec($sql)>0? $this->response["success"]= true: $this->response["success"]= false;

        return json_encode($this->response);

    }

    /**
     * @param $conditions
     * @param $columns
     * @return false|string
     */
    public function withConditions($columns,$conditions){


        return json_encode($this->theHand->fetchWithConditions($this->table,$columns,$conditions));

    }
    /**
     * @param $columns
     * @param $joins
     * @return false|string 
     */
    public function withJoins($columns, $joins){

      return json_encode($this->theHand->fetchWithJoins($this->table, $columns, $joins));


    }



    /**
     * checks to see if email, username, phone number is available
     * @param $column
     * @param $value
     * @return bool
     */
    public function isAvailable($column, $value){

        $vars = $this->theHand->fetchWithConditions($this->table,$column, "$column='$value'");

      return count($vars)==0;


    }

    /**
     * @param $columns
     * @param $joins
     * @param $conditions
     * @return false|string
     */
    public function joinsAndConditions($columns, $joins, $conditions){


        return json_encode($this->theHand->fetchWithJoinsAndConditions($this->table,$columns,$joins ,$conditions));
    }

}