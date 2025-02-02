<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico">
    <title>Biblioteka</title>
    <link rel="stylesheet" href = "/public/css/login.css" type="text/css">
</head>
<body>
<div id="container">
 
    <div id="main_content">
        <div id="messages">
            <?php
                if(isset($messages)){
                    foreach ($messages as $message){
                        echo $message;
                    }
                }
                ?>
        </div>
        <form action=""class="flex-column-center-center">
            <input class="userButton" type="text" name="email" placeholder="email" required> <br>
            <input id="loginButton" type="submit" value="Zresetuj hasło">
        </form>
        <div id="helper_buttons">
            <a href="./login"><button id="backToLoginButton">Powrót do logowania</button></a>
        </div>
    </div>
    
</div>
</body>
</html>