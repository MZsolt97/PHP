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

    $response  = "";

    $query = $db->query("SELECT a.ADMIN,
                            p.EMAIL,
                            a.ANSWERTIME,
                            a.ANSWER
                            FROM ANSWERS a INNER JOIN ERRORTICKETS er
                            ON a.TID = er.TID
                            INNER JOIN PERSONS p
                            ON p.PID = er.PID
                            WHERE a.TID = '$tid'
                        ");

        if($query->num_rows > 0){
            $response = "<table class=\"answers\">";
            while($row = $query->fetch_array()){
                if($row['ADMIN'] == 1){
                    $email = 'Admin';
                }
                else{
                    $email = $row['EMAIL'];
                }
                $response.= 
                    "<tr>"
                        ."<td>".$email."</td><td>".$row['ANSWERTIME']."</td>"
                    ."</tr>"
                    ."<tr>"
                        ."<td colspan=\"2\">".$row['ANSWER']."</td>"
                    ."</tr>";
            }
            $response.="</table>";
        }
        else{
            $response = "<tr><td>Ehhez a bejelentéshez még nem érkeztek válaszok!</td></tr>";
        }

    $db->close();

    echo $response;
?>