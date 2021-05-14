$(document).ready(function(){
    $(document).on('click', "#addNewTicketBtn", function(e){
        e.preventDefault();
        
        var errorType = $('#errorType').val();
        var subject = $('#subject').val();
        var errorDesc = $('#errorDesc').val();
        var image = $('#image').prop('files')[0];

        var formData = new FormData();

        formData.append('errorType', errorType);
        formData.append('subject', subject);
        formData.append('errorDesc', errorDesc);
        formData.append('image', image);

        $.ajax({
            type:"POST",
            url:"TicketControl/addTicket.php",
            dataType:"text",
            cache: false,
            contentType: false,
            processData: false,
            data:formData
        })
        .done(function(data){
            document.getElementById('msg').innerHTML = data;
        })
    });

    $(document).on('click', '.open', function(e){
        e.preventDefault();

        var tid = e.target.id;

        $.ajax({
            type:"POST",
            url:"TicketControl/ticket.php",
            dataType:"json",
            data: {tid:tid}
        })
        .done(function(data){
            $('#content').html(data.ticketData);
            $('#content').append(data.answers);
            $('#content').append(data.answerInterface);
            $('#answers').scrollTop($('#answers')[0].scrollHeight);
        })
    });

    $(document).on('click', '.answer', function(e){
        e.preventDefault();

        var tid = e.target.id;
        var answer = $('#answer').val();

        $.ajax({
            type:"POST",
            url:"TicketControl/answer.php",
            dataType:"json",
            data:{answer:answer, tid:tid}
        })
        .done(function(data){

            if(data.code == 200){
                $('#answers').append(data.msg);
                $('#answers').scrollTop($('#answers')[0].scrollHeight);
                $('#answer').val("");
            }
            else{
                $('#msg').html(data.msg);
            }
            
        })
    });

    $(document).on('click', '.save', function(e){
        e.preventDefault();

        var selectedError = $('#selectedError').val();
        var selectedStatus = $('#selectedStatus').val();
        var tid = e.target.id;

        $.ajax({
            type:"POST",
            url:"TicketControl/saveTicketEdit.php",
            dataType:"json",
            data:{tid:tid, selectedStatus:selectedStatus, selectedError:selectedError}
        })
        .done(function(data){
            if(typeof data.selectedError != 'undefined'){
                $('#selectedError').html(data.selectedError);
            }
            if(typeof data.selectedStatus != 'undefined'){
                $('#selectedStatus').html(data.selectedStatus);
            }
            $('#msg').html(data.msg);

            if($('#selectedStatus').val() != 'Lezárt'){

                document.getElementById('answerInterface').innerHTML = "";
                var table = document.createElement('table');
                table.id = "inteface";

                var trText = table.insertRow(0);

                trText.innerHTML = "<td colspan = \"2\"><textarea id=\"answer\" rows=\"4\" cols=\"60\"></textarea></td>";

                var trButton = table.insertRow(1);

                trButton.innerHTML = "<td><button type = \"submit\" class = \"answer\" id=\""+data.tid+"\">Válasz küldése!</button></td>";

                table.appendChild(trText);
                table.appendChild(trButton);

                document.getElementById('answerInterface').appendChild(table);
            }
            else{
                document.getElementById('answerInterface').innerHTML = "A hiba megoldódott, további válasz küldési lehetőség nem lehetséges!";
            }
        })
    });

    $(document).on('change', '#errorTicketListBlock', function(e){
        e.preventDefault();

        var email = $('#email').val();
        var selectedError = $('#selectedError').val();

        $.ajax({
            type:"GET",
            url:"TicketControl/errorTicketList.php",
            dataType:"json",
            data:{email:email, selectedError:selectedError}
        })
        .done(function(data){
            document.getElementById('content').innerHTML = data.msg;
            if(typeof data.email != 'undefined'){
                document.getElementById('email').value = data.email;
            }
            if(typeof data.selectedError != 'undefined'){
                document.getElementById('selectedError').value = data.selectedError;
            }
        })
    });

})
