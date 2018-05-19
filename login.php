<?php
include 'dbUtil.php';
if (isset($_POST['bejelentkezes']) && isset($_POST['nev']) && isset($_POST['jelszo'])) {
    $link = getDb();
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $jelszo = mysqli_real_escape_string($link, $_POST['jelszo']);
    $selectQuery = sprintf("SELECT id, jelszo, email FROM tag WHERE nev = '%s'", $nev);
    $result = $link->query($selectQuery) or die($link->error);
    if ($result->num_rows === 0) {
        $wrongAuth = true;
    } else {
        $user = mysqli_fetch_array($result);
        if (!password_verify($jelszo, $user['jelszo'])) {
            $wrongAuth = true;
        } else {
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userName'] = $nev;
            $_SESSION['userPassword'] = $user['jelszo'];
            $_SESSION['userEmail'] = $user['email'];
            $_SESSION['LOGGED_IN'] = true;
            if($_SESSION['userId'] == '955071MA')
                $_SESSION['admin'] = true;
            else
                $_SESSION['admin'] = false;
            header('Location:index.php');
            closeDb($link);
        }

    }
    closeDb($link);
}

?>

<div class="container">
    <?php
    if (isset($_SESSION["forgot"]) && $_SESSION["forgot"] === true):
        ?>
        <br/><div class="alert alert-info">
            <strong>Új jelszó!</strong>
            <br/>Új jelszavát elküldtük az e-mail címére!
        </div>
        <?php
        $_SESSION["forgot"] = false;
    endif; ?>

    <?php
    if (isset($wrongAuth) && $wrongAuth === true):
        ?>
        <br/><div class="alert alert-warning">
            <strong>Hibás bemenet</strong>
            <br/>Hibás felhasználónév vagy jelszó!
        </div>
        <?php
        $wrongAuth = false;
    endif; ?>
    <div class="jumbotron">
        <h1>Tartozáskezelő rendszer</h1>


        <form class="form-inline my-2 my-lg-0" method="post">
            <input class="form-control mr-sm-2" type="text" name="nev" placeholder="Felhasználónév" required>
            <input class="form-control mr-sm-2" type="password" name="jelszo" placeholder="Jelszó" required>
            <button class="myButton btn" type="submit" name="bejelentkezes">Bejelentkezés</button>
        </form>
        <form class="form-inline my-2 my-lg-0" method="post">
            <button class="myButton btn btn-lg" type="submit" formaction="registration.php">Regisztráció</button>
            <button class="myButton btn mr-sm-2" type="submit" formaction="forgotPassword.php">Elfelejtett jelszó
            </button>
        </form>
    </div>

</div>