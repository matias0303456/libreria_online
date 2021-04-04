<?php

class Database{

    public static function connect(){
        $db = new mysqli('localhost', 'root', '', 'libreria_online');
        $db->query('set names utf8');

        return $db;

    }

}