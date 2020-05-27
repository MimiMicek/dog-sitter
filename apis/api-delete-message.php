<?php

require_once __DIR__.'/../db-connect.php';

session_start();

if(!isset($_SESSION['userId'])){
    header('Location: ../index.php');
}

$userId = $_SESSION['userId'];

/*try{

    $stmt = $db->prepare( "DELETE FROM messages WHERE message_id=:message_id_from_url" );
    $stmt->bindValue(':userId', $userId );
    $stmt->execute();

    if (  $stmt->rowCount() == 0){
        echo 'The user is not deleted';
    }

    echo "The user has been successfully deleted!";

}catch( PDOEXception $ex ){
    echo $ex;
}

header('Location: ../index.php');*/