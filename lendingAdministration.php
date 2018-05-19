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

$orderByString = " ORDER BY ertek ASC";
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
        case 7:
            $orderByString = " ORDER BY t1.nev ASC";
            break;
        case 8:
            $orderByString = " ORDER BY t1.nev DESC";
            break;
        case 9:
            $orderByString = " ORDER BY t2.nev ASC";
            break;
        case 10:
            $orderByString = " ORDER BY t2.nev DESC";
            break;
    }
}
$searchString = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($link, $_POST['search']);
    $searchString = sprintf(" WHERE t1.nev LIKE '%%%s%%' OR t2.nev LIKE '%%%s%%' OR megnevezes LIKE '%%%s%%'", $_POST['search'], $_POST['search'], $_POST['search']);
}

$tartozasQuery = sprintf("SELECT tartozas.id, t1.nev ki, t2.nev kinek, ertek, megnevezes, felvetelDate, lejaratDate FROM tartozas inner join tag t1 on tartozo = t1.id inner join tag t2 on ado = t2.id") . $searchString . $orderByString . ";";
$tartozasResult = $link->query($tartozasQuery) or die($link->error);
?>

<div class="container">

    <?php if (isset($_SESSION['deleted']) && $_SESSION['deleted'] === true): ?>
        <br/>
        <div class="alert alert-info">
            <strong>Sikeres művelet!</strong>
            Tartozás törölve!
        </div>
        <?php $_SESSION['deleted'] = false;
    endif; ?>

    <div class="jumbotron">
        <h1 style="display: inline">Tartozások</h1>

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
                <option value="7" <?php if (isset($_POST['order']) && $_POST['order'] == 7) {
                    echo "selected";
                } ?>>Tartozó szerint növekvő
                </option>
                <option value="8" <?php if (isset($_POST['order']) && $_POST['order'] == 8) {
                    echo "selected";
                } ?>>Tartozó szerint csökkenő
                </option>
                <option value="9" <?php if (isset($_POST['order']) && $_POST['order'] == 9) {
                    echo "selected";
                } ?>>Adó szerint növekvő
                </option>
                <option value="10" <?php if (isset($_POST['order']) && $_POST['order'] == 10) {
                    echo "selected";
                } ?>>Adó szerint csökkenö
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
                <th scope="col">Ki</th>
                <th scope="col">Kinek</th>
                <th scope="col">Érték (Ft)</th>
                <th scope="col">Megnevezés</th>
                <th scope="col">Felvétel dátuma</th>
                <th scope="col">Lejárat dátuma</th>
                <th scope="col" id="center">Törlés</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($tartozasResult->num_rows === 0): ?>
                <tr class="table-active">
                    <td align="center" colspan="7">Nincsen megfelelő tartozás!</td>
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
                <td><?= $row['ki'] ?></td>
                <td><?= $row['kinek'] ?></td>
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
<?php
include "footer.html";
closeDb($link) ?>
</body>
</html>