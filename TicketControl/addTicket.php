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

    $errMSG = "";

    $errorType = $_POST['errorType'];
    $subject = $_POST['subject'];
    $errorDesc = $_POST['errorDesc'];

    if(isset($_FILES['image'])){
        $imageName = basename($_FILES['image']['name']);

        $extension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowed = array('jpg','png');

        if(!in_array($extension, $allowed )){
            $errMSG.="Nem képet adtál meg, próbáld újra!<br/>";
        }
    }

    if(empty($errorType)){
        $errMSG.="A hiba típus megadása kötelező!<br/>";
    }

    if(empty($subject)){
        $errMSG.="A tárgy kitöltése kötelező!<br/>";
    }

    if(empty($errorDesc)){
        $errMSG.="A hiba leírása kötelező!<br/>";
    }


    if(empty($errMSG)){
        if(isset($imageName)){
            $target = "images/".rand().basename($_FILES['image']['name']);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target) !== TRUE){
                $errMSG.="A fájl feltöltése nem sikerült!<br/>";
            }
        }
        
        if(empty($errMSG)){
            $db = connectToDb();

            $query = $db->query("SELECT * FROM ERRORTYPES WHERE TYPENAME = '$errorType'");

            if($query->num_rows == 1){
                $row = $query->fetch_array();
                $errorType = $row['ERRID'];
            }
            else{
                $errMSG.="Ez a hibakategória nem létezik, adj meg másikat!<br/>";
            }

            if(empty($errMSG)){
                $subject = mysqli_real_escape_string($db, $subject);
                $errorDesc = mysqli_real_escape_string($db, $errorDesc);
                $pid = $person->getPid();

                if(isset($target)){
                    $target = mysqli_real_escape_string($db, $target);

                    if($db->query("INSERT INTO ERRORTICKETS (ERRID, PID, SID, SUBJECT, ERRORDESC, IMAGE) VALUES ('$errorType',
                        '$pid','1', '$subject', '$errorDesc', '$target')") !== TRUE){
                        $errMSG.="Nem sikerült a hibajegy hozzáadása!<br/>";
                    }
                }
                else{
                    if($db->query("INSERT INTO ERRORTICKETS (ERRID, PID, SID, SUBJECT, ERRORDESC) VALUES ('$errorType',
                        '$pid','1', '$subject', '$errorDesc')") !== TRUE){
                        $errMSG.="Nem sikerült a hibajegy hozzáadása!<br/>";
                    }
                }
            }
            $db->close();
        }
    }

    if(empty($errMSG)){
        echo "Sikeres hibajegy hozzáadás!";
    }
    else{
        echo $errMSG;
    }
    
    

    
?>