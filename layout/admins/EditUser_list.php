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
                    <a href="Edit_Study_load.php"><div id="BoxAdmin">
                        <h3>Список учебной нагрузки</h3>
                    </div></a>
                    <a href="EditUser_list.php"><div id="BoxAdmin" class="ActiveBox7">
                        <h3>Список пользователей</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Список пользователей</h1>
                </div>
                <!--Список преподавателей -->
                <div class="Edit">
                    <h3 class="h2">Преподаватели</h3>
                    <div class="row rowTeachers">
                        <div class="cell"><h3>№</h3></div>
                        <div class="cell"><h3>ФИО преподавателя</h3></div>
                        <div class="cell"><h3>Логин</h3></div>
                        <div class="cell"><h3>Дата регистрации</h3></div>
                        <div class="cell"><h3>Действие</h3></div>
                    </div>
                    <?php while($ResRow=mysqli_fetch_array($result1, MYSQLI_ASSOC)){
                            ?><h3 class="h3"><?= $ResRow['name_corpus'];?></h3>
                            <?php $corpus=$ResRow['name_corpus'];
                            $teachers=mysqli_query($bd, "SELECT * FROM `users_teachers` WHERE teachers_otdel='$corpus'");
                            $data_study_load1=mysqli_fetch_array($teachers, MYSQLI_ASSOC);
                            $i=0;
                            if(!empty($data_study_load1['teachers_id'])){
                                $teachers=mysqli_query($bd, "SELECT * FROM `users_teachers` WHERE teachers_otdel='$corpus'");
                                while($data_study_load=mysqli_fetch_array($teachers, MYSQLI_ASSOC)){
                                    ?><div class="row row2 rowTeachers">
                                        <div class="cell cell2"><h3><?=$i+1;?></h3></div>
                                        <div class="cell cell2"><h3><?php echo $data_study_load['teachers_f'] . ' ' . mb_substr($data_study_load['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($data_study_load['teachers_o'], 0,1, 'utf-8');?></h3></div>
                                        <div class="cell cell2"><h3><?=$data_study_load['teachers_log'];?></h3></div>
                                        <?php 
                                            $log=$data_study_load['teachers_log'];
                                            $result2=mysqli_query($bd, "SELECT * FROM `users` WHERE users_name='$log'");
                                            $data=mysqli_fetch_array($result2, MYSQLI_ASSOC);
                                            $date=new DateTime($data['users_date']);
                                        ?>
                                        <div class="cell cell2"><h3><?=$date->format('d.m.Y');?></h3></div>
                                        <div class="cell cell2">
                                        <a href="EditUserT.php?id=<?=$data['users_id']?>" class="green"><h3>Изменить пользователя</h3></a>
                                        <div class="line"></div>
                                        <a href="../../php/Delete_users.php?id=<?=$data['users_id']?>"><h3>Удалить пользователя</h3></a>
                                        </div>
                                        </div>
                            <?php 
                                $i++;
                                }
                            }
                            else { ?>
                            <div class="row row2">
                                <div class="cell cell3"><h3>Преподаватели отсутсвуют в базе.</h3></div>
                            </div>  
                        <?php } }
                    ?>
                    
                </div>
                <!--Список студентов -->
                <div class="Edit">
                    <h3 class="h2">Студенты</h3>
                    <div class="row rowStudents">
                        <div class="cell"><h3>№</h3></div>
                        <div class="cell"><h3>ФИО студента</h3></div>
                        <div class="cell"><h3>Логин</h3></div>
                        <div class="cell"><h3>Группа</h3></div>
                        <div class="cell"><h3>Дата регистрации</h3></div>
                        <div class="cell"><h3>Действие</h3></div>
                    </div>
                    <?php   $student=mysqli_query($bd, "SELECT * FROM `users_students` WHERE students_uz='$uz' ORDER BY students_group");
                            
                            $RowStudent=mysqli_fetch_array($student, MYSQLI_ASSOC);
                            if(!empty($RowStudent['students_id'])){
                                $student=mysqli_query($bd, "SELECT * FROM `users_students` WHERE students_uz='$uz' ORDER BY students_group");
                                $i=0;
                                while($RowStudent=mysqli_fetch_array($student, MYSQLI_ASSOC)){
                                    ?><div class="row rowStudents">
                                        <div class="cell cell2"><h3><?=$i+1;?></h3></div>
                                        <div class="cell cell2"><h3><?php echo $RowStudent['students_f'] . ' ' . mb_substr($RowStudent['students_n'], 0,1, 'utf-8') . '.' . mb_substr($RowStudent['students_o'], 0,1, 'utf-8');?></h3></div>
                                        <div class="cell cell2"><h3><?=$RowStudent['students_log'];?></h3></div>
                                        <?php 
                                            $log=$RowStudent['students_log'];
                                            $result2=mysqli_query($bd, "SELECT * FROM `users` WHERE users_name='$log'");
                                            $data=mysqli_fetch_array($result2, MYSQLI_ASSOC);
                                            $date=new DateTime($data['users_date']);
                                        ?>
                                        <div class="cell cell2"><h3><?=$RowStudent['students_group'];?></h3></div>
                                        <div class="cell cell2"><h3><?=$date->format('d.m.Y');?></h3></div>
                                        <div class="cell cell2">
                                        <a href="EditUserS.php?id=<?=$data['users_id']?>" class="green"><h3>Изменить пользователя</h3></a>
                                        <div class="line"></div>
                                        <a href="../../php/Delete_users.php?id=<?=$data['users_id']?>"><h3>Удалить пользователя</h3></a>
                                        </div>
                                    </div>
                            <?php 
                                $i++;
                                }
                            }
                            else { ?>
                            <div class="row row2">
                                <div class="cell cell3"><h3>Студенты отсутсвуют в базе.</h3></div>
                            </div>  
                        <?php } 
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

