<?php
    session_start();

    $user=$_SESSION['users'];

    if($user==""){
        header('Location: ../../index.php');
        exit();
    }
    include('../../php/bd.php');
    $check=mysqli_query($bd, "SELECT users_access FROM `users` WHERE users_name='$user'");
    $row=mysqli_fetch_array($check, MYSQLI_ASSOC);
    switch($row['users_access']){
        case 0:
            $result=mysqli_query($bd, "SELECT * FROM `users_admins` WHERE admins_log='$user'");
            $FIO=mysqli_fetch_array($result, MYSQLI_ASSOC);
            break;
        case 1:
            header('Location: ../teachers/home.php');
            break;
        case 2:
            header('Location: ../students/home.php');
            break;
    }
    $uz=$FIO['admins_uz'];
    $result1=mysqli_query($bd, "SELECT name_corpus FROM `corpus` WHERE name_uz='$uz'");
    
?>


<html lang="en">
    <head>
        <title>Дистанционное обучение</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="../../CSS/indexCSS.css" rel="stylesheet">
        
    </head>
    <script>
        function Close(){
            document.getElementById('messageError').style.display='none';
        }
    </script>

    <body>
        <!--Голова сайта-->
        <section class="SectionNavigation">
            <div class="DivTitle">
                <img src="../../IMG/logo.png" alt="logo">
            </div>
            <div class="TitleText">
                <h1>Единый образовательный ресурс дистанционного обучения</h1>
            </div> 
            <a href="homeAdmin.php"><div class="DivPanel">
                <h1>Панель администратора</h1>
            </div></a>
            <a href="../../php/exit.php"><div class="DivExit">
                <p>Выход </p>
                <img src="../../IMG/exits.png" alt="exit">
            </div></a>
        </section>

        <!--Тело сайта-->
        <section class="SectionBodyHome">
            <div class="LeftBar">
                <div class="DivAdminInfo">
                    <h2><?php echo 'Администратор: ' . $FIO['admins_f'] . ' ' . mb_substr($FIO['admins_n'], 0,1, 'utf-8') . '.' . mb_substr($FIO['admins_o'], 0,1, 'utf-8')?></h2>
                    <h2><?php echo 'Учебное учреждение:' . $FIO['admins_uz'] ?></h2>
                </div>
                <div class="DivAdminBar">
                    <a href="AddUser1.php"><div id="BoxAdmin">
                        <h3>Добавить студента</h3>
                    </div></a>
                    <a href="AddUser2.php"><div id="BoxAdmin">
                        <h3>Добавить преподавателя</h3>
                    </div></a>
                    <a href="AddGroup.php"><div id="BoxAdmin">
                        <h3>Добавить группу</h3>
                    </div></a>
                    <a href="AddDiscip.php"><div id="BoxAdmin">
                        <h3>Добавить дисциплину</h3>
                    </div></a>
                    <a href="Study_load.php"><div id="BoxAdmin">
                        <h3>Создать учебную нагрузку</h3>
                    </div></a>
                    <a href="Edit_Study_load.php"><div id="BoxAdmin" class="ActiveBox6">
                        <h3>Список учебной нагрузки</h3>
                    </div></a>
                    <a href="EditUser_list.php"><div id="BoxAdmin">
                        <h3>Список пользователей</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Список учебной нагрузки</h1>
                </div>

                <div class="Edit">
                    <div class="row">
                        <div class="cell"><h3>№</h3></div>
                        <div class="cell"><h3>Наименование дисциплины</h3></div>
                        <div class="cell"><h3>Преподаватель</h3></div>
                        <div class="cell"><h3>Группа</h3></div>
                        <div class="cell"><h3>Удалить нагрузку</h3></div>
                    </div>
                    <?php while($ResRow=mysqli_fetch_array($result1, MYSQLI_ASSOC)){
                            ?><h3 class="h3"><?= $ResRow['name_corpus'];?></h3>
                            <?php $corpus=$ResRow['name_corpus'];
                            $study_load=mysqli_query($bd, "SELECT * FROM `study_load` WHERE name_corpus='$corpus'");
                            $data_study_load1=mysqli_fetch_array($study_load, MYSQLI_ASSOC);
                            $i=0;
                            if(!empty($data_study_load1['id_SL'])){
                                $study_load=mysqli_query($bd, "SELECT * FROM `study_load` WHERE name_corpus='$corpus'");
                                while($data_study_load=mysqli_fetch_array($study_load, MYSQLI_ASSOC)){
                                    ?><div class="row row2">
                                        <div class="cell cell2"><h3><?=$i+1;?></h3></div>
                                        <div class="cell cell2"><h3><?=$data_study_load['name_dis'];?></h3></div>
                                        <?php $log=$data_study_load['name_teacher'];
                                            $result2=mysqli_query($bd, "SELECT teachers_f, teachers_n, teachers_o FROM `users_teachers` WHERE teachers_log='$log'");
                                            $row2=mysqli_fetch_array($result2, MYSQLI_ASSOC);
                                            $FIOTeachers=$row2['teachers_f'] . ' ' . mb_substr($row2['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($row2['teachers_o'], 0,1, 'utf-8');?>
                                        <div class="cell cell2"><h3><?=$FIOTeachers;?></h3></div>
                                        <div class="cell cell2"><h3><?=$data_study_load['name_group'];?></h3></div>
                                        <div class="cell cell2"><a href="../../php/Delete_Study_load.php/?id=<?=$data_study_load['id_SL']?>"><h3>Удалить нагрузку</h3></a></div>
                                        </div>
                            <?php 
                                $i++;
                                }
                            }
                            else { ?>
                            <div class="row row2">
                                <div class="cell cell3"><h3>Нагрузка отсутсвует.</h3></div>
                            </div>  
                        <?php } }
                    ?>
                    
                </div>
            </div>
            <div class="Clear"></div>
        </section>

        <!--Подвал сайта-->
        <section class="SectionFooter">
            <div class="SectionDiv">
                <a href="#"><div class="SectionFooterNav">
                   Помощь и поддержка
                </div></a>
                <a href="#"><div class="SectionFooterNav">
                    Условия использования
                </div></a>
                <a href="#"><div class="SectionFooterNav">
                    Задать вопрос?
                </div></a>
                <div class="SectionLanguageDiv">
                    <select>
                        <option>Русский</option>
                        <option disabled>English</option>
                    </select>
                </div>
                <div id="perem"></div>
                <div id="copy">
                    &copy; 2021 Корнев "Murrka" Алексей
                </div>
            </div>
            </div>
        </section>
    </body>
</html>

