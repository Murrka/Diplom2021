<?php
//авторизация
    session_start();

    if(isset($_POST['Login'])){
        $login=$_POST['Login'];
        if ($login==""){
            unset($login);
        }
    }
    if(isset($_POST['Password'])){
        $password=$_POST['Password'];
        if ($password==""){
            unset($password);
        }
    }
    if(empty($login) or empty($password)){
        $_SESSION['messageError']='Логин или пароль введены неверно';
        header('Location: ../index.php');
        exit();
    }

    $login=stripcslashes($login);
    $login=htmlspecialchars($login);
    $password=stripcslashes($password);
    $password=htmlspecialchars($password);

    include('bd.php');

    $result=mysqli_query($bd, "SELECT * FROM `users` WHERE users_name='$login'");
    $row=mysqli_fetch_array($result, MYSQLI_BOTH);

    if(!empty($row['users_id'])){
        if($row['users_pass']==$password){ //password_verify($row['users_pass'], $password)
            switch($row['users_access']){
                case 0:
                    $_SESSION['users']=$login;
                    header('Location: ../layout/admins/homeAdmin.php');
                    break;
                case 1:
                    $_SESSION['users']=$login;
                    header('Location: ../layout/teachers/home.php');
                    break;
                case 2:
                    $_SESSION['users']=$login;
                    header('Location: ../layout/students/home.php');
                    break;
            }
        }
        else{
            $_SESSION['messageError']='Логин или пароль введены неверно';
            header('Location: ../index.php');
            exit();
        }
    }
?>