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
function randomPassword()
{ //Random jelszót generál
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, strlen($alphabet) - 1);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function sendMail($email, $jelszo)
{
    $msg = sprintf("Kedves Felhasználó!\n\nÚj jelszava: %s\nBármikor megváltoztathatja a jelszavát.\n\nTovábbi kellemes napot!", $jelszo);
    mail($email, "Jelszó frissítése", $msg, "From: gdviktor97@gmail.com\r\n");
}

include 'menu.php';
include 'dbUtil.php';
if (isset($_POST['kuldes']) && isset($_POST['nev']) && isset($_POST['szid'])) {
    $link = getDb();
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $szid = mysqli_real_escape_string($link, $_POST['szid']);

    $checkingQuery = sprintf("SELECT id FROM tag WHERE id = '%s' and nev = '%s'", $szid, $nev);

    $result = $link->query($checkingQuery) or die($link->error);
    if ($result->num_rows == 0) {
        $noId = true;
    } else {
        $ujJelszo = randomPassword();
        $hash = password_hash($ujJelszo, PASSWORD_DEFAULT);
        $selectQuery = sprintf("SELECT email FROM tag WHERE id = '%s' and nev = '%s'", $szid, $nev);
        $result = mysqli_query($link, $selectQuery) or die($link->error);
        $email = mysqli_fetch_array($result)[0];
        $updateQuery = sprintf("UPDATE tag SET jelszo = '%s' WHERE id='%s'", (string)$hash, $szid);
        mysqli_query($link, $updateQuery) or die($link->error);
        closeDb($link);
        sendMail($email, $ujJelszo);
        $_SESSION["forgot"] = true;
        header('Location:index.php');
    }
    closeDb($link);
}

?>
<body>
<form method="post" action="">
    <div class="container" style="width: 500px; margin-top: 10px;">
        <div class="card">
            <div class="card-header">
                Elfelejtett jelszó<br/>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="nev">Felhasználónév</label>
                    <input required class="form-control" name="nev" type="text" title="Felhasználónév"/>
                </div>
                <div class="form-group">
                    <label for="szid">Személyi szám</label>
                    <input class="form-control" name="szid" type="text" title="Személyi szám" maxlength="8"
                           minlength="8" required>
                </div>
            </div>
            <div class="card-footer">
                <input class="btn myButton" name="kuldes" type="submit" value="Küldés"/>
                <?php if (isset($noId) && $noId === true): ?>
                    <div class="alert alert-danger">
                        <strong>Hiba!</strong>
                        Nincs ilyen felhasználónév személyi szám páros!
                    </div>
                    <?php $noId = false;
                endif; ?>

                <?php if (isset($noName) && $noName === true): ?>
                    <div class="alert alert-danger">
                        <strong>Hiba!</strong>
                        Nincs ilyen felhasználónév!
                    </div>
                    <?php $noName = false;
                endif; ?>
            </div>
        </div>
    </div>
</form>
<?php include "footer.html"; ?>
</body>
</html>