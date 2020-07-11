
<?php
/**
 * Created by PhpStorm.
 * User: tapiwanashem
 * Date: 20/10/2019
 * Time: 22:38
 */



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With ');
$method = $_SERVER["REQUEST_METHOD"];

if($method=='POST'){


    require_once "theBomb.php";

    $theBomb = new theBomb();
    $userObj = new User($theBomb, "users", "user_id");

    /*
     *
     *
     * private $last_name;
    private $first_name;
    private $user_name;
    private $password;
    private $email;
    private $phone_number;
    private $user_type;
    private $songs;
     * */

    $assoc = array(

        "last_name"=>$_POST['last_name'],
        "first_name"=>$_POST['first_name'],
        "password"=>$_POST['password'],
        "email"=>$_POST['email'],
        "phone_number"=>$_POST['phone_number'],
        "user_type"=>$_POST['user_type']


    );



    $user_id = $userObj->create($assoc);

}

