<?php
    session_start();

    include('bd.php');
    
    $user=$_SESSION['users'];
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];

    $result=mysqli_query($bd, "SELECT name_corpus FROM `corpus` WHERE corpus.name_uz='$UZ'");
    $corpus=[];
    $i=0;
    while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $corpus[$i]=$row['name_corpus'];
        $i++;
    }
?>