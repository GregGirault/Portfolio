<?php
session_start();
require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT archived FROM projet WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch();
    $currentState = $result['archived'];

    $newState = ($currentState) ? "FALSE" : "TRUE";

    $sql = "UPDATE projet SET archived = $newState WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();

    header("Location: historique.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
