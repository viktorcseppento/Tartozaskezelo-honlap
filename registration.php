<html>
<head>
    <title>Tartozáskezelő</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
</head>


<?php
include 'menu.php';
include 'dbUtil.php';
if (isset($_POST['uj']) && isset($_POST['nev']) && isset($_POST['jelszo']) && isset($_POST['email']) && isset($_POST['szid'])) {
    $link = getDb();
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $jelszo = password_hash(mysqli_real_escape_string($link, $_POST['jelszo']), PASSWORD_DEFAULT);

    $email = mysqli_real_escape_string($link, $_POST['email']);
    $szid = mysqli_real_escape_string($link, $_POST['szid']);

    $checkingQuery = sprintf("SELECT id FROM tag WHERE id = '%s'", $szid);
    $checkingQuery2 = sprintf("SELECT nev FROM tag WHERE nev = '%s'", $nev);

    $result = $link->query($checkingQuery) or die($link->error);
    if ($result->num_rows <> 0) {
        $idTaken = true;
    } else {
        $result = $link->query($checkingQuery2) or die($link->error);
        if ($result->num_rows <> 0) {
            $nameTaken = true;
        } else {
            $insertQuery = sprintf("INSERT INTO tag (id, nev, jelszo, email) VALUES ('%s', '%s', '%s', '%s')", $szid, $nev, $jelszo, $email);
            mysqli_query($link, $insertQuery) or die($link->error);
            closeDb($link);
            header('Location:index.php');
        }
    }
    closeDb($link);
}

?>
<body>
<form method="post" action="">
    <div class="container" style="width: 500px; margin-top: 10px;">
        <div class="card">
            <div class="card-header">
                Regisztráció<br/>

            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="nev">Felhasználónév</label>
                    <input required class="form-control" name="nev" type="text" title="Felhasználónév">
                </div>
                <div class="form-group">
                    <label for="jelszo">Jelszó</label>
                    <input required class="form-control" name="jelszo" type="password" title="Jelszó" minlength="0">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" name="email" type="email" title="E-mail cím" required>
                </div>
                <div class="form-group">
                    <label for="szid">Személyi szám</label>
                    <input class="form-control" name="szid" type="text" title="Személyi szám" maxlength="8" minlength="8" required>
                </div>

            </div>
            <div class="card-footer">
                <input class="btn myButton" name="uj" type="submit" value="Létrehozás"/>
                <?php if (isset($idTaken) && $idTaken === true): ?>
                    <div class="alert alert-danger">
                        <strong>Hiba!</strong>
                        Ez a személyi szám már regisztrálva van!
                    </div>
                    <?php $idTaken = false;
                endif; ?>

                <?php if (isset($nameTaken) && $nameTaken === true): ?>
                    <div class="alert alert-danger">
                        <strong>Hiba!</strong>
                        Felhasználóneved már regisztrálva van!
                    </div>
                    <?php $nameTaken = false;
                endif; ?>
            </div>
        </div>
    </div>
</form>
<?php include "footer.html";?>
</body>
</html>