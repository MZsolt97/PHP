<?php

    require_once('Methods.php');

    function registration(){
        $errorMSG = "";

        $name = $_POST['name'];
        $email = strtolower($_POST['email']);
        
        $company = $_POST['company'];


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

        if(empty($company)){
            $errorMSG.= "A cég megadása kötelező!<br/>";
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
            $db = connectToDb();

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
                $company = mysqli_real_escape_string($db, $company);

                if($db->query("INSERT INTO PERSONS (NAME, EMAIL, COMPANY, PW, ADMIN) VALUES ('$name', '$email', '$company', '$pw', 0)") != TRUE){
                    $errorMSG.="Az adatbázishoz való hozzáadás nem sikerült!<br/>";
                }
            }
            $db->close();
        }

        if(empty($errorMSG)){
            echo "Sikeres regisztráció!";
        }
        else{
            echo $errorMSG;
        }
    }
    
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Hiba kezelő</title>
        <link rel="stylesheet" href="Home.css">
    </head>
    <body>
    <div id = "mode"><h1>Regisztráció</h1></div>
        <div id="content">
            <form action = "<?php echo $_SERVER['PHP_SELF']?>" method = "POST">
                <table>
                    <tr>
                        <td>Név:</td><td><input type="text" name="name"/></td>
                    </tr>
                    <tr>
                        <td>E-mail cím:</td><td><input type="text" name="email"/></td>
                    </tr>
                    <tr>
                        <td>Cég:</td><td><input type="text" name="company"/></td>
                    </tr>
                    <tr>
                        <td>Jelszó:</td><td><input type="password" name="pw"/></td>
                    </tr>
                    <tr>
                        <td>Jelszó újra:</td><td><input type="password" name="pw2"/></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" name="registrationBtn" style = "float:right;" value = "Regisztráció"/></td>
                    </tr>
                </table>
            </form>
        <?php
            if(isset($_POST['registrationBtn'])){
                registration();
            }
        ?>
        <p><a href="Login.php">Vissza a bejelentkezéshez</a></p>
        </div>
    </body>
</html>