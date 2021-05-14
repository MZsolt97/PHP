<?php

    require_once('../Methods.php');
    session_start();
    
    if(empty($_SESSION['person'])){
        header("location:../Logout.php");
    }

    $errorMSG = "";
    $db = connectToDb();

    //////////      Az admin hiba kategóriát ad hozzá az adatbázishoz        //////////
    if($_POST['btn'] == "addErrorTypeBtn"){

        $errName = strtolower($_POST['name']);

        if(empty($errName)){
            $errorMSG.="A hibakategória nevét kötelező kitölteni!<br/>";
        }
        else{

            $query = $db->query("SELECT * FROM ERRORTYPES");

            $isCorrect = TRUE;

            if($query->num_rows > 0){
                while($row = $query->fetch_array()){
                    if($row['TYPENAME'] == $errName){
                        $isCorrect = FALSE;
                        break;
                    }
                }
            }

            if($isCorrect){
                $errName = mysqli_real_escape_string($db, $errName);
                
                if($db->query("INSERT INTO ERRORTYPES (TYPENAME) VALUES ('$errName')") != TRUE){
                    $errorMSG.="Az adatbázishoz való hozzáadás nem sikerült!<br/>";
                }
            }
            else{
                $errorMSG.="Ezzel a névvel már szerepel hibakategória az adatbázisban!<br/>";
            }
        }

        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres hozzáadás!<br/>"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }

    //////////      Az admin módosítja a megadott hiba kategória nevét        //////////
    if($_POST['btn'] == "editErrorTypeBtn"){
        $errOldName = strtolower($_POST['oldName']);
        $errNewName = strtolower($_POST['newName']);
        $errorMSG = "";
    
        if(empty($errOldName)){
            $errorMSG.="A módosításhoz add meg a módosítandó kategória nevét!<br/>";
        }
        if(empty($errNewName)){
            $errorMSG.="A módosításhoz add meg a módosítandó kategória új nevét!<br/>";
        }
        if($errOldName == $errNewName){
            $errorMSG.="A megadott nevek megegyeznek, nem módosíthatóak , kérlek adj meg új nevet!<br/>";
        }
    
        if(empty($errorMSG)){
    
            $oldNameIsCorrect = FALSE;
            $newNameIsCorrect = TRUE;
    
            $query = $db->query("SELECT * FROM ERRORTYPES");
    
            if($query->num_rows > 0){
                while($row = $query->fetch_array()){
                    if($row['TYPENAME'] == $errOldName){
                        $oldNameIsCorrect = TRUE;
                    }
                    if($row['TYPENAME'] == $errNewName){
                        $newNameIsCorrect = FALSE;
                    }
                }
            }
    
            if(!$oldNameIsCorrect){
                $errorMSG.="Ezzel a névvel: <span style=\"font-weight: bold;\">".$errOldName."</span> nem szerepel hibakategória az adatbázisban!<br/>";
            }
            if(!$newNameIsCorrect){
                $errorMSG.="Ezzel a névvel: <span style=\"font-weight: bold;\">".$errNewName."</span> már szerepel hibakategória az adatbázisban!<br/>";
            }
    
            if($oldNameIsCorrect && $newNameIsCorrect){
                $errNewName = mysqli_real_escape_string($db, $errNewName);
                if($db->query("UPDATE ERRORTYPES SET TYPENAME = '$errNewName' WHERE TYPENAME = '$errOldName'") !== TRUE){
                    $errorMSG.="A módosítás nem sikerült!<br/>";
                }
            }
        }
    
        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres módosítás!<br/>"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }

    //////////      Az admin eltávolítja a megadott hiba kategóriát        //////////
    if($_POST['btn'] == "removeErrorTypeBtn"){
        $name = strtolower($_POST['name']);

        if(empty($name)){
            $errorMSG.="A törléshez add meg a törölni kívánt kategória nevét!<br/>";
        }
        else{
            $query = $db->query("SELECT * FROM ERRORTYPES");

            $isCorrect = FALSE;

            if($query->num_rows > 0){
                while($row = $query->fetch_array()){
                    if($row['TYPENAME'] == $name){
                        $isCorrect = TRUE;
                        break;
                    }
                }
            }

            if($isCorrect){
                if($db->query("DELETE FROM ERRORTYPES WHERE TYPENAME='$name'")!=TRUE){
                    $errorMSG.="A törlés nem sikerült!<br/>";
                }
            }
            else{
                $errorMSG.="A megadott hiba kategória: <span style=\"font-weight:bold;\">".$name."</span> nem szerepel az adatbázisban!<br/>";
            }
        }
        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres törlés!<br/>"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }

    $db->close();
?>