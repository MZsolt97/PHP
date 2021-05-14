function changeUserActive(event){
    var parent = event.target.parentNode;
    var active = parent.getElementsByClassName("active");

    document.getElementById('msg').innerHTML = "";

    if(event.target.className == "nonactive"){
        active[0].className = "nonactive";
        event.target.className = "active";

        var controller = parent.parentNode.id;
        if(controller == "mainControllers"){
            var content = document.getElementById("content");
            content.innerHTML = "";

            if(event.target.innerHTML == "Hibajegy létrehozása"){

                var div = document.createElement('div');

                div.id = "addErrorTicketBlock";

                var table = document.createElement('table');

                document.getElementById('areYouSure').style.display = "none";
                
                var trSelectError = table.insertRow(0);

                trSelectError.innerHTML = "<td>Hiba kategória:</td><td><select id=\"errorType\"></select></td>";

                trSelectError.onload = getErrorTypeList();

                var trErrorSubject = table.insertRow(1);

                trErrorSubject.innerHTML = "<td>Hiba tárgya:</td><td><input type=\"text\" id=\"subject\"/></td>";

                var trErrorDesc = table.insertRow(2);

                trErrorDesc.innerHTML = "<td colspan=\"2\">Hiba leírás:</td>";

                var trErrorDescContent = table.insertRow(3);

                trErrorDescContent.innerHTML = "<td colspan = \"2\"><textarea id=\"errorDesc\" rows=\"4\" cols=\"50\"></textarea></td>";

                var trImage = table.insertRow(4);

                trImage.innerHTML = "<td>Kép kiválasztása:</td><td><input type=\"file\" id=\"image\" name=\"image\"/></td>";
                
                var trButton = table.insertRow(5);

                trButton.innerHTML = "<td colspan=\"2\"><button type=\"submit\" id=\"addNewTicketBtn\">Beküldés</button></td>";

                var trArray = [trSelectError, trErrorSubject, trErrorDesc, trErrorDescContent, trImage, trButton];

                for(var i = 0; i<trArray.length; i++){
                    table.appendChild(trArray[i]);
                }

                div.appendChild(table);

                content.appendChild(div);
                
            }
            else if(event.target.innerHTML == "Korábbi hibajegyeim"){

                document.getElementById('areYouSure').style.display = "none";

                var content = document.getElementById('content');

                content.innerHTML = "";

                content.onload = getErrorTicketList();
            }
            else if(event.target.innerHTML == "Fiók adatok kezelése"){

                document.getElementById('areYouSure').style.display = "none";
                var content = document.getElementById('content');

                content.innerHTML = "";

                content.onload = getPersonData();
            }
        }
    }

}

function getErrorTypeList(){
    var request = new XMLHttpRequest();
    request.open("GET", "errorTypeList.php", true);
    request.onload = function(){
        document.getElementById("errorType").innerHTML = this.responseText;
    }
    request.send();
}

function getErrorTicketList(){
    var request = new XMLHttpRequest();
    request.open("GET", "TicketControl/errorTicketList.php", true);
    request.onload = function(){
        document.getElementById("content").innerHTML = this.responseText;
    }
    request.send();
}

function getPersonList(){
    var request = new XMLHttpRequest();
    request.open("GET", "PersonControl/getPersonList.php", true);
    request.onload = function(){
        document.getElementById("content").innerHTML = this.responseText;
    }
    request.send();
}

function getPersonData(){
    var request = new XMLHttpRequest();
    request.open("GET", "PersonControl/getPersonData.php", true);
    request.onload = function(){
        document.getElementById("content").innerHTML = this.responseText;
    }
    request.send();
}

function getErrorTypeList2(){
    var request = new XMLHttpRequest();
    request.open("GET", "ErrorTypeControl/getErrorTypeList.php", true);
    request.onload = function(){
        document.getElementById('content').innerHTML += this.responseText;
    }
    request.send();
}

