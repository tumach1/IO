<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="public/img/favicon.ico">
    <title>Manga</title>
    <link rel="stylesheet" href="public/css/register.css" type="text/css">
    <script type="text/javascript" src="./public/js/script.js" defer></script>
</head>
<body>
<div id="container">
    <div id="main_content">
        <div id="banner">
            <h1><a id="main_page_link" href="/">Rejestracja</a></h1>
        </div>
        <div id="messages">
            <?php
            if (isset($messages)) {
                foreach ($messages as $message) {
                    echo $message;
                }
            }
            ?>
        </div>
        <form action="" method="POST">
            <input class="userButton" type="text" name="imie" id="imie" placeholder="imię" required>
            <input class="userButton" type="text" name="nazwisko" id="nazwisko" placeholder="nazwisko" required>
            <input class="userButton" type="text" name="pesel" id="pesel" placeholder="pesel" required>
            <input class="userButton" type="email" name="email" id="email" placeholder="email" required>
            <input class="userButton" type="text" name="ulica" id="ulica" placeholder="ulica" required>
            <select class="userButton" name="miejscowosc" required>
                <?php
                require_once __DIR__.'/../../src/repository/CitiesRepository.php';
                $repo = new CitiesRepository();
                foreach ($repo->getCities() as $city) {
                    echo '<option value="'.$city->getId().'">'.$city->getName().'</option>';
                }
                ?>
            </select>
            <input class="userButton" type="text" name="kod_pocztowy" id="kod_pocztowy" placeholder="kod-pocztowy" required>
            <input class="userButton" type="password" name="password" id="password" placeholder="hasło" required>
            <input class="userButton" type="password" name="confirmedPassword" id="confirmedPassword" placeholder="powtórz hasło" required>
            <input id="registerButton" type="submit" value="Zarejestruj się">
        </form>
        <div id="helper_buttons">
            <a href="./login"><button id="backToLoginButton">Powrót do logowania</button></a>
        </div>
    </div>
</div>
</body>
</html>