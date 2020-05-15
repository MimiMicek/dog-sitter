<?php

require_once __DIR__.'/../db-connect.php';

ini_set('display_errors', 0);

$email = $_POST['email'] ?? '';
if(empty($email)){ sendResponse(0, __LINE__, 'Please enter email!'); }
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    sendResponse(0, __LINE__, 'Please enter a valid email!');
}

$password = $_POST['password'] ?? '';
if(empty($password)){ sendResponse(0, __LINE__, 'Please enter password!'); }
if(strlen($password) < 4){ sendResponse(0, __LINE__, 'Passwords cannot be less than 4 characters!'); }
if(strlen($password) > 30){ sendResponse(0, __LINE__, 'Passwords cannot be longer than 30 characters!'); }

try{

    $stmt = $db->prepare( "SELECT user_id, password FROM users WHERE email=:email" );
    $stmt->bindValue(':email', $email );
    $stmt->execute();
    $aRows = $stmt->fetchAll();

    if (!password_verify($password, $aRows[0]->password)) {

        echo 'Wrong password, try again!';
        exit;

    }

   $userId = $aRows[0]->user_id;
   //echo "User id is: ". $userId;

   if( count($aRows) == 0 ){

        echo 'Sorry, no user with that credentials found!';
        exit;
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

session_start();
$_SESSION['userId'] = $userId;
header("refresh:3;url=../index.php");
sendResponse(1, __LINE__, "Successfully logged in!");


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}