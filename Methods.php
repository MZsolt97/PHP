<?php

    function connectToDb(){
        $db = new Mysqli("localhost", "root", "", "reportcontroller");

        if (!$db) {
            die("Nem sikerült az adatbázishoz kapcsolódás: " . mysqli_connect_error());
        }
        else{
            $db->query("SET NAMES 'utf8'");
            return $db;
        }
    }

?>
