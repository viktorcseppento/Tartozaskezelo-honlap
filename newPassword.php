<html>
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
if (isset($_POST['kuldes']) && isset($_POST['oldPass']) && isset($_POST['newPass'])) {
    $link = getDb();
    $oldPass = mysqli_real_escape_string($link, $_POST['oldPass']);
    $newPass = mysqli_real_escape_string($link, $_POST['newPass']);

    if (password_verify($oldPass, $_SESSION['userPassword'])) {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $updateQuery = sprintf("UPDATE tag SET jelszo = '%s' WHERE id='%s'", (string)$hash, $_SESSION['userId']);
        $_SESSION['userPassword'] = $hash;
        mysqli_query($link, $updateQuery) or die($link->error);
        closeDb($link);
        $_SESSION['passwordChanged'] = true;
        header('Location:userSettings.php');
    } else {
        $wrongPass= true;
        closeDb($link);
    }
}
?>
<body>
<form method="post" action="">
    <div class="container" style="width: 500px; margin-top: 10px;">
        <div class="card">
            <div class="card-header">
                Jelszó változtatása<br/>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="oldPass">Régi jelszó</label>
                    <input required class="form-control" name="oldPass" type="password" title="Régi jelszó"/>
                </div>
                <div class="form-group">
                    <label for="newPass">Új jelszó</label>
                    <input required class="form-control" name="newPass" type="password" title="Új jelszó" minlength="0"/>
                </div>

            </div>
            <div class="card-footer">
                <input class="btn myButton" name="kuldes" type="submit" value="Küldés"/>
                <?php if (isset($wrongPass) && $wrongPass === true): ?>
                    <div class="alert alert-danger">
                        <strong>Hiba!</strong>
                        Téves régi jelszó!
                    </div>
                    <?php $wrongPass = false;
                endif; ?>
            </div>
        </div>
    </div>
</form>
<?php include "footer.html"; ?>
</body>
</html>