$(document).ready(function(){
    $(document).on('click', "#addPersonBtn", function(e){
        e.preventDefault();

        var name = $('#name').val();
        var email = $('#email').val();
        var company = $('#company').val();
        var pw = $('#pw').val();
        var pw2 = $('#pw2').val();
        var btn = e.target.id;

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{btn:btn, name:name, email:email, company:company, pw:pw, pw2:pw2}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
        })
    });

    $(document).on('click', ".editPersonBtn", function(e){
        e.preventDefault();

        var btn = e.target.getAttribute('class');
        var pid = e.target.id;

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{pid:pid, btn:btn}
        })
        .done(function(data){
            document.getElementById('content').innerHTML = data.msg;
        })
    });

    $(document).on('click', ".editPersonBtn2", function(e){
        e.preventDefault();
        var name = $('#name').val();
        var email = $('#email').val();
        var company = $('#company').val();
        var btn = e.target.getAttribute('class');
        var pid = e.target.id;

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{pid:pid, btn:btn, name:name, email:email, company:company}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
        })
    });

    $(document).on('click', ".removePersonBtn2", function(e){
        e.preventDefault();

        var btn = e.target.getAttribute('class');
        var pid = e.target.id;

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{btn:btn, pid:pid}
        })
        .done(function(data){

            if(data.code == 201){
                document.getElementById('logout').click();
            }
            document.getElementById('msg').innerHTML = data.msg;
            document.getElementById('areYouSure').style.display = "none";
            getPersonList();
        })  
    });

    $(document).on('click', "#saveDataEditBtn", function(e){
        e.preventDefault();

        var btn = e.target.id;
        var name = $('#newName').val();
        var email = $('#newEmail').val();
        var company = $('#newCompany').val();
        var pw = $('#password').val();

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{btn:btn, name:name, email:email, company:company, pw:pw}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
            $('#password').val("");
        })
    });

    $(document).on('click', "#savePwEditBtn", function(e){
        e.preventDefault();

        var btn = e.target.id;
        var currentPW = $('#currentPassword').val();
        var newPW = $('#newPassword').val();
        var reNewPW = $('#reNewPassword').val();

        $.ajax({
            type:"POST",
            url:"PersonControl/controlPersonData.php",
            dataType:"json",
            data:{btn:btn, currentPW:currentPW, newPW:newPW, reNewPW:reNewPW}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
            if(data.code == 200){
                $('#currentPassword').val("");
                $('#newPassword').val("");
                $('#reNewPassword').val("");
            }
        })
    });

    $(document).on('change', '#personFilter', function(e){
        e.preventDefault();

        var email = $('#email').val();
        var company = $('#company').val();

        $.ajax({
            type:"GET",
            url:"PersonControl/getPersonList.php",
            dataType:"json",
            data:{email:email, company:company}
        })
        .done(function(data){
            document.getElementById('content').innerHTML = data.msg;
            if(typeof data.email != 'undefined'){
                document.getElementById('email').value = data.email;
            }
            if(typeof data.company != 'undefined'){
                document.getElementById('company').value = data.company;
            }
        })
    });
})