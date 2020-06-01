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

?>
<form id="login" action="apis/api-add-booking.php" method="post">
    <div class="form-row">
        <div class="form-group text-left col-md-6">
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
                   required>
        </div></div>
    <div class="form-row">
            <div class="form-group col-md-6">
            <label for="startDate">Start date</label>
            <input type="text" name="startDate" class="form-control" id="startDate" required>
        </div>
        <div class="form-group col-md-6">
            <label for="endDate">End date</label>
            <input type="text" name="endDate" class="form-control" id="endDate" required>
        </div>
          <input name="sitterUserId"
               value="<?php echo $sitterUserId;?>"
               type="hidden">
    </div>
    <div class="form-group">
        <button class="btn btn-secondary">Book sitter</button classform-group>
    </div>
</form>




<?php

require_once 'footer.php';

?>