function changeAdminActive(event){
    
    var parent = event.target.parentNode;
    var active = parent.getElementsByClassName("active");

    document.getElementById('msg').innerHTML = "";

    if(event.target.className == "nonactive"){
        active[0].className = "nonactive";
        event.target.className = "active";

        var controller = parent.parentNode.id;
        if(controller == "mainControllers"){
            var subControllers = document.getElementById("subControllers");
            subControllers.innerHTML = "";

            var ul = document.createElement('ul');
            ul.onclick=changeAdminActive;

            if(event.target.innerHTML == "Felhasználók kezelése"){
                document.getElementById('areYouSure').style.display = "none";
            
                var addPerson = document.createElement('li');
                addPerson.id = "addPerson";
                addPerson.className = "active";
                addPerson.innerHTML = "Felhasználó hozzáadása";

                var editPerson = document.createElement('li');
                editPerson.id = "editPerson";
                editPerson.className = "nonactive";
                editPerson.innerHTML = "Felhasználók listázása és kezelése";

                var ulElements = [addPerson, editPerson];
                
            }
            else if(event.target.innerHTML == "Hibakategóriák kezelése"){
                document.getElementById('areYouSure').style.display = "none";
                
                var addErrorType = document.createElement('li');
                addErrorType.id = "addErrorType";
                addErrorType.className = "active";
                addErrorType.innerHTML = "Hibakategória hozzáadása";

                var editErrorType = document.createElement('li');
                editErrorType.id = "editErrorType";
                editErrorType.className = "nonactive";
                editErrorType.innerHTML = "Hibakategória módosítása";

                var removeErrorType = document.createElement('li');
                removeErrorType.id = "errorTicketControl";
                removeErrorType.className = "nonactive";
                removeErrorType.innerHTML = "Hibakategória törlése";

                var ulElements = [addErrorType, editErrorType, removeErrorType];
                
            }
            else if(event.target.innerHTML == "Hibajegyek kezelése"){
                document.getElementById('areYouSure').style.display = "none";

                var content = document.getElementById('content');

                content.innerHTML = "";

                content.onload = getErrorTicketList();
            }
            else if(event.target.innerHTML == "Fiók adatok kezelése"){
                document.getElementById('areYouSure').style.display = "none";
                var content = document.getElementById('content');

                content.innerHTML = "";

                content.onload = getPersonData();
            }
            if(typeof ulElements != 'undefined'){
                for(var i=0; i<ulElements.length;i++){
                    ul.appendChild(ulElements[i]);
                }
            }
            subControllers.appendChild(ul);
        }
        changeContent();
    }
}

function areYouSure(event){
    document.getElementById('areYouSure').style.display = "block";
    document.getElementsByClassName('removePersonBtn2')[0].id = event.target.id;
}

function hideBox(){
    document.getElementById('areYouSure').style.display = "none";
}

function setCompanyRow(){

    if(document.getElementById('admin').value == "Igen"){
        document.getElementById('companyRow').innerHTML = "";
    }
    else{
        document.getElementById('companyRow').innerHTML = "<td>Cég:</td><td><input type=\"text\" id=\"company\"/></td>";
    }
}

