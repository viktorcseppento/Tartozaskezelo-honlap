<?php
if (isset($_GET['id'])) {
    include 'dbUtil.php';
    session_start();
    $link = getDb();
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $deleteQuery = sprintf("DELETE FROM tag WHERE id = '%s'", $id);
    mysqli_query($link, $deleteQuery) or die($link->error);
    closeDb($link);
    $_SESSION['deleted'] = true;
    header('Location:'.$_SERVER['HTTP_REFERER']);
}
