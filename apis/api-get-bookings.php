<?php

require_once __DIR__.'/../db-connect.php';
session_start();

/*if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$ownerUserId = $_SESSION['userId'];

$sitterUserId = $_REQUEST['id'];
var_dump($sitterUserId);

try {

    $stmt = $db->prepare('SELECT b.booking_id, b.owner_user_id_fk, b.sitter_user_id_fk, b.start_date, b.end_date,
                                          bt.type_name
                                   FROM bookings AS b
                                   INNER JOIN booking_types AS bt
                                   ON bt.booking_type_id = b.booking_type_id_fk');

    //$stmt->bindValue(':sitterUserId', $sitterUserId);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    foreach($aRows as $row)
    {
        $data[] = array(
            'title'   => $row->type_name,
            'start'   => $row->start_date,
            'end'   => $row->end_date
        );
    }

    var_dump($aBookings);
    echo json_encode($data[0]);

   if(count($aRows) > 0){
        echo 'Sorry this dates are already occupied. Please try again!';
        exit;
    }

}catch( PDOEXception $ex ){
    echo $ex;
}

//header("refresh:5;url=../my-profile");
//sendResponse(1, __LINE__, "Your booking is saved!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}*/