function changeContent(){
    var actives = document.getElementsByClassName("active");

    var content = document.getElementById("content");
    content.innerHTML = "";
         
    var table = document.createElement('table');

    if(actives[0].innerHTML == "Felhasználók kezelése"){

        if(actives[1].innerHTML == "Felhasználó hozzáadása"){

            var div = document.createElement('div');

            div.id= "addPersonBlock";

            var trAdmin = table.insertRow(0);

            trAdmin.innerHTML = "<td>A felvenni kívánt személy admin?</td><td><select id=\"admin\" onchange=setCompanyRow()><option>Igen</option><option selected>Nem</option></select></td>";

            var trName = table.insertRow(1);

            trName.innerHTML = "<td>Név:</td><td><input type=\"text\" id=\"name\"/></td>";

            var trEmail = table.insertRow(2);

            trEmail.innerHTML = "<td>E-mail cím:</td><td><input type=\"text\" id=\"email\"/></td>";

            var trCompany = table.insertRow(3);

            trCompany.innerHTML = "<td>Cég:</td><td><input type=\"text\" id=\"company\"/></td>";
            trCompany.id = "companyRow";

            var trPW = table.insertRow(4);

            trPW.innerHTML = "<td>Jelszó:</td><td><input type=\"password\" id=\"pw\"/></td>";

            var trPW2 = table.insertRow(5);

            trPW2.innerHTML = "<td>Jelszó újra:</td><td><input type=\"password\" id=\"pw2\"/></td>";

            var trButton = table.insertRow(6);

            trButton.innerHTML = "<td colspan=\"2\"><button type=\"submit\" id=\"addPersonBtn\">Hozzáadás</button></td>"

            var trArray = [trAdmin, trName, trEmail, trCompany, trPW, trPW2, trButton];

            div.appendChild(table);

            content.appendChild(div);

            for(var i = 0; i<trArray.length; i++){
                table.appendChild(trArray[i]);
            }
       
        }
        else if(actives[1].innerHTML == "Felhasználók listázása és kezelése"){

            var content = document.getElementById('content');

            content.innerHTML = "";

            content.onload = getPersonList();
        }
    }
    else if(actives[0].innerHTML == "Hibakategóriák kezelése"){
        if(actives[1].innerHTML == "Hibakategória hozzáadása"){

            var div = document.createElement('div');

            div.id = "addErrorTypeBlock";
            
            var trName = table.insertRow(0);
            trName.innerHTML = "<td>Hibakategória neve</td><td><input type=\"text\" id=\"name\"/></td><td colspan=\"2\"><button type=\"submit\" id=\"addErrorTypeBtn\">Hozzáadás</button></td>";

            table.appendChild(trName);

            div.appendChild(table);
            content.appendChild(div);
        }
        else if(actives[1].innerHTML == "Hibakategória módosítása"){

            var div = document.createElement('div');

            div.id = "editErrorTypeBlock";

            var trOldName = table.insertRow(0);
            trOldName.innerHTML = "<td>Hibakategória jelenlegi neve</td><td><input type=\"text\" id=\"oldName\"/></td>";

            var trNewName = table.insertRow(1);
            trNewName.innerHTML = "<td>Hibakategória új neve</td><td><input type=\"text\" id=\"newName\"/></td>";

            var trButton = table.insertRow(2);
            trButton.innerHTML = "<td colspan=\"2\"><button type=\"submit\" id=\"editErrorTypeBtn\">Módosítás</button></td>";

            var trArray = [trOldName, trNewName, trButton];

            for(var i = 0; i<trArray.length; i++){
                table.appendChild(trArray[i]);
            }

            div.appendChild(table);
            content.appendChild(div);

            getErrorTypeList2();
            
        }
        else if(actives[1].innerHTML == "Hibakategória törlése"){

            var div = document.createElement('div');

            div.id = "removeErrorTypeBlock";

            var trName = table.insertRow(0);
            trName.innerHTML = "<td>Hibakategória neve</td><td><input type=\"text\" id=\"name\"/></td>";

            var trButton = table.insertRow(1);
            trButton.innerHTML = "<td colspan=\"2\"><button type=\"submit\" id=\"removeErrorTypeBtn\">Törlés</button></td>";

            var trArray = [trName, trButton];

            for(var i = 0; i<trArray.length; i++){
                table.appendChild(trArray[i]);
            }

            div.appendChild(table);
            content.appendChild(div);

            getErrorTypeList2();
        }
    }
    /*if(typeof trArray != 'undefined'){
        for(var i = 0; i<trArray.length; i++){
            table.appendChild(trArray[i]);
        }
    }
    content.appendChild(table);*/
}



