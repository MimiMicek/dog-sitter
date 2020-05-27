<?php

require_once __DIR__.'/../db-connect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$myUserId = $_SESSION['userId'];

$contactUserId = $_POST['contactUserId'] ?? '';

$message = $_POST['message'] ?? '';
if(empty($message)){ sendResponse(0, __LINE__, "Please enter information!"); }
if(strlen($message) < 10){ sendResponse(0, __LINE__, "Message cannot be less than 10 characters!"); }
if(strlen($message) > 2500){ sendResponse(0, __LINE__, "Message cannot be longer than 2500 characters!"); }

$timestamp = date("Y-m-d H:i:s");

//var_dump($myUserId, $contactUserId, $message, $timestamp);

try {

    //Saving the sent message
    $stmt = $db->prepare('INSERT INTO messages VALUES( null, :myUserId, :contactUserId, :message, :timestamp)');
    $stmt->bindValue(':myUserId', $myUserId );
    $stmt->bindValue(':contactUserId', $contactUserId );
    $stmt->bindValue(':message', $message );
    $stmt->bindValue(':contactUserId', $contactUserId );
    $stmt->bindValue(':timestamp', $timestamp );
    $stmt->execute();

    //Using rowcount() when INSERTing, UPDATEing or DELETEing
    if( $stmt->rowCount() == 0 ){
        echo 'Sorry, the message was not saved!';
        exit;
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

header("refresh:4;url=../index.php");
sendResponse(1, __LINE__, "Your message has been sent!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}