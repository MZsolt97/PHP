<?php

    require_once('Methods.php');

    $db = connectToDb();

    $query = $db->query("SELECT TYPENAME FROM ERRORTYPES");

    $options = "";

    if($query->num_rows > 0){
        while($row = $query->fetch_array()){
            $options .= "<option>".$row['TYPENAME']."</option>";
        }
    }

    $db->close();
    
    echo $options;
?>