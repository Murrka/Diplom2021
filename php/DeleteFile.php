<?php
//удаление загруженных файлов пользователями из папки и из бд
    session_start();

    $user=$_SESSION['users'];

    if($user==""){
        header('Location: ../index.php');
        exit();
    }
    include('bd.php');
    $check=mysqli_query($bd, "SELECT users_access FROM `users` WHERE users_name='$user'");
    $row=mysqli_fetch_array($check, MYSQLI_ASSOC);
    switch($row['users_access']){
        case 0:
            header('Location: ../admins/homeAdmin.php');
            break;
        case 1:
            $id=$_GET['id_DT'];
            $id_SL=$_GET['id_SL'];
            $group=$_GET['gr'];
            $dis=$_GET['dis'];
            $result=mysqli_query($bd, "SELECT * FROM `downloads_task` WHERE id_DT='$id'");
            $DelInfoResult=mysqli_fetch_array($result, MYSQLI_ASSOC);
            unlink($DelInfoResult['path_DT']);
            $del=mysqli_query($bd,"DELETE FROM `downloads_task` WHERE id_DT='$id'");
            header('Location: ../layout/teachers/MethodMat.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' .$id_SL);
            break;
        case 2:
            $id=$_GET['id'];
            $id_SL=$_GET['id_SL'];
            $id_task=$_GET['id_task'];
            $result=mysqli_query($bd, "SELECT * FROM `hard_work_students` WHERE id_HW='$id'");
            $DelInfoResult=mysqli_fetch_array($result, MYSQLI_ASSOC);
            unlink($DelInfoResult['path_HW']);
            $del=mysqli_query($bd,"DELETE FROM `hard_work_students` WHERE id_HW='$id'");
            header('Location: ../layout/students/Tasks.php?id_SL=' .$id_SL .'&id_task=' .$id_task);
            break;
    }
    
    
?>