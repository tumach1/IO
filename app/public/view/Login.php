<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico">
    <title>Sklep</title>
    <link rel="stylesheet" href="/public/css/login.css" type="text/css">
</head>
<body>
<div id="container">
    <div id="main_content">
        <form action="login" method="POST">
            <div id="messages">
                <?php
                if (isset($messages)) {
                    foreach ($messages as $message) {
                        echo $message;
                    }
                }
                ?>
            </div>
            <input class="userButton" type="text" name="email" placeholder="email" required> <br>
            <input class="userButton" type="password" name="password" placeholder="hasło" required> <br>
            <div class="switch-container">
                <label class="switch">
                    <input type="checkbox" name="isWorker">
                    <span class="slider round"></span>
                </label>
                <span class="worker-text">Zaloguj na konto pracownicze</span>
            </div>
            <input id="loginButton" type="submit" value="Zaloguj">
        </form>
        <div id="helper_buttons">
            <a href="./ReaderRegister"><button id="registerButton">Nie masz konta? Zarejestruj się</button></a>
            <a href="./ResetPassword"><button id="resetPassword">Zapomniałeś hasła? zresetuj</button></a>
            <a href="./"><button id="mainPageButton">Powrót do strony głównej</button></a>
        </div>
    </div>
</div>
</body>
</html>