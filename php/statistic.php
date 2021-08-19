<?php
//вывод статистики определенного юзера-преподавателя из бд за определенный месяц
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');

    $url=$_SERVER['HTTP_REFERER'];
    if(isset($_POST['month'])){
        $month=$_POST['month'];
    }
    if(isset($_POST['dis'])){
        $dis=$_POST['dis'];
    }
    if(isset($_POST['group'])){
        $group=$_POST['group'];
    }
    $result=mysqli_query($bd,"SELECT id_SL FROM `study_load` WHERE name_dis='$dis' and name_group='$group'");
    $id=mysqli_fetch_assoc($result);
    $id_SL=$id['id_SL'];
    
    $_SESSION['date']=$month;
    $_SESSION['id_SL']=$id_SL;
    $month=new DateTime($month);
    $month=$month->format('m');
    
    switch($month){
        case '01':
            $_SESSION['month']='Январь';
            break;
        case '02':
            $_SESSION['month']='Февраль';
            break;
        case '03':
            $_SESSION['month']='Март';
            break;
        case '04':
            $_SESSION['month']='Апрель';
            break;
        case '05':
            $_SESSION['month']='Май';
            break;
        case '06':
            $_SESSION['month']='Июнь';
            break;
        case '07':
            $_SESSION['month']='Июль';
            break;
        case '08':
            $_SESSION['month']='Август';
            break;
        case '09':
            $_SESSION['month']='Сентябрь';
            break;
        case '10':
            $_SESSION['month']='Октябрь';
            break;
        case '11':
            $_SESSION['month']='Ноябрь';
            break;
        case '12':
            $_SESSION['month']='Декабрь';
            break;
    }
    header("Location: " . $url);

?>