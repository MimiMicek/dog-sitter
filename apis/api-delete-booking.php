<?php

require_once __DIR__.'/../db-connect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$userId = $_SESSION['userId'];
$bookingId = $_GET['id'];
try{

    $stmt = $db->prepare( 'DELETE FROM bookings
                                    WHERE booking_id=:bookingId
                                    AND owner_user_id_fk=:userId
                                    OR sitter_user_id_fk=:userId' );
    $stmt->bindValue(':bookingId', $bookingId );
    $stmt->bindValue(':userId', $userId );

    $stmt->execute();

    if (  $stmt->rowCount() == 0){
        echo 'The booking is not deleted';
    }

    echo "The booking has been deleted!";

}catch( PDOEXception $ex ){
    echo $ex;
}

header('Location: ../my-profile');