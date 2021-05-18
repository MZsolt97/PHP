<?php
    
    require_once('PersonControl/personClasses.php');
    require_once('Methods.php');

    session_start();

    if(empty($_SESSION['person'])){
        header("location:Logout.php");
    }
    else{
        $person = $_SESSION['person'];
    }

    function mainControllers($person){
        if(get_class($person) === 'Admin'){
            echo
            "<ul onclick=changeAdminActive(event)>"
                ."<li class = \"active\" id=\"personControl\">Felhasználók kezelése</li>"
                ."<li class = \"nonactive\" id=\"errorTypeControl\">Hibakategóriák kezelése</li>"
                ."<li class = \"nonactive\" id=\"errorTicketControl\">Hibajegyek kezelése</li>"
                ."<li class = \"nonactive\" id=\"accountDataControl\">Fiók adatok kezelése</li>"
            ."</ul>";
        }
        else{
            echo
            "<ul onclick=changeUserActive(event)>"
                ."<li class = \"active\" id=\"newErrorTicket\">Hibajegy létrehozása</li>"
                ."<li class = \"nonactive\" id=\"oldErrorTickets\">Korábbi hibajegyeim</li>"
                ."<li class = \"nonactive\" id=\"accountDataControl\">Fiók adatok kezelése</li>"
            ."</ul>";
        }
    }

    function subControllers($person){
        if(get_class($person) === 'Admin'){
            echo
            "<ul onclick=changeAdminActive(event)>"
                ."<li class = \"active\" id=\"addPerson\">Felhasználó hozzáadása</li>"
                ."<li class = \"nonactive\" id=\"editPerson\">Felhasználók listázása és kezelése</li>"
            ."</ul>";
        }
    }

    function errorTypeOptions(){
        $db = connectToDb();

        $query = $db->query("SELECT TYPENAME FROM ERRORTYPES");

        $options = "";

        if($query->num_rows > 0){
            while($row = $query->fetch_array()){
                $options .= "<option>".$row['TYPENAME']."</option>";
            }
        }

        $db->close();

        return $options;

    }

    function defaultContent($person){
        if(get_class($person) === 'Admin'){
            echo
                "<div id=\"addPersonBlock\">"
                    ."<table>"
                        ."<tr>"
                            ."<td>A felvenni kívánt személy admin?</td><td><select onchange=setCompanyRow() id=\"admin\"><option>Igen</option><option selected>Nem</option></select></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>Név:</td><td><input type=\"text\" name=\"name\" id=\"name\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>E-mail cím:</td><td><input type=\"text\" name=\"email\" id=\"email\"/></td>"
                        ."</tr>"
                        ."<tr id=\"companyRow\">"
                            ."<td>Cég:</td><td><input type=\"text\" name=\"company\" id=\"company\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>Jelszó:</td><td><input type=\"password\" name=\"pw\" id=\"pw\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>Jelszó újra:</td><td><input type=\"password\" name=\"pw2\" id=\"pw2\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td colspan=\"2\"><button type=\"submit\" id=\"addPersonBtn\">Hozzáadás</button></td>"
                        ."</tr>"
                    ."</table>"
                ."</div>";
        }
        else{
            echo
                "<div id=\"addErrorTicketBlock\">"
                    ."<table>"
                        ."<tr>"
                            ."<td>Hiba kategória:</td><td><select id=\"errorType\">".errorTypeOptions()."</select></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>Hiba tárgya:</td><td><input type=\"text\" id=\"subject\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td colspan=\"2\">Hiba leírás:</td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td colspan=\"2\"><textarea id=\"errorDesc\" rows=\"4\" cols=\"50\"></textarea></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td>Kép kiválasztása:</td><td><input type=\"file\" id=\"image\" name=\"image\"/></td>"
                        ."</tr>"
                        ."<tr>"
                            ."<td colspan=\"2\"><button type=\"submit\" id=\"addNewTicketBtn\">Beküldés</button></td>"
                        ."</tr>"
                    ."</table>"
                ."</div>";
        }
    }

    function mode($person){
        if(get_class($person) === "Admin"){
            echo "Admin felület";
        }
        else{
            echo "User felület";
        }
    }

?>

<!DOCTYPE html>
<html lang="hu-HU">
    <header>
        <title>Hiba kezelő</title>
        <link rel="stylesheet" href="Home.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="Home.js"></script>
        <script src="PersonControl/ajaxPerson.js"></script>
        <script src="ErrorTypeControl/ajaxErrorType.js"></script>
        <script src="TicketControl/ajaxTicket.js"></script>
        <meta charset = "UTF-8">
        <meta name = "author" content = "Murszelovics Zsolt Gábor - J8IKYP">
    </header>
    <body>
        <div id="mode"><h1><?php echo mode($person);?></h1></div>

        <div id="mainControllers">
            <?php mainControllers($person); ?>
        </div>
        <div id="subControllers">
            <?php subControllers($person); ?>
        </div>
        <div id="areYouSure">
                <p>Biztosan törölni szeretnéd a fiókot?</p>
                <button type="submit" id="declineBtn" onclick="hideBox()">Nem</button>
                <button type="submit" class="removePersonBtn2" id="0">Igen</button>
            </div>
        <div id="content">
            <?php defaultContent($person); ?>
        </div>
        <div id="msg"></div>
        <a id="logout" href="/Logout.php">Kijelentkezés</a>
    </body>
</html>