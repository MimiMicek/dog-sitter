<?php

require_once 'header.php';
require_once __DIR__ . '/db-connect.php';

//ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$myUserId = $_SESSION['userId'];

try {
    //Getting received messages
    $stmt = $db->prepare('SELECT m.message_id, m.my_user_id_fk, m.contact_user_id_fk, m.message, m.timestamp,
                                          u.user_id, u.first_name, u.last_name
                                   FROM messages AS m
                                   INNER JOIN users AS u
                                   ON u.user_id = m.my_user_id_fk
                                   WHERE m.contact_user_id_fk = :myUserId
                                   ORDER BY m.timestamp DESC ');

    $stmt->bindValue(':myUserId', $myUserId);

    $stmt->execute();

    $aReceivedMessages = $stmt->fetchAll();

    //var_dump($aReceivedMessages);

    if (count($aReceivedMessages) == 0) {
        echo 'Sorry no received messages found!';
    }

    //Getting sent messages
    $stmt = $db->prepare('SELECT m.message_id, m.my_user_id_fk, m.contact_user_id_fk, m.message, m.timestamp,
                                          u.user_id, u.first_name, u.last_name
                                   FROM messages AS m
                                   INNER JOIN users AS u
                                   ON u.user_id = m.contact_user_id_fk
                                   WHERE m.my_user_id_fk = :myUserId
                                   ORDER BY m.timestamp DESC');

    $stmt->bindValue(':myUserId', $myUserId);

    $stmt->execute();

    $aSentMessages = $stmt->fetchAll();

    //var_dump($aSentMessages);

    if (count($aSentMessages) == 0) {
        echo 'Sorry no sent messages found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}

?>

<div class="form-row pt-5 pb-3">
    <div class="form-row nav-masthead">
        <a id="inbox" href="#" class="nav-link">Inbox</a>
        <a id="sent" href="#" class="nav-link">Sent</a>
    </div>
    <div id="receivedMessages">
        <?php /*If Inbox clicked show received, if Sent link clicked show sent messages*/
        foreach ($aReceivedMessages as $receivedMessage){
            echo '
                        <div class="form-group pt-4 col-md-12 text-left">
                           <h6>From: '.$receivedMessage->first_name." ".$receivedMessage->last_name.' </h6>
                           '.$receivedMessage->timestamp.'
                           <hr>
                           '.$receivedMessage->message.'     
                           <div class="form-group pt-4 col-md-12 text-right">
                             <a href="send-message?id='.$receivedMessage->user_id.'" class="btn btn-info">Reply</a>
                             <a href="apis/delete-message?id='.$receivedMessage->message_id.'" class="btn btn-danger">Delete</a>
                           </div>
                           <div class="message-bottom pt-3"></div>
                         </div>
                    ';
        }
        ?>
    </div>
    <div id="sentMessages">
        <?php /*If Inbox clicked show received, if Sent link clicked show sent messages*/
        foreach ($aSentMessages as $sentMessage){
            echo '
                        <div class="form-group pt-4 col-md-12 text-left">
                           <h6>To: '.$sentMessage->first_name." ".$sentMessage->last_name.' </h6>
                           '.$sentMessage->timestamp.'
                           <hr>
                           '.$sentMessage->message.'     
                           <div class="form-group pt-4 col-md-12 text-right">
                             <a href="apis/delete-message?id='.$sentMessage->message_id.'" class="btn btn-danger">Delete</a>
                           </div>
                           <div class="message-bottom pt-3"></div>
                         </div>
                    ';
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#receivedMessages").show();
        $("#sentMessages").hide();

        $("#sent").click(function(){
            $("#receivedMessages").hide();
            $("#sentMessages").show();
        });

        $("#inbox").click(function(){
            $("#receivedMessages").show();
            $("#sentMessages").hide();
        });
    });
</script>