<?php
//создание дисциплины в бд
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];


    if(isset($_POST['Otdel'])){
        $otdel=$_POST['Otdel'];
        if ($otdel=="-- --"){
            unset($otdel);
        }
    }
    if(isset($_POST['module'])){
        $modul=$_POST['module'];
        if($modul==""){
            unset($modul);
        }
    }
    if(isset($_POST['Moduls'])and $_POST['module']==""){
        $modul=$_POST['Moduls'];
        if($modul=="-- --"){
            unset($modul);
        }
    }
    if(isset($_POST['pincodes'])){
        $pin=$_POST['pincodes'];
        if($pin==""){
            unset($pin);
        }
    }
    if(isset($_POST['discip'])){
        $DIS=$_POST['discip'];
        if($DIS==""){
            unset($DIS);
        }
    }
    if(empty($otdel)){
        $_SESSION['messageError']='Выберите отделение';
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
    if(empty($modul)){
        $_SESSION['messageError']='Введите название модуля или выберите из существующих.';
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
    if(empty($DIS)){
        $_SESSION['messageError']='Введите название дисциплины.';
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
    if(empty($pin)){
        $_SESSION['messageError']='Введите pin-код администратора';
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
    //защита от sql-инъекций
    $modul=stripcslashes($modul);
    $modul=htmlspecialchars($modul);
    $DIS=stripcslashes($DIS);
    $DIS=htmlspecialchars($DIS);
    $pin=stripcslashes($pin);
    $pin=htmlspecialchars($pin);
    $modul=trim($modul);
    $DIS=trim($DIS);
    $pin=trim($pin);
    

    //проверка дисциплины на существование в бд
    $result=mysqli_query($bd , "SELECT id_dis FROM `discipline` WHERE name_dis='$DIS'" );
    $myrow=mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if(!empty($myrow['id_dis'])){
        $_SESSION['messageError']="Такая дисциплина уже существует";
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
    //проверка пин-кода администратора
    $resultPin=mysqli_query($bd ,"SELECT admins_pin FROM users_admins WHERE admins_log='$user'");
    $rowPin=mysqli_fetch_array($resultPin, MYSQLI_ASSOC);
    
    if($rowPin['admins_pin']==$pin){
        //добавление нового пользователя если пройдены все проверки
        $result2=mysqli_query($bd , "INSERT INTO `discipline` (name_modul, name_dis, name_corpus) VALUES('$modul', '$DIS','$otdel')");
        if($result2==TRUE){
            $_SESSION['messageSuccess']='Дисциплина ' . $DIS . ' успешно добавлена!';
            header('Location: ../layout/admins/AddDiscip.php');
            exit();
        }
    }
    else{
        $_SESSION['messageError']='Pin код администратора введен не верно';
        header('Location: ../layout/admins/AddDiscip.php');
        exit();
    }
?>