<html>
<head>
    <title>Tartozáskezelő</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
</head>


<?php
session_start();
include 'menu.php';
include 'dbUtil.php';

$link = getDb();
$userNameQuery = sprintf("SELECT id, nev FROM tag WHERE id != '%s' ORDER BY nev", $_SESSION['userId']);
$result = $link->query($userNameQuery) or die($link->error);

if (isset($_POST['optradio']) && isset($_POST['combo']) && isset($_POST['ertek']) && isset($_POST['megnevezes']) && isset($_POST['nap'])) {
    $ertek = mysqli_real_escape_string($link, $_POST['ertek']);
    $megnevezes = mysqli_real_escape_string($link, $_POST['megnevezes']);
    $masikFel = mysqli_real_escape_string($link, $_POST['combo']);
    $nap = mysqli_real_escape_string($link, $_POST['nap']);
    $enTartozok = $_POST['optradio'];
    if ($enTartozok === "true") {
        $insertQuery = sprintf("INSERT INTO tartozas (ertek, megnevezes, lejaratDate, ado, tartozo) VALUES ('%s', '%s', ADDDATE(now(), INTERVAL '%s' DAY), '%s', '%s')"
            , $ertek, $megnevezes, $nap, $masikFel, $_SESSION['userId']);
    } else {
        $insertQuery = sprintf("INSERT INTO tartozas (ertek, megnevezes, lejaratDate, ado, tartozo) VALUES ('%s', '%s', ADDDATE(now(), INTERVAL '%s' DAY), '%s', '%s')"
            , $ertek, $megnevezes, $nap, $_SESSION['userId'], $masikFel);
    }
    $link->query($insertQuery) or die($link->error);
    closeDb($link);
    $_SESSION['lendingAdded'] = true;
    header("Location:index.php");
}

?>
<body>
<form method="post" action="">
    <div class="container" style="width: 500px; margin-top: 10px;">
        <div class="card">
            <div class="card-header">
                Új tartozás felvétele<br/>
            </div>

            <div class="card-body">
                <div class="radio">
                    <label><input type="radio" name="optradio" value="true" checked="checked">Én tartozok</label>
                </div>

                <div class="radio">
                    <label><input type="radio" name="optradio" value="false">Nekem tartoznak</label>
                </div>

                <div class="form-group">
                    <label for="combo">Ki tartozik/kinek tartozol?</label>
                    <select class="form-control" title="Másik fél" name="combo">
                        <?php while ($row = $result->fetch_array()): ?>
                            <option value=<?= $row['id'] ?>><?= $row['nev'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ertek">Érték (Ft)</label>
                    <input required class="form-control" name="ertek" min="0" type="number" title="Érték">
                </div>
                <div class="form-group">
                    <label for="megnevezes">Megnevezés</label>
                    <input class="form-control" name="megnevezes" type="text" title="Megnevezés">
                </div>
                <div class="form-group">
                    <label for="nap">Hány nap múlva jár le?</label>
                    <input class="form-control" name="nap" type="number" min="0" title="Lejárati idő" required>
                </div>
            </div>

            <div class="card-footer">
                <input class="btn myButton" name="uj" type="submit" value="Létrehozás"/>
            </div>
        </div>
    </div>
</form>
<?php closeDb($link);
include "footer.html"; ?>
</body>
</html>