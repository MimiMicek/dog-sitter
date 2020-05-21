<?php

require_once __DIR__.'/../db-connect.php';

ini_set('display_errors', 0);

session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login');
}

$userId = $_SESSION['userId'];

$regNumber = $_POST['regNumber'] ?? '';
if(empty($regNumber)){ sendResponse(0, __LINE__, 'Please enter registration number!'); }
if(strlen($regNumber) < 4 ){ sendResponse(0, __LINE__, 'Registration number cannot be less than 4 characters!'); }
if(strlen($regNumber) > 4 ){ sendResponse(0, __LINE__, 'Registration number cannot be longer than 4 characters!'); }
if(intval($regNumber) < 1000){ sendResponse(0, __LINE__, 'Please enter a registration number!'); }
if(intval($regNumber) > 9999){ sendResponse(0, __LINE__, 'Please enter a registration number!'); }
if(!ctype_digit($regNumber)){ sendResponse(0, __LINE__, 'Registration number contains only numbers!'); }

$accountNumber = $_POST['accountNumber'] ?? '';
if(empty($accountNumber)){ sendResponse(0, __LINE__, 'Please enter registration number!'); }
if(strlen($accountNumber) < 10 ){ sendResponse(0, __LINE__, 'Registration number cannot be less than 10 characters!'); }
if(strlen($accountNumber) > 10 ){ sendResponse(0, __LINE__, 'Registration number cannot be longer than 10 characters!'); }
if(intval($accountNumber) < 1000000000){ sendResponse(0, __LINE__, 'Please enter a registration number!'); }
if(intval($accountNumber) > 9999999999){ sendResponse(0, __LINE__, 'Please enter a registration number!'); }
if(!ctype_digit($accountNumber)){ sendResponse(0, __LINE__, 'Registration number contains only numbers!'); }

$fullAccountNumber = intval($regNumber.$accountNumber);
var_dump($fullAccountNumber);
echo "User id: ".$userId;

try {

    //Checking if a user already has an account
    $stmt = $db->prepare( "SELECT account_id FROM accounts WHERE user_id_fk=:userId" );
    $stmt->bindValue(':userId', $userId );
    $stmt->execute();
    $aCheckAccountRows = $stmt->fetchAll();

    if( count($aCheckAccountRows) > 0 ){
        echo 'Sorry, the bank account already exists!';
        exit;
    }

    //Saving the account
    $stmt = $db->prepare( 'INSERT INTO accounts VALUES(null,:accountNumber, :userId, 10000.00)' );
    $stmt->bindValue(':accountNumber', $fullAccountNumber );
    $stmt->bindValue(':userId', $userId );
    $stmt->execute();

    //Using rowcount() when INSERTing, UPDATEing or DELETEing
    if( $stmt->rowCount() == 0 ){
        echo 'Sorry, the account was not saved!';
        exit;
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

header("refresh:3;url=../index.php");
sendResponse(1, __LINE__, "Account successfully saved!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}