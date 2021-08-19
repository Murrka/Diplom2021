<?php
    include('../../php/bd.php');
    $otdel=$_POST['nameOtdel'];

    $result=mysqli_query($bd, "SELECT teachers_log, teachers_f, teachers_n, teachers_o FROM `users_teachers` WHERE teachers_otdel='$otdel'");
    $UserFIO=[];
    $s=0;
    while($rowd=mysqli_fetch_assoc($result)){
        $UserFIO[]=[
            'login'=>$rowd['teachers_log'],
            'FIO'=>$rowd['teachers_f'] . ' ' . mb_substr($rowd['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($rowd['teachers_o'], 0,1, 'utf-8')
        ];
        $s++;
    }
    
    echo json_encode($UserFIO);
?>