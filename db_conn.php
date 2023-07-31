<?php

    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "portfolio";
    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    if (!$conn){
        echo "Connexion failed!";
        exit();
    }
