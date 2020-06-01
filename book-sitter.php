<?php
header('Access-Control-Allow-Origin: http://localhost');
require_once 'header.php';
require_once __DIR__ . '/db-connect.php';

ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$ownerUserId = $_SESSION['userId'];
$sitterUserId = $_GET['id'];

$dateToday = date('Y-m-d');

try{
    //Getting all bookings for each sitter
    $stmt = $db->prepare('SELECT b.sitter_user_id_fk, b.start_date, b.end_date
                                       FROM bookings AS b
                                       INNER JOIN users AS u ON u.user_id = b.sitter_user_id_fk
                                       WHERE b.sitter_user_id_fk = :sitterUserId');

    $stmt->bindValue(':sitterUserId', $sitterUserId);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if(count($aRows) == 0){
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}
?>

<div class="pt-3">
    <div class="form-row pt-3 pb-3">
        <div class="form-group text-left col-md-12">
            <h6>This sitter already the upcoming bookings</h6>
            <?php
                foreach ($aRows as $aRow){
                    if($aRow->start_date >= $dateToday) {
                        echo '
                           <div class="form-group pt-2 col-md-12 text-left">
                                Start date: ' . $aRow->start_date . '<br>
                                End date: ' . $aRow->end_date . '
                           </div>
                          ';
                    }else{
                        echo 'This user has no upcoming bookings!';
                        exit;
                    }
                }
            ?>
        </div>
    </div>
    <form id="login" action="apis/api-add-booking.php" method="post">
       <div class="form-row">
            <div class="form-group text-left col-md-6">
                <h6 class="text-left">Book this sitter</h6>
                <label for="typeOption">
                   Please enter a number for option type:<br>
                    1. Walk (1 day)<br>
                    2. Overnight stay (2 days) or<br>
                    3. Vacation (3 or more)
                </label>
                <input type="text"
                       name="typeOption"
                       class="form-control"
                       id="typeOption"
                       placeholder="1"
                       required>
            </div></div>
        <div class="form-row">
                <div class="form-group col-md-6">
                <label for="startDate">Start date</label>
                <input type="text" name="startDate" class="form-control" id="startDate" placeholder="2020-06-05" required>
            </div>
            <div class="form-group col-md-6">
                <label for="endDate">End date</label>
                <input type="text" name="endDate" class="form-control" id="endDate" placeholder="2020-06-06" required>
            </div>
              <input name="sitterUserId"
                   value="<?php echo $sitterUserId;?>"
                   type="hidden">
        </div>
        <div class="form-group">
            <button class="btn btn-secondary">Book sitter</button classform-group>
        </div>
    </form>
</div>

<?php

require_once 'footer.php';

?>
