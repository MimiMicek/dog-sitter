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
    $stmt = $db->prepare('SELECT u.first_name, u.last_name, u.address, postal_codes.code, u.email, u.password, u.phone_no, u.image, u.info
                                    FROM users as u
                                    INNER JOIN postal_codes 
                                    ON postal_codes.postal_code_id = u.postal_code_id_fk 
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

//TODO SHOW BOOKINGS => MAKE A NEW SECTION AND SHOW BOOKING TYPE, DATES AND NAME OF THE OWNE WHO BOOKED IT

?>

<form id="profile" action="apis/api-update-profile.php" method="POST" enctype="multipart/form-data">
    <h3 class="pt-4">Profile information</h3>
    <div class="form-row pt-3 pb-3">
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
                <input name="email"
                       class="form-profile"
                       type="email"
                       required
                       id="email"
                       value="<?php
                    foreach ($aRows as $aRow){
                    echo $aRow->email;
                    }
                ?>">
            </p>
            <p>
                <label for="password">Password (if changed): </label>
                <input name="password"
                       class="form-profile"
                       type="password"
                       id="password"
                       placeholder="Leave empty otherwise">
            </p>
        </div>
        <div class="form-group col-md-6">
            <p>
                <label for="phone">Phone number: </label>
                <input name="phone"
                       class="form-profile"
                       type="number"
                       required
                       id="phone"
                       min="10000000"
                       max="99999999"
                       value="<?php
                       foreach ($aRows as $aRow){
                           echo $aRow->phone_no;
                       }
                       ?>">
            </p>
            <p>
                <label for="phone">Address: </label>
                <input name="address"
                       class="form-profile"
                       type="text"
                       required
                       id="phone"
                       minlength="4"
                       maxlength="50"
                       value="<?php
                       foreach ($aRows as $aRow){
                           echo $aRow->address;
                       }
                       ?>">
            </p>
            <p>
                <label for="phone">Postal code: </label>
                <input name="postalCode"
                       class="form-profile"
                       type="text"
                       required
                       id="phone"
                       minlength="4"
                       maxlength="4"
                       value="<?php
                       foreach ($aRows as $aRow){
                           echo $aRow->code;
                       }
                       ?>">
            </p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <button class="btn btn-warning" type="submit" name="updateUser">Update user</button>
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
                <textarea name="info"
                       class="form-profile info"
                       type="text"
                       required
                       id="info"
                       minlength="10"
                       maxlength="1500">
                    <?php
                        foreach ($aRows as $aRow){
                            echo $aRow->info;
                        }
                    ?>
                </textarea>
            </p>
        </div>
    </div>
</form>
<div class="form-row">
    <div class="form-group col-md-12">
        <a href="apis/api-delete-profile.php" class="btn btn-danger">Delete user</a>
    </div>
</div>
<?php

require_once 'footer.php';

?>

