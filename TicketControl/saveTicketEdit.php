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
    $selectedError = $_POST['selectedError'];
    $selectedStatus = $_POST['selectedStatus'];
    
    $errorMSG = "";

    if(empty($tid)){
        $errorMSG.="A változtatáshoz szükséges a hibajegy!<br/>";
    }
    if(empty($selectedError)){
        $errorMSG.="A hiba kategória kiválasztása kötelező!<br/>";
    }
    if(empty($selectedStatus)){
        $errorMSG.="A státusz megadása kötelező!<br/>";
    }

    
    if(empty($errorMSG)){
        $db = connectToDb();
        
        $query = $db->query("SELECT * FROM STATUS WHERE STATUS = '$selectedStatus'");
        $row = $query->fetch_array();
        $selectedStatusID = $row['SID'];

        $query = $db->query("SELECT * FROM ERRORTYPES WHERE TYPENAME = '$selectedError'");
        $row = $query->fetch_array();
        $selectedErrorID = $row['ERRID'];
        
        if($db->query("UPDATE ERRORTICKETS SET ERRID = '$selectedErrorID', SID = '$selectedStatusID' WHERE TID = '$tid'") === TRUE){
            echo json_encode(['msg'=>'Sikeres módosítás', 'tid'=>$tid]);
        }
        else{
            echo json_encode(['selectedError'=>'ERROR', 'selectedStatus'=>'ERROR', 'msg'=>$db->error]);
        }

        $db->close();
    }
    else{
        echo json_encode(['selectedError'=>'ERROR', 'selectedStatus'=>'ERROR', 'msg'=>$errorMSG]);
    }

?>