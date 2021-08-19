<?php
//удаление аккаунта пользователя
    session_start();

    $user=$_SESSION['users'];

    if($user==""){
        header('Location: ../../index.php');
        exit();
    }
    include('bd.php');
    $check=mysqli_query($bd, "SELECT users_access FROM `users` WHERE users_name='$user'");
    $row=mysqli_fetch_array($check, MYSQLI_ASSOC);
    switch($row['users_access']){
        case 0:
            $id=$_GET['id'];
            $delete=mysqli_query($bd, "DELETE FROM `users` WHERE users_id='$id'");
            header("Location: ../../layout/admins/EditUser_list.php");
            break;
        case 1:
            header('Location: ../teachers/home.php');
            break;
        case 2:
            header('Location: ../students/home.php');
            break;
    }

?>