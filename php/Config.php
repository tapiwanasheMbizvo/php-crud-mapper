<?php
/**
 * Created by PhpStorm.
 * User: tmgreyHat
 * Date: 8/17/2018
 * Time: 8:26 AM
 */
class Config{


public function getTheCon(){


        $config = parse_ini_file("config.ini");
        $server = $config["server"];
        $database = $config["database"];
        $user = $config["user"];
        $password = $config["password"];

    try{
        $con = new PDO("mysql:host=$server;dbname=$database", $user, $password);

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }catch (PDOException $exception){

                echo "Connection failed ". $exception->getMessage();
            }

    if (!empty($con)) {
        return $con;
    }

        }



}