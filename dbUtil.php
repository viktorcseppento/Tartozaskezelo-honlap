﻿<?php

function getDb() {
    $link = mysqli_connect("localhost", "root", "")
    or die("Kapcsolódási hiba: " . $link->error);
    mysqli_select_db($link, "tartozasok");
    mysqli_query ($link, "set character_set_results='utf8'");
    mysqli_query ($link, "set character_set_client='utf8'");
    return $link;
}

function closeDb($link) {
    mysqli_close($link);
}