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

    $errorMSG = "";


    $db = connectToDb();

    //////////      Az admin fiókot ad az adatbázishoz        //////////
    if($_POST['btn'] == "addPersonBtn"){
        $name = $_POST['name'];
        $email = strtolower($_POST['email']);
        
        if(isset($_POST['company'])){
            $company = $_POST['company'];
        }

        $pw = $_POST['pw'];
        $pw2 = $_POST['pw2'];
        
        if(empty($name)){
            $errorMSG.= "A név megadása kötelező!<br/>";
        }
        else if(preg_match("/[^a-zA-Z'\-áÁéÉíÍóÓöÖőŐúÚüÜűŰ]/", $name)){
            $errorMSG.= "A név nem tartalmazhat speciális karaktereket!<br/>";
        }
        if(empty($email)){
            $errorMSG.= "Az e-mail cím megadása kötelező!<br/>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorMSG.="Az e-mail cím formátuma nem megfelelő!<br/>";
        }

        if(isset($company)){
            if(empty($company)){
                $errorMSG.= "A cég megadása kötelező!<br/>";
            }
        }
        if(empty($pw)){
            $errorMSG.= "A jelszó megadása kötelező!<br/>";
        }
        else if(strlen($pw) < 5 || strlen($pw) > 16){
            $errorMSG.="A jelszó hosszának legalább 5, maximum 16 karakter hosszúnak kell lennie!<br/>";
        }
        if(empty($pw2)){
            $errorMSG.= "A jelszó újra megadása kötelező!<br/>";
        }

        if($pw!=$pw2){
            $errorMSG.="A megadott jelszavak nem egyeznek!<br/>";
        }

        if(empty($errorMSG)){

            $query = $db->query("SELECT * FROM PERSONS");

            if($query->num_rows > 0){
                while($row = $query->fetch_array()){
                    if($row['EMAIL'] == $email){
                        $errorMSG.="A megadott e-mail címmel már van fiók regisztrálva!";
                        break;
                    }
                }
            }

            if(empty($errorMSG)){

                $name = mysqli_real_escape_string($db, $name);
                $email = mysqli_real_escape_string($db, $email);
                $pw = md5($pw);

                if(isset($company)){
                    $company = mysqli_real_escape_string($db, $company);
                    if($db->query("INSERT INTO PERSONS (NAME, EMAIL, COMPANY, PW, ADMIN) VALUES ('$name', '$email', '$company', '$pw', 0)") != TRUE){
                        $errorMSG.="Az adatbázishoz való hozzáadás nem sikerült!<br/>";
                    }
                }
                else{
                    if($db->query("INSERT INTO PERSONS (NAME, EMAIL, PW, ADMIN) VALUES ('$name', '$email', '$pw', 1)") != TRUE){
                        $errorMSG.="Az adatbázishoz való hozzáadás nem sikerült!<br/>";
                    }
                }
            }
        }

        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres hozzáaádás!"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }
    
    //////////      Az admin megkeresi a módosítandó fiókot és visszaadja az adatait        //////////
    if($_POST['btn'] == "editPersonBtn"){
        
        $pid = $_POST['pid'];

        $response = "";

        $query = $db->query("SELECT * FROM PERSONS");
        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                if($row['PID'] == $pid){
                    $response =
                    "<table>"
                        ."<tr>"
                            ."<td>Név:</td><td><input type=\"text\" id=\"name\" value=\"".$row['NAME']."\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>E-mail cím:</td><td><input type=\"text\" id=\"email\" value=\"".$row['EMAIL']."\"/></td>"
                        ."</tr>";
                    if($row['ADMIN'] == 0){
                        $response.= 
                        "<tr>"
                            ."<td>Cég:</td><td><input type=\"text\" id=\"company\" value=\"".$row['COMPANY']."\"/></td>"
                        ."</tr>";
                    }
                    $response.=
                        "<tr>"
                            ."<td><button type=\"submit\" class=\"editPersonBtn2\", id=\"".$pid."\">Módosítás</button></td>"
                        ."</tr>"
                    ."</table>";

                    break;
                }
            }
        }

        if(empty($response)){
            echo json_encode(['msg'=>'Nem található a kiválasztott fiók!']);
        }
        else{
            echo json_encode(['msg'=>$response]);
        }
    }

    //////////      Az admin módosítja egy fiók adatait        //////////
    if($_POST['btn'] == "editPersonBtn2"){
        $name = $_POST['name'];
        $email = strtolower($_POST['email']);
        $pid = $_POST['pid'];
        
        if(isset($_POST['company'])){
            $company = $_POST['company'];
        }

        if(empty($name)){
            $errorMSG.= "A név megadása kötelező!<br/>";
        }
        else if(!preg_match("/[^a-zA-Z'\-áÁéÉíÍóÓöÖőŐúÚüÜűŰ]/", $name)){
            $errorMSG.= "A név nem tartalmazhat speciális karaktereket!<br/>";
        }
        if(empty($email)){
            $errorMSG.= "Az e-mail cím megadása kötelező!<br/>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorMSG.="Az e-mail cím formátuma nem megfelelő!<br/>";
        }
        
        if(isset($company)){
            if(empty($company)){
                $errorMSG.= "A cég megadása kötelező!<br/>";
            }
        }

        if(empty($errorMSG)){

            $query = $db->query("SELECT * FROM PERSONS");

            if($query->num_rows >= 1){
                while($row = $query->fetch_array()){
                    if($row['EMAIL'] == $email && $row['PID'] != $pid){
                        $errorMSG.="A megadott e-mail címmel már van fiók regisztrálva!";
                        break;
                    }
                }
            }

            if(empty($errorMSG)){

                $name = mysqli_real_escape_string($db, $name);
                $email = mysqli_real_escape_string($db, $email);

                if(isset($company)){
                    $company = mysqli_real_escape_string($db, $company);
                    if($db->query("UPDATE PERSONS SET NAME = '$name', EMAIL='$email', COMPANY='$company' WHERE PID = $pid") != TRUE){
                        $errorMSG.="Az adatok módosítása nem sikerült!<br/>";
                    }
                }
                else{
                    if($db->query("UPDATE PERSONS SET NAME = '$name', EMAIL='$email' WHERE PID = $pid") != TRUE){
                        $errorMSG.="Az adatok módosítása nem sikerült!<br/>";
                    }
                }
            }
        }

        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres módosítás!"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }

    //////////      A felhasználó/admin módosítja a saját fiókjához tartozó adatokat       //////////
    if($_POST['btn'] == "saveDataEditBtn"){

        $name = $_POST['name'];
        $email = strtolower($_POST['email']);
        $pw = $_POST['pw'];

        if(isset($_POST['company'])){
            $company = $_POST['company'];
        }
        
        if(empty($name)){
            $errorMSG.= "A név megadása kötelező!<br/>";
        }
        else if(preg_match("/[^a-zA-Z'\-áÁéÉíÍóÓöÖőŐúÚüÜűŰ]/", $name)){
            $errorMSG.= "A név nem tartalmazhat speciális karaktereket!<br/>";
        }
        if(empty($email)){
            $errorMSG.= "Az e-mail cím megadása kötelező!<br/>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorMSG.="Az e-mail cím formátuma nem megfelelő!<br/>";
        }
        else{
            $query = $db->query("SELECT EMAIL FROM PERSONS WHERE EMAIL = '$email'");

            if($query->num_rows == 1){
                $row = $query->fetch_array();

                if($row['EMAIL'] != $person->getEmail()){
                    $errorMSG.="Ezzel az e-mail címmel már létezik regisztrált fiók!<br/>";
                }
            }
            else{
                $errorMSG.="Hiba történt az adatbázisban!<br/>";
            }
        }
        if(isset($company)){
            if(empty($company)){
                $errorMSG.= "A cég megadása kötelező!<br/>";
            }
        }
        if(empty($pw)){
            $errorMSG.= "A jelszó megadása kötelező!<br/>";
        }
        else{
            $pw = md5($pw);
            
            $pid = $person->getPid();
            $query = $db->query("SELECT * FROM PERSONS WHERE PID = $pid");

            if($query->num_rows == 1){
                $row = $query->fetch_array();
                if($pw != $row['PW']){
                    $errorMSG.= "Helytelen jelszó!<br/>";
                }
            }
            else{
                $errorMSG.="Hiba történt az adatbázisban!<br/>";
            }
        }

        if(empty($errorMSG)){

            $name = mysqli_real_escape_string($db, $name);
            $email = mysqli_real_escape_string($db, $email);
            
            if(isset($company)){
                $company = mysqli_real_escape_string($db, $company);
                if($db->query("UPDATE PERSONS SET NAME = '$name', EMAIL = '$email', COMPANY = '$company' WHERE PID = $pid") !== TRUE){
                    $errorMSG.= $db->error;
                }
            }
            else{
                if($db->query("UPDATE PERSONS SET NAME = '$name', EMAIL = '$email' WHERE PID = $pid") !== TRUE){
                    $errorMSG.= $db->error;
                }
            }
        }

        if(empty($errorMSG)){
            echo json_encode(['msg'=>"Sikeres adatmódosítás!"]);
            $person->setName($name);
            $person->setEmail($email);
            if(isset($company)){
                $person->setCompany($company);
            }
        }
        else{
            echo json_encode(['msg'=>$errorMSG]);
        }
 
    }

    //////////      A felhasználó/admin módosítja a saját fiókjához tartozó jelszót       //////////
    if($_POST['btn'] == "savePwEditBtn"){
        
        $currentPW = $_POST['currentPW'];
        $newPW = $_POST['newPW'];
        $reNewPW = $_POST['reNewPW'];

        if(empty($currentPW)){
            $errorMSG.="A jelenlegi jelszó megadása kötelező!<br/>";
        }
        if(empty($newPW)){
            $errorMSG.="Az új jelszó megadása kötelező!<br/>";
        }
        else if(strlen($newPW) < 5 || strlen($newPW) > 16){
            $errorMSG.="A jelszó hosszának legalább 5, maximum 16 karakter hosszúnak kell lennie!<br/>";
        }
        if(empty($reNewPW)){
            $errorMSG.="Az új jelszó megerősítése kötelező!<br/>";
        }
        if($newPW != $reNewPW){
            $errorMSG.="Az új jelszó és a megerősítés nem egyezik meg!<br/>";
        }

        if(empty($errorMSG)){
            $pid = $person->getPid();
            $query = $db->query("SELECT * FROM PERSONS WHERE PID = $pid");

            if($query->num_rows == 1){
                $row = $query->fetch_array();

                $currentPW = md5($currentPW);
                $reNewPW = md5($reNewPW);

                if($row['PW'] != $currentPW){
                    $errorMSG.= "Helytelen jelszó!<br/>";
                }
                else if($db->query("UPDATE PERSONS SET PW = '$reNewPW' WHERE PID = $pid") !== TRUE){
                    $errorMSG.= $db->error;
                }
            }
            else{
                $errorMSG.="Hiba történt az adatbázisban!<br/>";
            }
        }

        if(empty($errorMSG)){
            echo json_encode(['code'=>200, 'msg'=>"Sikeres jelszó módosítás!"]);
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }

    //////////      Az admin töröl egy fiókot az adatbázisból       //////////
    if($_POST['btn'] == "removePersonBtn2"){
        
        $pid = $_POST['pid'];

        if($db->query("DELETE FROM PERSONS WHERE PID = $pid") !== TRUE){
            $errorMSG.=$db->error;
        }

        if(empty($errorMSG)){
            if($pid == $person->getPid()){
                echo json_encode(['code'=>201]);
            }
            else{
                echo json_encode(['code'=>200, 'msg'=>'Sikeres törlés!']);
            }
            
        }
        else{
            echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
        }
    }
    $db->close();

?>