<?php
//добавить оценку за задание
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');

    $group=$_GET['gr'];
    $dis=$_GET['dis'];
    $id_SL=$_GET['id_SL'];
    $url=$_SERVER['HTTP_REFERER'];


    if(isset($_POST['score_student'])){
        $score_student=$_POST['score_student'];
    }
    if(isset($_POST['login_student'])){
        $login_student=$_POST['login_student'];
    }
    if(isset($_POST['id_task'])){
        $id_task=$_POST['id_task'];
    }

    $query=mysqli_query($bd, "UPDATE `hard_work_students` SET score_HW='$score_student' WHERE name_students='$login_student' and id_task='$id_task'");
    if($query==TRUE){
        header('Location: ' . $url);
        exit();
    }

    
?>