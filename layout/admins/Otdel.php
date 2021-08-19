<?php 
    include('../../php/bd.php');
    $otdel=$_POST['nameOtdel'];
    $result=mysqli_query($bd, "SELECT DISTINCT name_modul FROM `discipline` WHERE name_corpus='$otdel';");
    $Moduls=[];
    $s=0;
    while($rows=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $Moduls[$s]=$rows['name_modul'];
        $s++;
    }
    echo json_encode($Moduls);
?>