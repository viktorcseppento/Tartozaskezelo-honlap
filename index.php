<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
          integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <title>Tartozáskezelő</title>
</head>
<body>
<?php
session_start();
if (isset($_GET['logout'])) {
    $_SESSION['userId'] = "";
    $_SESSION['userName'] = "";
    $_SESSION['userPassword'] = "";
    $_SESSION['userEmail'] = "";
    $_SESSION['LOGGED_IN'] = false;
    $_SESSION['admin'] = false;
}
include 'menu.php';

if (isset($_SESSION["LOGGED_IN"]) && $_SESSION["LOGGED_IN"] === true) {
    include "main.php";
} else {
    include 'login.php';
}
?>
<?php include "footer.html"; ?>
</body>
</html>