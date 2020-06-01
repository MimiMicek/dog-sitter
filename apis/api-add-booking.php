<?php

require_once __DIR__.'/../db-connect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$aTypeIds = [1, 2, 3];

$ownerUserId = $_SESSION['userId'];
if(empty($ownerUserId)){ sendResponse(0, __LINE__, "Owner userId is missing!"); }

$sitterUserId = $_POST['sitterUserId'] ?? '';
if(empty($sitterUserId)){ sendResponse(0, __LINE__, "Sitter userId is missing!"); }

$bookingTypeId = (int)($_POST['typeOption']) ?? '';
if(empty($bookingTypeId)){ sendResponse(0, __LINE__, "Please enter booking type!"); }
if(!in_array($bookingTypeId, $aTypeIds)){ sendResponse(0, __LINE__, "Please a booking type number => 1, 2 or 3!"); }

$startDate = $_POST['startDate'] ?? '';
if(empty($startDate)){ sendResponse(0, __LINE__, "Start date is missing!"); }

$endDate = $_POST['endDate'] ?? '';
if(empty($endDate)){ sendResponse(0, __LINE__, "End date is missing!"); }

try {

    $stmt = $db->prepare('SELECT *
                                   FROM bookings AS b
                                   WHERE b.start_date = :startDate
                                   OR b.end_date = :endDate');

    $stmt->bindValue(':startDate', $startDate );
    $stmt->bindValue(':endDate', $endDate );

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if(count($aRows) > 0){
        echo 'Sorry this dates are already occupied. Please try again!';
        exit;
        header("refresh:5;url=../find-sitter");
    }

    //Saving the booking
    $stmt = $db->prepare('INSERT INTO bookings VALUES( null, :ownerUserId, :sitterUserId, :startDate, :endDate, :bookingTypeId)');
    $stmt->bindValue(':ownerUserId', $ownerUserId );
    $stmt->bindValue(':sitterUserId', $sitterUserId );
    $stmt->bindValue(':bookingTypeId', $bookingTypeId );
    $stmt->bindValue(':startDate', $startDate );
    $stmt->bindValue(':endDate', $endDate );
    $stmt->execute();

    var_dump($stmt->rowCount());

    //Using rowcount() when INSERTing, UPDATEing or DELETEing
    if( $stmt->rowCount() == 0 ){
        echo 'Sorry, the booking was not saved!';
        exit;
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

header("refresh:5;url=../my-profile");
sendResponse(1, __LINE__, "Your booking is saved!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}