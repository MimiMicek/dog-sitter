<?php

require_once 'header.php';
require_once __DIR__ . '/db-connect.php';

ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$myUserId = $_SESSION['userId'];
$contactUserId = $_GET['id'];

try {
    //Getting users details
    $stmt = $db->prepare('SELECT u.first_name, u.last_name
                                   FROM users AS u
                                   WHERE u.user_id = :contactUserId');

    $stmt->bindValue(':contactUserId', $contactUserId);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if (count($aRows) == 0) {
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}

?>

<form id="send-message" action="apis/api-send-message" method="POST">
    <div class="form-row">
        <div class="form-group text-left col-md-12">
            <h6>To:
                <?php
                    foreach ($aRows as $aRow){
                        echo $aRow->first_name.' '.$aRow->last_name;
                    }
                ?>
            </h6>
            <input name="contactUserId"
                   value="<?php echo $contactUserId;?>"
                   type="hidden">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group text-left col-md-12">
            <h6>Message:</h6>
            <textarea name="message"
                      class="form-profile message"
                      type="text"
                      id="message"
                      required
                      minlength="10"
                      maxlength="2500"
                      placeholder="Max 2500 characters..."></textarea>
        </div>
    </div>
    <button class="btn btn-primary">Send</button>
</form>
<?php

require_once 'footer.php';

?>
