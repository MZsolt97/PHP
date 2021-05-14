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
    $answer = $_POST['answer'];

    if(get_class($person) == 'Admin'){
        $admin = 1;
    }
    else{
        $admin = 0;
    }

    $response = "";

    if(empty($answer)){
        $response = "A válasz mezőt kötelező kitölteni a válasz rögzítéséhez!";
        echo json_encode(['code'=>404, 'msg'=>$response]);
    }
    else{
        $db = connectToDb();

        $answer = mysqli_real_escape_string($db, $answer);

        if($db->query("INSERT INTO ANSWERS (TID, ANSWER, ADMIN) VALUES ('$tid', '$answer', $admin)") === TRUE){
            $time = date("Y-m-d H:i:s");
            $query = $db->query("SELECT p.EMAIL FROM ERRORTICKETS er INNER JOIN PERSONS p ON p.PID = er.PID WHERE er.TID = '$tid'");
            
            $row = $query->fetch_array();

            if($admin == 0){
                $email = $row['EMAIL'];
            }
            else{
                $email = "Admin";
            }
            $response =
            "<table class=\"answers\">"
                ."<tr>"
                    ."<td>".$email."</td><td>".$time."</td>"
                ."</tr>"
                ."<tr>"
                    ."<td colspan=\"2\">".$answer."</td>"
                ."</tr>"
            ."</table>";
        }

        $db->close();

        echo json_encode(['code'=>200, 'msg'=>$response]);
    }

?>