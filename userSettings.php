<head>
    <meta charset="utf-8">
    <title>Tartozáskezelő</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
</head>


<?php
session_start();
include 'menu.php';
include 'dbUtil.php';

?>
<body>
<div class="container">
    <?php if (isset($_SESSION['passwordChanged']) && $_SESSION['passwordChanged'] === true): ?>
        <br/><div class="alert alert-info">
            <strong>Sikeres művelet!</strong>
            Jelszó sikeresen megváltozott!
        </div>
        <?php $_SESSION['passwordChanged'] = false;
    endif; ?>
    <div class="jumbotron">

        <h1>Adataid</h1>
        <p class="lead"><br/>Személyi szám:&nbsp;&nbsp;&nbsp;<?= $_SESSION['userId'] ?></p>
        <p class="lead">Felhasználónév:&nbsp;&nbsp;&nbsp;<?= $_SESSION['userName'] ?></p>
        <p class="lead">E-mail cím:&nbsp;&nbsp;&nbsp;<?= $_SESSION['userEmail'] ?></p>
        <a  href="newPassword.php" class="lead">Jelszó megváltoztatása</a>
    </div>
</div>
<?php include "footer.html"; ?>
</body>
</html>