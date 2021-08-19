<?php 
    include('../../php/bd.php');
    $otdel=$_POST['nameOtdel'];
    $result=mysqli_query($bd, "SELECT DISTINCT name_dis FROM `discipline` WHERE name_corpus='$otdel';");
    $Dis=[];
    $s=0;
    while($rows=mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $Dis[$s]=$rows['name_dis'];
        $s++;
    }

    echo json_encode($Dis);
?>