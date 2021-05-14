<?php
    require_once('personClasses.php');
    require_once('../Methods.php');

    session_start();

    if(empty($_SESSION['person'])){
        header("location:../Logout.php");
    }
    else{
        $person = $_SESSION['person'];
    }

    $response = "";
    
    $pid = $person->getPid();

    $db = connectToDb();

    $query = $db->query("SELECT * FROM PERSONS WHERE PID = $pid");

    if($query->num_rows == 1){
        $response.=
        "<div id=\"personDataBlock\">"
        ."<table>"
            ."<tr>"
                ."<td>Név:</td><td><input type=\"text\" id=\"newName\" value=\"".$person->getName()."\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td>E-mail cím:</td><td><input type=\"text\" id=\"newEmail\" value=\"".$person->getEmail()."\"/></td>"
            ."</tr>";
        if(get_class($person) == "User"){
            $response .=
            "<tr>"
                ."<td>Cég:</td><td><input type=\"text\" id=\"newCompany\" value=\"".$person->getCompany()."\"/></td>"
            ."</tr>";
        }
        $response .=
            "<tr>"
                ."<td>Jelszó:</td><td><input type=\"password\" id=\"password\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td colspan = \"2\"><button type = \"submit\" id=\"saveDataEditBtn\">Adatok módosítása</button></td>"
            ."</tr>"
        ."</table>"
        ."<table>"
            ."<tr>"
                ."<td>Jelenlegi jelszó:</td><td><input type=\"password\" id=\"currentPassword\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td>Új jelszó:</td><td><input type=\"password\" id=\"newPassword\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td>Új jelszó megerősítése:</td><td><input type=\"password\" id=\"reNewPassword\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td colspan = \"2\"><button type = \"submit\" id=\"savePwEditBtn\">Jelszó módosítása</button></td>"
        ."</tr>"
        ."</table>"
        ."<table>"
            ."<tr>"
                ."<td>Fiók törlése:</td><td><button class=\"removePersonBtn\" id=\"".$pid."\" onclick=\"areYouSure(event)\">Fiók törlése</button></td>"
            ."</tr>"
        ."</table></div>";
    }
    else{
        $response = "Hiba történt, a fiókod nem szerepel az adatbázisban!";
    }

    echo $response;

    $db->close();

?>