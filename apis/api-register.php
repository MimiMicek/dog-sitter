<?php

require_once __DIR__.'/../db-connect.php';

ini_set('display_errors', 0);

$fName = $_POST['fName'] ?? '';
if(empty($fName)){ sendResponse(0, __LINE__, "Please enter first name!"); }
if(strlen($fName) < 2 ){ sendResponse(0, __LINE__, "First name cannot be less than 2 characters!"); }
if(strlen($fName) > 40 ){ sendResponse(0, __LINE__, "First name cannot be longer than 40 characters!"); }

$lName = $_POST['lName'] ?? '';
if(empty($lName)){ sendResponse(0, __LINE__, "Please enter last name!"); }
if(strlen($lName) < 2 ){ sendResponse(0, __LINE__, "Last name cannot be less than 2 characters!"); }
if(strlen($lName) > 40 ){ sendResponse(0, __LINE__, "Last name cannot be longer than 40 characters!"); }

$email = $_POST['email'] ?? '';
if(empty($email)){ sendResponse(0, __LINE__, "Please enter email!"); }
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    sendResponse(0, __LINE__, "Please enter a valid email!");
}

$password = $_POST['password'] ?? '';
if(empty($password)){ sendResponse(0, __LINE__, "Please enter password!"); }
if(strlen($password) < 4){ sendResponse(0, __LINE__, "Passwords cannot be less than 4 characters!"); }
if(strlen($password) > 30){ sendResponse(0, __LINE__, "Passwords cannot be longer than 30 characters!"); }

$address = $_POST['address'] ?? '';
if(empty($address)){ sendResponse(0, __LINE__, "Please enter address!"); }
if(strlen($address) < 4 ){ sendResponse(0, __LINE__, "Address cannot be less than 4 characters!"); }
if(strlen($address) > 50 ){ sendResponse(0, __LINE__, "Address cannot be longer than 50 characters!"); }

$postalCode = $_POST['postalCode'] ?? '';
if(empty($postalCode)){ sendResponse(0, __LINE__, "Please enter postal code!"); }
if(strlen($postalCode) < 4 ){ sendResponse(0, __LINE__, "Postal code cannot be less than 4 characters!"); }
if(strlen($postalCode) > 4 ){ sendResponse(0, __LINE__, "Postal code cannot be longer than 4 characters!"); }
if(intval($postalCode) < 1000){ sendResponse(0, __LINE__, "Please enter a postal code!"); }
if(intval($postalCode) > 9999){ sendResponse(0, __LINE__, "Please enter a postal code!"); }
if(!ctype_digit($postalCode)){ sendResponse(0, __LINE__, "Postal code contains only numbers!");  }

$city = $_POST['city'] ?? '';
if(empty($city)){ sendResponse(0, __LINE__, "Please enter city!"); }
if(strlen($city) < 2 ){ sendResponse(0, __LINE__, "City cannot be less than 2 characters!"); }
if(strlen($city) > 30 ){ sendResponse(0, __LINE__, "City cannot be longer than 30 characters!"); }

$cpr = $_POST['cpr'] ?? '';
if(empty($cpr)){ sendResponse(0, __LINE__, "Please enter Cpr number!"); }
if(strlen($cpr) != 10){ sendResponse(0, __LINE__, "Cpr number cannot be less than 10 characters!"); }
if(!ctype_digit($cpr)){ sendResponse(0, __LINE__, "Cpr number contains only numbers!"); }

$phone = $_POST['phone'] ?? '';
if(empty($phone)){ sendResponse(0, __LINE__, "Please enter phone number!"); }
if(strlen($phone) != 8){ sendResponse(0, __LINE__, "Phone number has to be 8 characters!"); }
if(intval($phone) < 10000000){ sendResponse(0, __LINE__, "Please enter a valid phone number!"); }
if(intval($phone) > 99999999){ sendResponse(0, __LINE__, "Please enter a valid phone number!"); }
if(!ctype_digit($phone)){ sendResponse(0, __LINE__, "Phone number contains only numbers!");  }

$image = $_POST['image'] ?? '';
if(empty($image)){ sendResponse(0, __LINE__, "Please upload an image!"); }

if (isset($image)){

    $uniqueName = $fName.$lName.uniqid();

    $target_file = sprintf("%s/../images/$uniqueName.jpg", __DIR__);

    $image = $_FILES["image"]["name"];
}

//TODO Check if image is too large
//TODO Check if it is the right format
//TODO Check if it is fake or real

$info = $_POST['info'] ?? '';
if(empty($info)){ sendResponse(0, __LINE__, "Please enter information!"); }
if(strlen($info) < 10){ sendResponse(0, __LINE__, "Information cannot be less than 10 characters!"); }
if(strlen($info) > 1500){ sendResponse(0, __LINE__, "Information cannot be longer than 1500 characters!"); }

$userType = $_POST['userType'] ?? '';
if(empty($userType)){ sendResponse(0, __LINE__, "Please choose a user type!"); }

//TODO check if the email is already in the database

try {
    //select the id from the cities table
    $stmt = $db->prepare('SELECT postal_codes.postal_code_id 
                                    FROM postal_codes 
                                    INNER JOIN cities 
                                    ON cities.city_id = postal_codes.city_id_fk
                                    WHERE postal_codes.code = :postalCode 
                                    AND cities.name = :city');
    $stmt->bindValue(':city', $city);
    $stmt->bindValue(':postalCode', $postalCode);
    $stmt->execute();

    $aRows = $stmt->fetch();

    var_dump($aRows);


    if( $aRows === false ){
        echo 'Sorry, you already have this type of account!';
        exit;
    }

    $stmt = $db->prepare('INSERT INTO users VALUES
                        (null,:fName, :lName, :email, :password, :address, :postalCode, :cpr, :phone, :image, :info, :userType)');


    $stmt->bindValue(':fName', $fName);
    $stmt->bindValue(':lName', $lName);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $password);//.hash(1, PASSWORD_DEFAULT) TODO Hash password
    $stmt->bindValue(':address', $address);
    $stmt->bindValue(':postalCode', $aRows->postal_code_id);
    $stmt->bindValue(':cpr', $cpr);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':image', $image);
    $stmt->bindValue(':info', $info);
    $stmt->bindValue(':userType', $userType);

    $stmt->execute();

    echo 'Success';

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
        echo "Image transferred successfully!";
    }else{
        echo "There was a problem uploading!";
    }

} catch (PDOException $ex) {
    echo $ex;
}

sendResponse(1, __LINE__, "Saved to the database!");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendResponse($bStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}