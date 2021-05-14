$(document).ready(function(){
    $(document).on('click', "#addErrorTypeBtn", function(e){
        e.preventDefault();

        var name = $('#name').val();
        var btn = e.target.id;

        $.ajax({
            type:"POST",
            url:"ErrorTypeControl/controlErrorType.php",
            dataType:"json",
            data:{btn:btn, name:name}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
        })
    });

    $(document).on('click', "#editErrorTypeBtn", function(e){
        e.preventDefault();

        var oldName = $('#oldName').val();
        var newName = $('#newName').val();
        var btn = e.target.id;

        $.ajax({
            type:"POST",
            url:"ErrorTypeControl/controlErrorType.php",
            dataType:"json",
            data:{btn:btn, oldName:oldName, newName:newName}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;
        })
    });

    $(document).on('click', "#removeErrorTypeBtn", function(e){
        e.preventDefault();

        var name = $('#name').val();
        var btn = e.target.id;

        $.ajax({
            type:"POST",
            url:"ErrorTypeControl/controlErrorType.php",
            dataType:"json",
            data:{btn:btn, name:name}
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data.msg;

            if(data.code == 200){
                document.getElementById('errorTypeList').innerHTML = "";
                getErrorTypeList2();
            }
        })
    });
})