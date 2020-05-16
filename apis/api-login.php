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
    //Checking if the password and email match
    $stmt = $db->prepare( "SELECT user_id, password FROM users WHERE email=:email" );
    $stmt->bindValue(':email', $email );
    $stmt->execute();
    $aRows = $stmt->fetchAll();

    //Verifying hashed password
    if (!password_verify($password, $aRows[0]->password)) {

        echo 'Wrong password, try again!';
        exit;

    }

   $userId = $aRows[0]->user_id;
    echo "User id: " . $userId;

   if( count($aRows) == 0 ){

        echo 'Sorry, no user with that credentials found!';
        exit;
    }

   //Checking if a user already has a saved account number
    $stmt = $db->prepare( "SELECT account_id FROM accounts WHERE user_id_fk=:userId" );
    $stmt->bindValue(':userId', $userId );
    $stmt->execute();
    $aAccountRows = $stmt->fetchAll();

    if( count($aAccountRows) == 0 ){
        header("refresh:3;url=../bank-account.php");
    }else{
        header("refresh:3;url=../index.php");
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

session_start();
$_SESSION['userId'] = $userId;

sendResponse(1, __LINE__, "Successfully logged in!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}