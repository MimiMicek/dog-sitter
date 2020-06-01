<?php

require_once 'header.php';

require_once __DIR__.'/db-connect.php';

//ini_set('display_errors', 0);

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}


$userId = $_SESSION['userId'];
$typeOwner = 2;
$dateToday = date('Y-m-d');

try{
    //Getting users details
    $stmt = $db->prepare('SELECT u.first_name, u.last_name, u.address, postal_codes.code, u.email, u.password, u.phone_no, u.image, u.info, u.user_type_id_fk
                                    FROM users as u
                                    INNER JOIN postal_codes 
                                    ON postal_codes.postal_code_id = u.postal_code_id_fk 
                                    WHERE u.user_id = :userId');

    $stmt->bindValue(':userId', $userId);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    $userTypeId = $aRows[0]->user_type_id_fk;

    if(count($aRows) == 0){
        echo 'Sorry no users found!';
    }

    //Getting bookings
    if ($userTypeId == $typeOwner){
        $stmt = $db->prepare('SELECT b.booking_id, b.owner_user_id_fk, b.sitter_user_id_fk, b.start_date, b.end_date,
                                              bt.type_name,
                                              u.first_name, u.last_name
                                       FROM bookings AS b
                                       INNER JOIN booking_types AS bt ON bt.booking_type_id = b.booking_type_id_fk
                                       JOIN users AS u ON u.user_id = b.sitter_user_id_fk
                                       WHERE b.owner_user_id_fk = :userId');

        $stmt->bindValue(':userId', $userId);

        $stmt->execute();

        $myBookings = $stmt->fetchAll();

        if(count($myBookings) == 0){
            echo 'Sorry no owner bookings found!';
        }
    }else{
        $stmt = $db->prepare('SELECT b.booking_id, b.owner_user_id_fk, b.sitter_user_id_fk, b.start_date, b.end_date,
                                              bt.type_name,
                                              u.first_name, u.last_name
                                       FROM bookings AS b
                                       INNER JOIN booking_types AS bt ON bt.booking_type_id = b.booking_type_id_fk
                                       JOIN users AS u ON u.user_id = b.owner_user_id_fk
                                       WHERE b.sitter_user_id_fk = :userId');

        $stmt->bindValue(':userId', $userId);

        $stmt->execute();

        $myBookings = $stmt->fetchAll();

        if(count($myBookings) == 0){
            echo 'Sorry no sitter bookings found!';
        }
    }

} catch (PDOException $ex) {
    echo $ex;
}

?>

<form id="profile" action="apis/api-update-profile.php" method="POST" enctype="multipart/form-data">
    <h3 class="pt-4">Profile information</h3>
    <div class="form-row pt-3 pb-3">
        <div class="form-group col-md-6 text-left">
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
        <div class="form-group text-left col-md-6">
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
    <hr>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="form-group col-md-12">
                <h4>My upcoming bookings</h4>
                <?php
                foreach ($myBookings as $booking){
                    if($booking->start_date >= $dateToday){
                        echo '
                        <div class="form-row pt-3">
                           <div class="form-group col-md-3">
                                  Booking type:<br>                       
                                '.$booking->type_name.'
                            </div>
                            <div class="form-group col-md-3">
                                  Start date:<br>                       
                                '.$booking->start_date.'
                            </div>
                            <div class="form-group col-md-3">
                                  End date:<br>                       
                                '.$booking->end_date.'
                            </div>
                            <div class="form-group col-md-3">
                                  Booked with:<br>                       
                                '.$booking->first_name.' '.$booking->last_name.'
                            </div>
                            <div class="form-group pt-4 col-md-12 text-right">
                                <a href="apis/api-delete-booking?id='.$booking->booking_id.'" class="btn text-right btn-outline-danger">Delete</a>
                           </div>
                        </div>
                        <hr>
                    ';
                    }

                }
                ?>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <h6>Profile image</h6>
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
                <h6>About me</h6>
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

