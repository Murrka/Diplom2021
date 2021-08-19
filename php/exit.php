<?php
//выход из аккаунта
    session_start();
    unset($_SESSION['users']);
    
    header('Location: ../index.php');
?>