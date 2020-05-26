<?php

require_once __DIR__.'/../db-connect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$myUserId = $_SESSION['userId'];
$contactUserId = $_POST['contactUserId'] ?? '';
$message = $_POST['message'] ?? '';
//TODO check length of message
//TODO set a timestamp
//TODO insert into database
//TODO notify the message has been sent and redirect to index

var_dump($contactUserId, $message);