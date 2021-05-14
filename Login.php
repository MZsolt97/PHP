<?php
    
    require_once('PersonControl/personClasses.php');
    require_once('Methods.php');
    
    session_start();

    if(!empty($_SESSION['person'])){
        header('location: /Home.php');
    }

    function Login(){
        $errorMSG = "";

        $email = strtolower($_POST['email']);
        $pw = $_POST['pw'];

        $_SESSION['email'] = $email;
        $_SESSION['pw'] = $pw;

        if(empty($email)){
            $errorMSG .= "Az e-mail cím megadása kötelező!<br/>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errorMSG .= "A megadott e-mail címnek nem megfelelő a formátuma!<br/>";
        }
            
        if(empty($pw)){
            $errorMSG .= "Az jelszó megadása kötelező!";
        }
        else if(strlen($pw) < 5 || strlen($pw) > 16){
            $errorMSG .= "Az jelszó legalább 5, legfeljebb 16 karakter hosszú!<br/>";
        }

        if(empty($errors)){
            $db = connectToDb();

            $email = mysqli_real_escape_string($db, $email);

            $pw = mysqli_real_escape_string($db, md5($pw));
            
            $query = $db->query("SELECT * FROM PERSONS WHERE EMAIL='$email' AND PW='$pw'");

            if($query->num_rows == 1){
                $row = $query->fetch_array();
                if($row['ADMIN']){
                    $person = new Admin($row['PID'], $row['NAME'], $row['EMAIL'], $row['ADMIN']);
                    $_SESSION['person'] = $person;
                }
                else{
                    $person = new User($row['PID'], $row['NAME'], $row['EMAIL'], $row['COMPANY']);
                    $_SESSION['person'] = $person;
                }
                header("location:/Home.php");
            }
            else{
                $errorMSG .= "Hibás e-mail cím vagy jelszó!<br/>";
            }
            $db->close();
        }

        if(!empty($errorMSG)){
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
        <div id="mode"><h1>Bejelentkezés</h1></div>
        <div id="content">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <table>
                    <tr>
                        <td><label for="email">E-mail cím:</label></td>
                        <td>
                            <input
                                type="text"
                                name="email"
                                title="Pl.: valaki@valami.hu"
                                placeholder="valaki@valami.hu"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="pw">Jelszó:</label></td>
                        <td>
                            <input
                                type="password"
                                name="pw"
                                title="8-16 karakter"
                                placeholder="********"
                            />
                        </td>
                    </tr>
                </table>
                <input type="submit" name="login" value="Bejelentkezés"/>
            </form>
            <?php if(isset($_POST['login'])){ Login(); }?>
            <p>Még nincs fiókod? <a href="/Registration.php">Regisztrálj most!</a></p>
        </div>
    </body>
</html>