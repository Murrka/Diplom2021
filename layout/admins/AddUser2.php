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

    if(!isset($_SESSION['messageError'])){
        unset($_SESSION['messageError']);
    }
    if(!isset($_SESSION['messageSuccess'])){
        unset($_SESSION['messageSuccess']);
    }
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
                    <a href="AddUser1.php"><div id="BoxAdmin" >
                        <h3>Добавить студента</h3>
                    </div></a>
                    <a href="AddUser2.php"><div id="BoxAdmin" class="ActiveBox2">
                        <h3>Добавить преподавателя</h3>
                    </div></a>
                    <a href="AddGroup.php"><div id="BoxAdmin" >
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
                    <a href="EditUser_list.php"><div id="BoxAdmin">
                        <h3>Список пользователей</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Добавить пользователя</h1>
                </div>
                
                <?php if(isset($_SESSION['messageError'])){ //вывод сообщения об ошибке при регистрации?>
                <div class="Error" id="messageError">
                    <h3><?php echo $_SESSION['messageError'];?></h3>
                    <button type="button" class="closeError" tabindex="0" onclick="Close()">X</button>
                </div>
                <?php unset($_SESSION['messageError']); } ?>

                <?php if(isset($_SESSION['messageSuccess'])){ //вывод сообщения о успешной регистрации?>
                <div class="Error Success" id="messageError">
                    <h3><?php echo $_SESSION['messageSuccess'];?></h3>
                    <button type="button" class="closeError" tabindex="0" onclick="Close()">X</button>
                </div>
                <?php unset($_SESSION['messageSuccess']); } ?>

                <div class="AddUser">
                    <form action="../../php/AddTeachers.php" method="POST">
                        <div><p>Введите Фамилию преподавателя</p>
                        <input type="text" name="fam">
                        </div>
                        <div><p>Введите Имя преподавателя</p>
                        <input type="text" name="name">
                        </div>
                        <div><p>Введите Отчество преподавателя</p>
                        <input type="text" name="otch">
                        </div>
                        <div>
                            <p>Выберите отделение</p>
                            <select name="Otdel" id="AddUserSelect" >
                                <option value="Value0" selected>-- --</option>
                                <?php include('../../php/autoZapolOtdel.php'); 
                                for($j=0; $j<count($corpus); $j++){
                            ?><option value="<?=$corpus[$j];?>"><?=$corpus[$j];?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div><p>Введите логин пользователя</p>
                            <input type="text" name="login">
                        </div>
                        <div><p>Введите пароль пользователя</p>
                            <input type="password" name="password">
                        </div>
                        <div><p>Введите Pin-code администратора</p>
                            <input type="password" name="pincodes">
                        </div>
                        <div>
                            <input type="submit" name="SaveUser1" value="Добавить преподавателя">
                        </div>
                    </form>
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
