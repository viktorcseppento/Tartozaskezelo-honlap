<?php
include 'dbUtil.php';
$link = getDb();
$orderByString = "";
if (isset($_POST['order'])) {
    switch ($_POST['order']) {
        case 1:
            $orderByString = " ORDER BY ertek ASC";
            break;
        case 2:
            $orderByString = " ORDER BY ertek DESC";
            break;
        case 3:
            $orderByString = " ORDER BY lejaratDate ASC";
            break;
        case 4:
            $orderByString = " ORDER BY lejaratDate DESC";
            break;
        case 5:
            $orderByString = " ORDER BY felvetelDate ASC";
            break;
        case 6:
            $orderByString = " ORDER BY felvetelDate DESC";
            break;
    }
}
$searchString = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($link, $_POST['search']);
    $searchString = sprintf(" and (nev like '%%%s%%' or megnevezes like '%%%s%%')", $_POST['search'], $_POST['search']);
}

$tartozasQuery = sprintf("SELECT nev, ertek, megnevezes, felvetelDate, lejaratDate FROM tartozas inner join tag on ado = tag.id WHERE tartozo = '%s'", $_SESSION['userId']) . $searchString . $orderByString . ";";
$tartozasResult = $link->query($tartozasQuery) or die($link->error);

$orderByString2 = "";
if (isset($_POST['order2'])) {
    switch ($_POST['order2']) {
        case 1:
            $orderByString2 = " ORDER BY ertek ASC";
            break;
        case 2:
            $orderByString2 = " ORDER BY ertek DESC";
            break;
        case 3:
            $orderByString2 = " ORDER BY lejaratDate ASC";
            break;
        case 4:
            $orderByString2 = " ORDER BY lejaratDate DESC";
            break;
        case 5:
            $orderByString2 = " ORDER BY felvetelDate ASC";
            break;
        case 6:
            $orderByString2 = " ORDER BY felvetelDate DESC";
            break;
    }
}
$searchString2 = "";
if (isset($_POST['search2'])) {
    $search2 = mysqli_real_escape_string($link, $_POST['search2']);
    $searchString2 = sprintf(" and (nev like '%%%s%%' or megnevezes like '%%%s%%')", $_POST['search2'], $_POST['search2']);
}

$kolcsonQuery = sprintf("SELECT tartozas.id id, nev, ertek, megnevezes, felvetelDate, lejaratDate FROM tartozas inner join tag on tartozo = tag.id WHERE ado = '%s'", $_SESSION['userId']) . $searchString2 . $orderByString2 . ";";
$kolcsonResult = $link->query($kolcsonQuery) or die($link->error);

