<?php
//создание учебной нагрузки
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
    if(isset($_POST['Moduls'])){
        $modul=$_POST['Moduls'];
        if($modul=="-- --"){
            unset($modul);
        }
    }
    if(isset($_POST['Groups'])){
        $group=$_POST['Groups'];
        if($group=="-- --"){
            unset($group);
        }
    }
    if(isset($_POST['Teachers'])){
        $teachers=$_POST['Teachers'];
        if($teachers=="-- --"){
            unset($teachers);
        }
    }
    if(isset($_POST['time_study_load'])){
        $time=$_POST['time_study_load'];
        if($time=="-- --"){
            unset($time);
        }
    }
    if(isset($_POST['url'])){
        $url=$_POST['url'];
        if($url==""){
            unset($url);
        }
    }
    if(isset($_POST['url_edit'])){
        $url_edit=$_POST['url_edit'];
        if($url_edit==""){
            unset($url_edit);
        }
    }
    if(isset($_POST['pincodes'])){
        $pin=$_POST['pincodes'];
        if($pin==""){
            unset($pin);
        }
    }
    if(empty($otdel)){
        $_SESSION['messageError']='Выберите отделение!';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($modul)){
        $_SESSION['messageError']='Выберите дисциплину!';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($teachers)){
        $_SESSION['messageError']='Выберите преподавателя который будет вести дисциплину';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($group)){
        $_SESSION['messageError']='Выберите группу в которой будет вести преподаватель';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($time)){
        $_SESSION['messageError']='Введите количество часов нагрузки по дисциплине';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($url)){
        $_SESSION['messageError']='Вставьте ссылку на гугл-таблицу';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($url_edit)){
        $_SESSION['messageError']='Вставьте ссылку на редактирование гугл-таблицы';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    if(empty($pin)){
        $_SESSION['messageError']='Введите pin-код администратора';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    //защита от sql-инъекций
    $pin=stripcslashes($pin);
    $pin=htmlspecialchars($pin);
    $pin=trim($pin);
    
    $time = preg_replace('/[^0-9]/', '', $time);

    $check=mysqli_query($bd, "SELECT id_SL FROM `study_load` WHERE name_dis='$modul' and name_teacher='$teachers'");
    $mycheck=mysqli_fetch_array($check, MYSQLI_ASSOC);
    if(!empty($mycheck['id_SL'])){
        $FIO=mysqli_query($bd, "SELECT  teachers_f, teachers_n, teachers_o FROM `users_teachers` WHERE teachers_log='$teachers'");
        $rowd=mysqli_fetch_array($FIO, MYSQLI_ASSOC);
        $_SESSION['messageError']="Преподаватель " . $rowd['teachers_f'] . ' ' . mb_substr($rowd['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($rowd['teachers_o'], 0,1, 'utf-8') . ". уже ведет дисциплину " . $modul . " в группе " . $group;
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
    //проверка пин-кода администратора
    $resultPin=mysqli_query($bd ,"SELECT admins_pin FROM users_admins WHERE admins_log='$user'");
    $rowPin=mysqli_fetch_array($resultPin, MYSQLI_ASSOC);
    
    if($rowPin['admins_pin']==$pin){
        
        $result2=mysqli_query($bd , "INSERT INTO `study_load` (name_dis, name_group, name_teacher, time_study_load, name_corpus, url_SL, url_edit) VALUES('$modul', '$group','$teachers', '$time','$otdel', '$url', '$url_edit')");
        if($result2==TRUE){
            $_SESSION['messageSuccess']='Нагрузка для преподавателя успешно добавлена!';
            header('Location: ../layout/admins/Study_load.php');
            exit();
        }
    }
    else{
        $_SESSION['messageError']='Pin код администратора введен не верно';
        header('Location: ../layout/admins/Study_load.php');
        exit();
    }
?>