<?php

    require_once('../Methods.php');

    $db = connectToDb();

    $query = $db->query("SELECT TYPENAME FROM ERRORTYPES");

    $response = "<div id=\"errorTypeList\"><table><tr><th>Hiba kategória neve</th></tr>";

    if($query->num_rows > 0){
        while($row = $query->fetch_array()){
            $response .= "<tr><td>".$row['TYPENAME']."</td></tr>";
        }
    }
    else{
        $response.="<tr><td>Nincs hiba kategória az adatbázisban!</td></tr>";
    }

    $response.="</table></div>";

    $db->close();
    
    echo $response;
?>