<?php


require_once 'header.php';
require_once __DIR__ . '/db-connect.php';

//ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$userId = $_SESSION['userId'];

try {
    //Getting users details
    $stmt = $db->prepare('SELECT *
                                   FROM users
                                   WHERE users.user_type_id_fk = :typeSitter');

    $stmt->bindValue(':typeSitter', $typeSitter);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if (count($aRows) == 0) {
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}
