<?php
//создание группы студентов в бд
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];

    if(isset($_POST['group'])){
        $group=$_POST['group'];
        if ($group==""){
            unset($group);
        }
    }
    if(isset($_POST['Otdel'])){
        $otdel=$_POST['Otdel'];
        if($otdel=="-- --"){
            unset($otdel);
        }
    }
    if(isset($_POST['pincodes'])){
        $pin=$_POST['pincodes'];
        if($pin==""){
            unset($pin);
        }
    }
    if(empty($group)){
        $_SESSION['messageError']='Введите название группы';
        header('Location: ../layout/admins/AddGroup.php');
        exit();
    }
    if(empty($otdel)){
        $_SESSION['messageError']='Выберите отделение';
        header('Location: ../layout/admins/AddGroup.php');
        exit();
    }
    if(empty($pin)){
        $_SESSION['messageError']='Введите pin-код администратора';
        header('Location: ../layout/admins/AddGroup.php');
        exit();
    }
    //защита от sql-инъекций
    $group=stripcslashes($group);
    $group=htmlspecialchars($group);
    $pin=stripcslashes($pin);
    $pin=htmlspecialchars($pin);
    $group=trim($group);
    $pin=trim($pin);
    
    //проверка группы на существование в бд
    $result=mysqli_query($bd , "SELECT id_groups FROM `groups` WHERE name_group='$group'" );
    $myrow=mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if(!empty($myrow['id_groups'])){
        $_SESSION['messageError']="Группа уже существует";
        header('Location: ../layout/admins/AddGroup.php');
        exit();
    }
    //проверка пин-кода администратора
    $resultPin=mysqli_query($bd ,"SELECT admins_pin FROM users_admins WHERE admins_log='$user'");
    $rowPin=mysqli_fetch_array($resultPin, MYSQLI_ASSOC);
    
    if($rowPin['admins_pin']==$pin){
        //добавление нового пользователя если пройдены все проверки
        $result2=mysqli_query($bd , "INSERT INTO `groups` (name_group, name_corpus) VALUES('$group', '$otdel')");
        if($result2==TRUE){
            $_SESSION['messageSuccess']='Группа ' . $group . ' успешно добавлена!';
            header('Location: ../layout/admins/AddGroup.php');
            exit();
        }
    }
    else{
        $_SESSION['messageError']='Pin код администратора введен не верно';
        header('Location: ../layout/admins/AddGroup.php');
        exit();
    }
?>