<?php

require_once __DIR__.'/../db-connect.php';

session_start();

if(!isset($_SESSION['userId'])){
    header('Location: ../index');
}

$userId = $_SESSION['userId'];
$messageId = $_GET['id'];

try{

    $stmt = $db->prepare( "DELETE FROM messages 
                                    WHERE message_id=:messageId 
                                    AND my_user_id_fk=:userId
                                    OR contact_user_id_fk=:userId" );
    $stmt->bindValue(':messageId', $messageId );
    $stmt->bindValue(':userId', $userId );

    $stmt->execute();

    if (  $stmt->rowCount() == 0){
        echo 'The message is not deleted';
    }

    echo "The message has been deleted!";

}catch( PDOEXception $ex ){
    echo $ex;
}

header('Location: ../my-messages');