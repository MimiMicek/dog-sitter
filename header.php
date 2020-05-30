<?php

require_once __DIR__ . '/db-connect.php';

ini_set('display_errors', 0);
session_start();

$userId = $_SESSION['userId'];
//var_dump($userId);
$typeOwner = 2;
$typeSitter = 3;

try {
    //Getting owners ids
    $stmt = $db->prepare('SELECT users.user_id
                                   FROM users
                                   WHERE users.user_type_id_fk = :typeOwner');

    $stmt->bindValue(':typeOwner', $typeOwner);

    $stmt->execute();

    $aOwnerIdRows = $stmt->fetchAll();

    foreach ($aOwnerIdRows as $row){
        $listOfOwnerIds[] = $row->user_id;
    }

    if (count($aOwnerIdRows) == 0) {
        echo 'Sorry no users found!';
    }

    //Getting sitters ids
    $stmt = $db->prepare('SELECT users.user_id
                                   FROM users
                                   WHERE users.user_type_id_fk = :typeSitter');

    $stmt->bindValue(':typeSitter', $typeSitter);

    $stmt->execute();

    $aSitterIdRows = $stmt->fetchAll();

    foreach ($aSitterIdRows as $row){
        $listOfSitterIds[] = $row->user_id;
    }

    if (count($aSitterIdRows) == 0) {
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Dog sitter</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/cover/">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <meta name="theme-color" content="#563d7c">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
        <!-- Custom styles for this template -->
        <link href="css/header.css" rel="stylesheet">

    </head>
    <body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                <h3 class="masthead-brand">DOG SITTER™</h3>
                <nav class="nav nav-masthead justify-content-center">
                    <a class="nav-link active" href="index">Home</a>
                    <?php
                        if (!$userId){
                            echo '<a class="nav-link" href="register">Register</a>
                                  <a class="nav-link" href="login">Login</a>';
                        }
                    ?>
                    <?php
                        if ($userId){
                            echo '<a class="nav-link" href="my-profile">My profile</a>
                                  <a class="nav-link" href="my-messages">My messages</a>';

                            if (!in_array($userId, $listOfSitterIds)){
                                echo '<a class="nav-link" href="find-sitter">Find a sitter</a>';
                            }

                            if (!in_array($userId, $listOfOwnerIds)){
                                echo ' <a class="nav-link" href="find-dog">Find a dog</a>';
                            }

                            echo '<a class="nav-link" href="apis/api-logout.php">Logout</a>';
                        }
                    ?>

                </nav>
            </div>
        </header>


