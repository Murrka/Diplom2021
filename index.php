<?php
    session_start();
    if(!isset($_SESSION['messageError'])){
        unset($_SESSION['messageError']);
    }
?>
<html lang="en">
    <head>
        <title>Дистанционное обучение</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="CSS/indexCSS.css" rel="stylesheet">
    </head>
    <script>
        function Close(){
            document.getElementById('messageError').style.display='none';
        }
    </script>
    <body>
        <!--Голова сайта-->
        <section class="SectionHead">
            <div class="logo-title">
                <img src="IMG/logo.png" alt="logo">
            </div>
            <div class="h1-title">
                <h1>Единый образовательный ресурс дистанционного обучения</h1>
            </div>   
        </section>
        <!--Тело сайта-->
        <section class="SectionBody">
            <form class="FormBody" action="../../php/login.php" method="POST">
                <div class="FormDiv">
                <H1>Войдите в систему.</H1>
                </div>
                <div class="Email-div">
                    <h2>Логин</h2>
                    <input type="text" class="Email-body" id="Email" name="Login">
                </div>
                <div class="Password-div">
                    <h2>Пароль</h2>
                    <input type="password" class="Password-body" id="Password" name="Password">
                </div>
                <div class="FormSupport">
                    <h3><a href="#" >Забыли пароль?</a></h3>
                </div>
                <div>
                    <button type="submit" class="FormButton" >Вход</button>
                </div>
                <?php if(isset($_SESSION['messageError'])){ //вывод сообщения об ошибке при регистрации?>
                <div class="Error Index" id="messageError">
                    <h3><?php echo $_SESSION['messageError'];?></h3>
                    <button type="button" class="closeError" tabindex="0" onclick="Close()">X</button>
                </div>
                <?php unset($_SESSION['messageError']); } ?>
            </form>
            
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