<?php
    session_start();
    if(!empty($_SESSION['person'])){
        session_unset();
    }
    
    session_destroy();
    header("location:Login.php");
?>

<script>
    window.location.href('localhost/Login.php');
</script>

