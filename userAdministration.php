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
include "dbUtil.php";
include 'menu.php';
$link = getDb();
$userQuery = "SELECT nev, email, id FROM tag ORDER BY nev";
$result = $link->query($userQuery) or die($link->error);
?>
<div class="container">

    <?php if (isset($_SESSION['deleted']) && $_SESSION['deleted'] === true): ?>
        <br/>
        <div class="alert alert-info">
            <strong>Sikeres művelet!</strong>
            Felhasználó törölve!
        </div>
        <?php $_SESSION['deleted'] = false;
    endif; ?>

    <div class="jumbotron">
        <h1>Regisztrált tagok</h1>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Név</th>
                <th scope="col">E-mail cím</th>
                <th scope="col">Személyi szám</th>
                <th scope="col" id="center">Törlés</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_array()): ?>
                <tr>
                    <td><?= $row['nev'] ?></td>
                    <td><a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a></td>
                    <td><?= $row['id'] ?></td>
                    <?php if ($_SESSION['userId'] <> $row['id']): ?>
                        <td align="center">
                            <a href="deleteUser.php?id=<?= $row['id'] ?>"><i class="fas fa-trash"></i></a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
            </tbody>

        </table>

    </div>

</div>
<?php include "footer.html";
closeDb($link) ?>
</body>
</html>