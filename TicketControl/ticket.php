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

    $tid = $_POST['tid'];

    $db = connectToDb();

    $ticketData  = "";


    if(get_class($person) == 'User'){
        $query = $db->query("SELECT
                                p.EMAIL,
                                err.TYPENAME,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                er.ERRORDESC,
                                er.IMAGE,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.SID = s.SID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                INNER JOIN PERSONS p
                                ON p.PID = er.PID
                                WHERE er.TID = '$tid'"
                            );
        if($query->num_rows == 1){
            $row = $query->fetch_array();
            
            $ticketData = 
            "<div id=\"ticketDataBlock\"><table>"
                ."<tr>"
                    ."<td>A bejelentő e-mail címe:</td><td>".$row['EMAIL']."</td>";

            if(!empty($row['IMAGE'])){
                $image = "<td rowspan =\"6\"><img src=\"/TicketControl/".$row['IMAGE']."\" alt=\"".$row['IMAGE']."\" height = \"200px\" width = \"320px\"></td>";
                $ticketData.=$image;
            }

            $ticketData.=
                "</tr>"
                ."<tr>"
                    ."<td>Tárgy:</td><td>".$row['SUBJECT']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Beküldés ideje:</td><td>".$row['CREATIONTIME']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Hiba kategória:</td><td>".$row['TYPENAME']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Feldolgozás státusza:</td><td>".$row['STATUS']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Hiba leírása:</td><td>".$row['ERRORDESC']."</td>"
                ."</tr>"
            ."</table></div>";

            $answers = getAnswers($tid, $db, $person);

            if($row['STATUS'] == 'Lezárt'){
                $answerInterface = "A hiba megoldódott, további válasz küldési lehetőség nem lehetséges!";
            }
            else{
                $answerInterface = answerInteface($tid);
            }
            

            echo json_encode(['ticketData'=>$ticketData, 'answers'=>$answers, 'answerInterface'=>$answerInterface]);
        }
        else{
            $answers =  "Hiba történt, nincs nyilvántartva a hiba bejegyzés!";

            echo json_encode(['answers'=>$answers]);
        }
    }
    else{
        $query = $db->query("SELECT
                                p.EMAIL,
                                err.TYPENAME,
                                er.SUBJECT,
                                er.CREATIONTIME,
                                er.ERRORDESC,
                                er.IMAGE,
                                s.STATUS
                                FROM errortickets er INNER JOIN status s
                                ON er.SID = s.SID
                                INNER JOIN errortypes err
                                ON err.ERRID = er.ERRID
                                INNER JOIN PERSONS p
                                ON p.PID = er.PID
                                WHERE er.TID = '$tid'"
                            );
        if($query->num_rows == 1){
            $row = $query->fetch_array();

            
            $ticketData = 
            "<div id=\"ticketDataBlock\"><table>"
                ."<tr>"
                    ."<td>A bejelentő e-mail címe:</td><td>".$row['EMAIL']."</td>";

            if(!empty($row['IMAGE'])){
                $image = "<td rowspan =\"6\"><img src=\"/TicketControl/".$row['IMAGE']."\" alt=\"".$row['IMAGE']."\" height = \"200px\" width = \"320px\"></td>";
                $ticketData.=$image;
            }

            $ticketData.=   
                "</tr>"
                ."<tr>"
                    ."<td>Tárgy:</td><td>".$row['SUBJECT']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Beküldés ideje:</td><td>".$row['CREATIONTIME']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Hiba kategória:</td><td><select id=\"selectedError\">".getErrorTypeList($db, $row['TYPENAME'])."</select></td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Feldolgozás státusza:</td><td><select id=\"selectedStatus\">".getStatuses($db, $row['STATUS'])."</select></td>"
                ."</tr>"
                ."<tr>"
                    ."<td>Hiba leírása:</td><td>".$row['ERRORDESC']."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td colspan=\"2\"><button type=\"submit\" id=\"".$tid."\" class=\"save\"/>Mentés</td>"
                ."</tr>"
            ."</table></div>";

            $answers = getAnswers($tid, $db, $person);

            if($row['STATUS'] == 'Lezárt'){
                $answerInterface = "A hiba megoldódott, további válasz küldési lehetőség nem lehetséges!";
            }
            else{
                $answerInterface = answerInteface($tid);
            }

            echo json_encode(['ticketData'=>$ticketData, 'answers'=>$answers, 'answerInterface'=>$answerInterface]);
        }
        else{
            $answers =  "Hiba történt, nincs nyilvántartva a hiba bejegyzés!";

            echo json_encode(['answers'=>$answers]);
        }
    }

    $db->close();


    function getAnswers($tid, $db, $person){
        $answers = "<div id=\"answers\">"; 
        
        $query = $db->query("SELECT a.ADMIN, p.EMAIL, a.ANSWERTIME, a.ANSWER FROM ANSWERS a INNER JOIN ERRORTICKETS er ON a.TID = er.TID INNER JOIN PERSONS p ON p.PID = er.PID WHERE a.TID = '$tid'");

        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                if($row['ADMIN'] == 1){
                    $email = 'Admin';
                }
                else{
                    $email = $row['EMAIL'];
                }
                $answers.=
                "<table class = \"answers\">"
                    ."<tr>"
                        ."<td>".$email."</td><td>".$row['ANSWERTIME']."</td>"
                    ."</tr>"
                    ."<tr>"
                        ."<td colspan=\"2\">".$row['ANSWER']."</td>"
                    ."</tr>"
                ."</table>";
            }
            $answers.= "<a id=\"focused\"></a>";
        }
        else{
            $answers.= "Ehhez a bejelentéshez még nem érkeztek válaszok!";
        }
        $answers.="</div>";

        return $answers;
    }

    function answerInteface($tid){
        $interface = 
        "<div id=\"answerInterface\"><table>"
            ."<tr>"
                ."<td colspan = \"2\"><textarea id=\"answer\" rows=\"4\" cols=\"60\"></textarea></td>"
            ."</tr>"
            ."<tr>"
                ."<td><button type = \"submit\" class = \"answer\" id=\"".$tid."\">Válasz küldése!</button></td>"
        ."</tr>"
        ."</table></div>";
        
        return $interface;
    }

    function getErrorTypeList($db, $typename){
        $query = $db->query("SELECT TYPENAME FROM ERRORTYPES");

        $options = "";

        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                if($row['TYPENAME'] == $typename){
                    $options .= "<option selected>".$row['TYPENAME']."</option>";
                }
                else{
                    $options .= "<option>".$row['TYPENAME']."</option>";
                }
            }
        }

        return $options;
    }
    
    function getStatuses($db, $status){
        $query = $db->query("SELECT STATUS FROM STATUS");

        $options = "";

        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                if($row['STATUS'] == $status){
                    $options .= "<option selected>".$row['STATUS']."</option>";
                }
                else{
                    $options .= "<option>".$row['STATUS']."</option>";
                }
            }
        }
        return $options;
    }
?>