<?php
/**
 * Created by PhpStorm.
 * User: tapiwanashem
 * Date: 20/10/2019
 * Time: 22:19
 */

class Artist extends DBModel {


    private $songs;


    /**
     * @return mixed
     */
    public function getSongs(){

        return $this->songs;
    }

    /**
     * @param mixed $songs
     */
    public function setSongs($songs)
    {
        $this->songs = $songs;
    }







}