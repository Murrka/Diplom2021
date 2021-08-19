<?php
    session_start();

    include('bd.php');
    
    $user=$_SESSION['users'];
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];

    $result=mysqli_query($bd, "SELECT DISTINCT name_modul FROM `corpus` INNER JOIN `discipline` on corpus.name_corpus = discipline.name_corpus WHERE corpus.name_uz='$UZ'");
    $modul=[];
    $s=0;
    while($rows=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $modul[$s]=$rows['name_modul'];
        $s++;
    }
?>