?>
    <body>
    <div class="container">
        <?php if (isset($_SESSION['lendingAdded']) && $_SESSION['lendingAdded'] === true): ?>
            <br/>
            <div class="alert alert-info">
                <strong>Sikeres művelet!</strong>
                Tartozás hozzáadva!
            </div>
            <?php $_SESSION['lendingAdded'] = false;
        endif; ?>

        <?php if (isset($_SESSION['deleted']) && $_SESSION['deleted'] === true): ?>
            <br/>
            <div class="alert alert-info">
                <strong>Sikeres művelet!</strong>
                Kölcsön törölve!
            </div>
            <?php $_SESSION['deleted'] = false;
        endif; ?>


        <div class="jumbotron">
            <h1 style="display: inline">Tartozásaid</h1>
            <form class="form-inline float-right" method="post">
                <select class="custom-select" title="Rendezés" name="order">
                    <option value="1" <?php if (isset($_POST['order']) && $_POST['order'] == 1) {
                        echo "selected";
                    } ?>>Érték szerint növekvő
                    </option>
                    <option value="2" <?php if (isset($_POST['order']) && $_POST['order'] == 2) {
                        echo "selected";
                    } ?>>Érték szerint csökkenő
                    </option>
                    <option value="3" <?php if (isset($_POST['order']) && $_POST['order'] == 3) {
                        echo "selected";
                    } ?>>Lejárat szerint növekvő
                    </option>
                    <option value="4" <?php if (isset($_POST['order']) && $_POST['order'] == 4) {
                        echo "selected";
                    } ?>>Lejárat szerint csökkenő
                    </option>
                    <option value="5" <?php if (isset($_POST['order']) && $_POST['order'] == 5) {
                        echo "selected";
                    } ?>>Felvétel szerint növekvő
                    </option>
                    <option value="6" <?php if (isset($_POST['order']) && $_POST['order'] == 6) {
                        echo "selected";
                    } ?>>Felvétel szerint csökkenő
                    </option>
                </select>
                <input style="margin-left: 10px; margin-right: 10px" class="form-control" type="text"
                       placeholder="Keresés" name="search" value=<?php if (isset($_POST['search'])) {
                    echo $_POST['search'];
                } ?>>
                <button type="submit" class="btn btn-primary">Mehet</button>
            </form>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Kinek</th>
                    <th scope="col">Érték (Ft)</th>
                    <th scope="col">Megnevezés</th>
                    <th scope="col">Felvétel dátuma</th>
                    <th scope="col">Lejárat dátuma</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($tartozasResult->num_rows === 0): ?>
                    <tr class="table-active">
                        <td align="center" colspan="5">Nincsen megfelelő tartozás!</td>
                    </tr>
                <?php endif; ?>
                <?php while ($row = $tartozasResult->fetch_array()): ?>
                    <?php if (strtotime($row['lejaratDate']) < strtotime(date("Y-m-d H:i:s"))) {
                        echo '<tr class="table-danger">';
                    } else if (strtotime($row['lejaratDate']) < strtotime('+2 day', time())) {
                        echo '<tr class="table-warning">';
                    } else {
                        echo '<tr class="table-info">';
                    } ?>
                    <td><?= $row['nev'] ?></td>
                    <td><?= $row['ertek'] ?></td>
                    <td><?= $row['megnevezes'] ?></td>
                    <td><?= $row['felvetelDate'] ?></td>
                    <td><?= $row['lejaratDate'] ?></td>
                    <?php echo '</tr>';
                endwhile; ?>
                </tbody>

            </table>
            <h1 style="display: inline">Kölcsöneid</h1>
            <form class="form-inline float-right" method="post">
                <select class="custom-select" title="Rendezés" name="order2">
                    <option value="1" <?php if (isset($_POST['order2']) && $_POST['order2'] == 1) {
                        echo "selected";
                    } ?>>Érték szerint növekvő
                    </option>
                    <option value="2" <?php if (isset($_POST['order2']) && $_POST['order2'] == 2) {
                        echo "selected";
                    } ?>>Érték szerint csökkenő
                    </option>
                    <option value="3" <?php if (isset($_POST['order2']) && $_POST['order2'] == 3) {
                        echo "selected";
                    } ?>>Lejárat szerint növekvő
                    </option>
                    <option value="4" <?php if (isset($_POST['order2']) && $_POST['order2'] == 4) {
                        echo "selected";
                    } ?>>Lejárat szerint csökkenő
                    </option>
                    <option value="5" <?php if (isset($_POST['order2']) && $_POST['order2'] == 5) {
                        echo "selected";
                    } ?>>Felvétel szerint növekvő
                    </option>
                    <option value="6" <?php if (isset($_POST['order2']) && $_POST['order2'] == 6) {
                        echo "selected";
                    } ?>>Felvétel szerint csökkenő
                    </option>
                </select>
                <input style="margin-left: 10px; margin-right: 10px" class="form-control" type="text"
                       placeholder="Keresés" name="search2" value=<?php if (isset($_POST['search2'])) {
                    echo $_POST['search2'];
                } ?>>
                <button type="submit" class="btn btn-primary">Mehet</button>
            </form>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Kinek</th>
                    <th scope="col">Érték (Ft)</th>
                    <th scope="col">Megnevezés</th>
                    <th scope="col">Felvétel dátuma</th>
                    <th scope="col">Lejárat dátuma</th>
                    <th scope="col" id="center">Törlés</th>
                </tr>
                </thead>
                <tbody>

                <?php if ($kolcsonResult->num_rows === 0): ?>
                    <tr class="table-active">
                        <td align="center" colspan="6">Nincsen megfelelő kölcsön!</td>
                    </tr>
                <?php endif; ?>
                <?php while ($row = $kolcsonResult->fetch_array()): ?>
                    <?php if (strtotime($row['lejaratDate']) < strtotime(date("Y-m-d H:i:s"))) {
                        echo '<tr class="table-danger">';
                    } else if (strtotime($row['lejaratDate']) < strtotime('+2 day', time())) {
                        echo '<tr class="table-warning">';
                    } else {
                        echo '<tr class="table-info">';
                    } ?>
                    <td><?= $row['nev'] ?></td>
                    <td><?= $row['ertek'] ?></td>
                    <td><?= $row['megnevezes'] ?></td>
                    <td><?= $row['felvetelDate'] ?></td>
                    <td><?= $row['lejaratDate'] ?></td>
                    <td align="center">
                        <a href="deleteLending.php?id=<?= $row['id'] ?>"><i class="fas fa-trash"></i></a>
                    </td>

                    <?php echo '</tr>';
                endwhile; ?>
                </tbody>

            </table>

        </div>
    </div>
    </body>
<?php closeDb($link) ?>