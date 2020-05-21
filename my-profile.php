<?php

require_once 'header.php';

require_once __DIR__.'/db-connect.php';

//ini_set('display_errors', 0);
session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}


$userId = $_SESSION['userId'];

try{
    //Getting users details
    $stmt = $db->prepare('SELECT u.first_name, u.last_name, u.email, u.phone_no, u.image, u.info
                                    FROM users as u 
                                    WHERE u.user_id = :userId');

    $stmt->bindValue(':userId', $userId);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if(count($aRows) == 0){
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}
?>

<form id="profile" action="apis/api-delete-profile.php" method="POST" enctype="multipart/form-data">
    <h3>Profile information</h3>
    <div class="form-row">
        <div class="form-group col-md-6">
            <p>
                <label for="fullName">Full name: </label>
                <span id="fullName">
                <?php
                    foreach ($aRows as $aRow){
                    echo $aRow->first_name.' '.$aRow->last_name ;
                    }
                ?>
                </span>
            </p>

            <p>
                <label for="email">Email: </label>
                <span id="email">
                <?php
                    foreach ($aRows as $aRow){
                    echo $aRow->email;
                    }
                ?>
                </span>
            </p>

            <p>
                <label for="phone">Phone number: </label>
                <span id="phone">
                <?php
                foreach ($aRows as $aRow){
                    echo $aRow->phone_no;
                }
                ?>
                </span>
            </p>
        </div>
        <div class="form-group col-md-6">
            <button class="btn btn-danger" type="submit" name="deleteButton">Delete user</button>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">

        <label for="image">Image: </label>
        <?php
        foreach ($aRows as $aRow){
            echo '
                    <div class="image">
                        <img src="images/'.$aRow->first_name.$aRow->last_name.$aRow->image.'">
                    </div>
                ';
        }
        ?>
        </div>
        <div class="form-group col-md-6">
            <p>
                <h6 for="info">About me</h6>
                <span id="info">
                <?php
                foreach ($aRows as $aRow){
                    echo $aRow->info;
                }
                ?>
                </span>
            </p>
        </div>
    </div>
</form>

<?php

require_once 'footer.php';

?>

