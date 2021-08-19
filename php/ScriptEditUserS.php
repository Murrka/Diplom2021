<?php
//редактирование профиля students
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];
    $id=$_POST['id'];

    if(isset($_POST['fam'])){
        $fam=$_POST['fam'];
        if ($fam==""){
            unset($fam);
        }
    }
    if(isset($_POST['name'])){
        $name=$_POST['name'];
        if ($name==""){
            unset($name);
        }
    }
    if(isset($_POST['otch'])){
        $otch=$_POST['otch'];
    }
    if(isset($_POST['login'])){
        $login=$_POST['login'];
    }
    if(isset($_POST['password'])){
        $password=$_POST['password'];
        if($password==""){
            unset($password);
        }
    }
    if(isset($_POST['Groups'])){
        $group=$_POST['Groups'];
    }
    if(isset($_POST['pincodes'])){
        $pin=$_POST['pincodes'];
        if($pin==""){
            unset($pin);
        }
    }
    if(empty($fam) or empty($name)){
        $_SESSION['messageError']='Поле Фамилия и Имя не должны быть пустыми';
        header('Location: ../layout/admins/EditUserS.php?id='. $id);
        exit();
    }
    if(empty($pin)){
        $_SESSION['messageError']='Введите pin-код администратора';
        header('Location: ../layout/admins/EditUserS.php?id='. $id);
        exit();
    }
    
    //защита от sql-инъекций
    $login=stripcslashes($login);
    $login=htmlspecialchars($login);
    $password=stripcslashes($password);
    $password=htmlspecialchars($password);
    $fam=stripcslashes($fam);
    $fam=htmlspecialchars($fam);
    $name=stripcslashes($name);
    $name=htmlspecialchars($name);
    $otch=stripcslashes($otch);
    $otch=htmlspecialchars($otch);
    $pin=stripcslashes($pin);
    $pin=htmlspecialchars($pin);
    $login=trim($login);
    $password=trim($password);
    $fam=trim($fam);
    $name=trim($name);
    $otch=trim($otch);
    $pin=trim($pin);
    
    //подключение к бд
    
    //проверка пин-кода администратора
    $resultPin=mysqli_query($bd ,"SELECT admins_pin FROM users_admins WHERE admins_log='$user'");
    $rowPin=mysqli_fetch_array($resultPin, MYSQLI_ASSOC);
    
    if($rowPin['admins_pin']==$pin){
        //добавление нового пользователя если пройдены все проверки
        if(!empty($password)){
            $password=password_hash($password,PASSWORD_DEFAULT);
            $result2=mysqli_query($bd , "UPDATE `users` SET users_pass='$password' WHERE users_name='$login'");
        }
        $result2_1=mysqli_query($bd, "UPDATE `users_students` SET students_f='$fam', students_n='$name', students_o='$otch', students_group='$group' WHERE students_log='$login'");
        
        if($result2_1==TRUE){
            header('Location: ../layout/admins/EditUser_list.php');
            exit();
        }
    }
    else{
        $_SESSION['messageError']='Pin код администратора введен не верно';
        header('Location: ../layout/admins/EditUserS.php?id='. $id);
        exit();
    }
?>