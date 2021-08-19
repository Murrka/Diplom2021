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
            header('Location: ../admins/homeAdmin.php');
            break;
        case 1:
            $result=mysqli_query($bd, "SELECT * FROM `users_teachers` WHERE teachers_log='$user'");
            $FIO=mysqli_fetch_array($result, MYSQLI_ASSOC);
            break;
        case 2:
            header('Location: ../students/home.php');
            break;
    }
    $group=$_GET['gr'];
    $dis=$_GET['dis'];
    $id_SL=$_GET['id_SL'];
    $id_task=$_GET['id_task'];
    $result=mysqli_query($bd, "SELECT * FROM `tasks` WHERE id_task='$id_task'");
    $taskResult=mysqli_fetch_array($result, MYSQLI_ASSOC);
    $result=mysqli_query($bd, "SELECT * FROM `users_students` WHERE students_group='$group'");
?>

<html lang="en">
    <head>
        <title>Дистанционное обучение</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="../../CSS/indexCSS.css" rel="stylesheet">
        
    </head>
    <body>
        <!--Голова сайта-->
        <section class="SectionNavigation">
            <div class="DivTitle">
                <img src="../../IMG/logo.png" alt="logo">
            </div>
            <div class="TitleText">
                <h1>Единый образовательный ресурс дистанционного обучения</h1>
            </div> 
            <div class="DivNav">
                <ul class="menu">
                    <li><a id="active" href="home.php">Главная</a></li>
                    <li><a href="https://collegetsaritsyno.mskobr.ru/raspisanie_zanyatij">Расписание</a></li>
                    <li><a href="#">Чат</a></li>
                    <li><a href="profile.php">Профиль</a></li>
                </ul>
            </div>
            <a href="../../php/exit.php"><div class="DivExit">
                <p>Выход </p>
                <img src="../../IMG/exits.png" alt="exit">
            </div></a>
        </section>

        <!--Тело сайта-->
        <section class="SectionBodyHome">
            <div class="LeftBar">
            <div class="DivPolInfo">
                    <h2><?php echo 'Преподаватель: ' . $FIO['teachers_f'] . ' ' . mb_substr($FIO['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($FIO['teachers_o'], 0,1, 'utf-8')?></h2>
                    <h2><?php echo 'Учебное учереждение:' . $FIO['teachers_uz'] ?></h2>
                </div>
                <div class="DivInfoDis">
                    <a href="Discipline.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Оценки</h3>
                    </div></a>
                    <a href="HardWork.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Практические занятия</h3>
                    </div></a>
                    <a href="TeorWork.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Теоритические занятия</h3>
                    </div></a>
                    <a href="MethodMat.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Методический материал</h3>
                    </div></a>
                    <a href="#"><div id="BoxDis">
                        <h3>Онлайн занятия</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                <h1><?=$_GET['gr'];?>><?=$_GET['dis'];?>>Практические занятия><?=$taskResult['name_tasks']?></h1>
                </div>
                <div class="info">
                    <h3>Описание задания</h3>
                    <h3><?=$taskResult['description_tasks']?></h3>
                </div>
                <div class="DivMBoxs_Mouth">
                <?php
                    while($studentsResult=mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        ?><div class="BoxDate">
                        <h3><?=$studentsResult['students_f'] . ' ' . mb_substr($studentsResult['students_n'], 0,1, 'utf-8') . '.' . mb_substr($studentsResult['students_o'], 0,1, 'utf-8') . '.'?></h3>
                        <?php 
                            $students_login=$studentsResult['students_log'];
                            $Score_student=mysqli_query($bd, "SELECT * FROM `hard_work_students` WHERE name_students='$students_login' and id_task='$id_task'");
                            $resultScore=mysqli_fetch_array($Score_student, MYSQLI_ASSOC);
                            if(!empty($resultScore['score_HW'])){
                                ?>
                                <form action="../../php/AddScore.php" method="POST" class="scoreStudentsForm">
                                <h3>Оценка: <?=$resultScore['score_HW']?></h3>
                            </form>
                        <?php }
                            else{
                        
                        ?>
                        <form action="../../php/AddScore.php" method="POST" class="scoreStudentsForm">
                            <h3>Оценка: </h3>
                            <select name="score_student" class="score_student">
                                <option value="Не зачет">Не зачет</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <input type="text" hidden name="login_student" value="<?=$studentsResult['students_log']?>">
                            <input type="text" hidden name="id_task" value="<?=$id_task?>">
                            <input type="submit" value="Поставить оценку">
                        </form>
                        <?php } ?>
                        <div></div>
                    </div>
                   <?php 
                        $login=$studentsResult['students_log'];
                        $resultTask=mysqli_query($bd, "SELECT * FROM `hard_work_students` WHERE name_students='$login' and id_task='$id_task'");
                        $taskrow=mysqli_fetch_array($resultTask, MYSQLI_ASSOC);
                        if(!empty($taskrow['id_HW'])){
                            $resultTask=mysqli_query($bd, "SELECT * FROM `hard_work_students` WHERE name_students='$login' and id_task='$id_task'");
                            while($taskrow=mysqli_fetch_array($resultTask, MYSQLI_ASSOC)){
                           
                             ?>
                                <div class="BoxFile"><a href="../../<?=$taskrow['path_HW']?>" download="">
                                <p><?=$taskrow['name_file']?></p>
                                <span><?php $date= new DateTime($taskrow['date_create_HW']); echo $date->format('d.m.Y'); ?></span>
                                </a></div>
                    <?php   }
                        }
                            else{ ?>
                                <div class="BoxFile"><a>
                                <p>Файлы отсутсвуют</p>
                                </a></div>
                    <?php   }
                        }
                    
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
