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

    $db = connectToDb();
    
    $response =
    "<div id=\"personList\">"
    ."<table id=\"personFilter\">"
        ."<tr>"
            ."<td>E-mail cím:</td><td><input type=\"text\" id=\"email\"/></td>"
        ."</tr>"
        ."<tr>"
            ."<td>Cég:</td><td><input type=\"text\" id=\"company\"/></td>"
        ."</tr>"
    ."</table>"
    ."<table id=\"list\">"
        ."<tr>"
            ."<th>Név</th><th>E-mail cím</th><th>Cég</th><th>Fiók típus</th>"
        ."</tr>";

    
    if(isset($_GET['email']) && !empty($_GET['email'])){
        $email = "%".$_GET['email']."%";
    }
    else if(isset($_GET['email']) && empty($_GET['email'])){
        $email = "%";
    }
    if(isset($_GET['company']) && !empty($_GET['company'])){
        $company = "%".$_GET['company']."%";
    }
    else if(isset($_GET['company']) && empty($_GET['company'])){
        $company = "%";
    }

    if(isset($_GET['email']) && empty($_GET['company'])){
        $query = $db->query("SELECT
                                PID,
                                NAME,
                                EMAIL,
                                COMPANY,
                                ADMIN
                                FROM PERSONS
                                WHERE EMAIL LIKE '$email'
                                ORDER BY ADMIN DESC
                            ");
    }
    else if(isset($_GET['email']) && !empty($_GET['company'])){
        $query = $db->query("SELECT
                                PID,
                                NAME,
                                EMAIL,
                                COMPANY,
                                ADMIN
                                FROM PERSONS
                                WHERE EMAIL LIKE '$email' AND COMPANY LIKE '$company'
                                ORDER BY ADMIN DESC
                            ");
    }
    else{
        $query = $db->query("SELECT PID, NAME, EMAIL, COMPANY, ADMIN FROM PERSONS ORDER BY ADMIN DESC");
    }

    if($query->num_rows > 0){
        while($row = $query->fetch_array()){
            if($row['PID'] != $person->getPid()){
                if($row['ADMIN'] == 1){
                    $admin = "Admin";
                    $company = "";
                }
                else{
                    $admin = "Felhasználó";
                    $company = $row['COMPANY'];
                }
                $response.=
                "<tr>"
                    ."<td>".$row['NAME']."</td>"
                    ."<td>".$row['EMAIL']."</td>"
                    ."<td>".$company."</td>"
                    ."<td>".$admin."</td>"
                    ."<td><a class=\"editPersonBtn\" id=\"".$row['PID']."\">Módosítás</a></td>"
                    ."<td><a class=\"removePersonBtn\" id=\"".$row['PID']."\" onclick=\"areYouSure(event)\">Törlés</a></td>"
                ."</tr>";
            }
        }
    }

    else{
        $response.="<tr><td colspan=\"4\">A megadott szűrőkkel nem található fiók!</td></tr>";
    }

    $response.="</table></div>";

    if(isset($_GET['email'])){
        echo json_encode(['msg'=>$response, 'email'=>$_GET['email'], 'company'=>$_GET['company']]);
    }
    else{
        echo $response;
    }

    $db->close();

?>