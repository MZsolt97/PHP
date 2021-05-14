<?php
    require_once('../Methods.php');
    require_once('../PersonControl/personClasses.php');

    session_start();
    
    if(empty($_SESSION['person'])){
        header("location:../Logout.php");
    }
    else{
        $person = $_SESSION['person'];
    }

    $response = "";

    $db = connectToDb();


    if(get_class($person) == "User"){

        $response =
        "<div id=\"errorTicketListBlock\">"
        ."<table>"
            ."<tr>"
                ."<td>Hiba kategória:</td><td><select id=\"selectedError\"><option></option>".getErrorTypeList($db)."</select></td>"
            ."</tr>"      
        ."</table>"
        ."<table>"
            ."<tr>"
                ."<th>Hiba kategória</th><th>Hiba tárgya</th><th>Hiba beküldésének ideje</th><th>Feldolgozás státusza</th>"
            ."</tr>";

        $pid = $person->getPid();

        if(isset($_GET['selectedError']) && !empty($_GET['selectedError'])){
            $selectedError = $_GET['selectedError'];
            $query = $db->query("SELECT
                                err.TYPENAME,
                                er.TID,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.PID = $pid AND er.SID = s.SID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                WHERE err.TYPENAME = '$selectedError'
                                ORDER BY ER.CREATIONTIME DESC"
                            );
        }
        else{
            $query = $db->query("SELECT
                                err.TYPENAME,
                                er.TID,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.PID = $pid AND er.SID = s.SID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                ORDER BY ER.CREATIONTIME DESC"
                            );
        }

        if($query->num_rows != 0){
            while($row = $query->fetch_array()){
                $response.="<tr><td>".$row['TYPENAME']."</td>"
                ."<td>".$row['SUBJECT']."</td>"
                ."<td>".$row['CREATIONTIME']."</td>"
                ."<td>".$row['STATUS']."</td>"
                ."<td><a class=\"open\" id=\"".$row['TID']."\">Megtekintés</a></td></tr>";
            }
        }
        else{
            $response .= "<tr><td colspan = \"4\">Nincs a megadott szűrési feltételeknek megfelelő hibajegy az adatbázisban!</td></tr>";
        }
        
    }
    else{
        $response =
        "<div id=\"errorTicketListBlock\">"
        ."<table>"
            ."<tr>"
                ."<td>E-mail cím:</td><td><input type=\"text\" id=\"email\"/></td>"
            ."</tr>"
            ."<tr>"
                ."<td>Hiba kategória:</td><td><select id=\"selectedError\"><option></option>".getErrorTypeList($db)."</select></td>"
            ."</tr>"       
        ."</table>"
        ."<table>"
            ."<tr>"
                ."<th>Felhasználó email címe</th><th>Hiba kategória</th><th>Hiba tárgya</th><th>Hiba beküldésének ideje</th><th>Feldolgozás státusza</th>"
            ."</tr>";
        
        if(isset($_GET['selectedError']) && !empty($_GET['selectedError']) && isset($_GET['email']) && !empty($_GET['email'])){
            $selectedError = $_GET['selectedError'];
            $email = "%".$_GET['email']."%";
            $query = $db->query("SELECT
                                    err.TYPENAME,
                                    p.EMAIL,
                                    er.TID,
                                    er.SUBJECT,
                                    er.CREATIONTIME,
                                    s.STATUS
                                    FROM errortickets er INNER JOIN status s
                                    ON er.SID = s.SID
                                    INNER JOIN persons P
                                    ON p.PID = er.PID
                                    INNER JOIN errortypes err
                                    ON err.ERRID = er.ERRID
                                    WHERE err.TYPENAME = '$selectedError'
                                    AND p.EMAIL LIKE '$email'
                                    ORDER BY er.CREATIONTIME DESC"
                                );
        }
        else if(isset($_GET['email']) && !empty($_GET['email'])){
            $email = "%".$_GET['email']."%";
            $query = $db->query("SELECT
                                    err.TYPENAME,
                                    p.EMAIL,
                                    er.TID,
                                    er.SUBJECT,
                                    er.CREATIONTIME,
                                    s.STATUS
                                    FROM errortickets er INNER JOIN status s
                                    ON er.SID = s.SID
                                    INNER JOIN persons p
                                    ON p.PID = er.PID
                                    INNER JOIN errortypes err
                                    ON err.ERRID = er.ERRID
                                    WHERE p.EMAIL LIKE '$email'
                                    ORDER BY er.CREATIONTIME DESC"
                                );
        }
        else if(isset($_GET['selectedError']) && !empty($_GET['selectedError'])){
            $selectedError = $_GET['selectedError'];
            $query = $db->query("SELECT
                                err.TYPENAME,
                                p.EMAIL,
                                er.TID,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.SID = s.SID
                                INNER JOIN persons P
                                ON p.PID = er.PID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                WHERE err.TYPENAME = '$selectedError'
                                ORDER BY er.CREATIONTIME DESC"
                            );
        }
        else{
            $query = $db->query("SELECT
                                err.TYPENAME,
                                p.EMAIL,
                                er.TID,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.SID = s.SID
                                INNER JOIN persons P
                                ON p.PID = er.PID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                ORDER BY er.CREATIONTIME DESC"
                            );
        }

        if($query->num_rows != 0){
            while($row = $query->fetch_array()){
                $response.="<tr><td>".$row['EMAIL']."</td><td>".$row['TYPENAME']."</td><td>".$row['SUBJECT']."</td><td>".$row['CREATIONTIME']."</td><td>".$row['STATUS']."</td><td><a class=\"open\" id=\"".$row['TID']."\">Megtekintés</a></td></tr>";
            }
        }
        else{
            $response.="<tr><td colspan = \"5\">Nincs a megadott szűrési feltételeknek megfelelő hibajegy az adatbázisban!</td></tr>";
        }
    }

    $response.="<table/></div>";

    $db->close();

    if((isset($_GET['selectedError']) && !empty($_GET['selectedError'])) || (isset($_GET['email']) && !empty($_GET['email']))){
        if(isset($_GET['email']) && !empty($_GET['email'])){
            echo json_encode(['msg'=>$response, 'selectedError'=>$_GET['selectedError'], 'email'=>$_GET['email']]);
        }
        else{
            echo json_encode(['msg'=>$response, 'selectedError'=>$_GET['selectedError']]);
        }
        
    }
    else if((isset($_GET['selectedError']) && empty($_GET['selectedError'])) || (isset($_GET['email']) && empty($_GET['email']))){
        echo json_encode(['msg'=>$response]);
    }
    else{
        echo $response;
    }
    
    function getErrorTypeList($db){
        $query = $db->query("SELECT TYPENAME FROM ERRORTYPES");

        $options = "";

        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                $options .= "<option>".$row['TYPENAME']."</option>";
            }
        }
        return $options;
    }

?>