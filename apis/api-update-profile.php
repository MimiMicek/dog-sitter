<?php

require_once __DIR__.'/../db-connect.php';

session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}

$userId = $_SESSION['userId'];

$email = $_POST['email'] ?? '';
if(empty($email)){ sendResponse(0, __LINE__, 'Please enter email!'); }
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    sendResponse(0, __LINE__, 'Please enter a valid email!');
}

$password = $_POST['password'] ?? '';
if(!empty($password)){
    if(strlen($password) < 4){ sendResponse(0, __LINE__, 'Passwords cannot be less than 4 characters!'); }
    if(strlen($password) > 30){ sendResponse(0, __LINE__, 'Passwords cannot be longer than 30 characters!'); }
}

$address = $_POST['address'] ?? '';
if(empty($address)){ sendResponse(0, __LINE__, 'Please enter address!'); }
if(strlen($address) < 4 ){ sendResponse(0, __LINE__, 'Address cannot be less than 4 characters!'); }
if(strlen($address) > 50 ){ sendResponse(0, __LINE__, 'Address cannot be longer than 50 characters!'); }

$postalCode = $_POST['postalCode'] ?? '';
if(empty($postalCode)){ sendResponse(0, __LINE__, 'Please enter postal code!'); }
if(strlen($postalCode) < 4 ){ sendResponse(0, __LINE__, 'Postal code cannot be less than 4 characters!'); }
if(strlen($postalCode) > 4 ){ sendResponse(0, __LINE__, 'Postal code cannot be longer than 4 characters!'); }
if(intval($postalCode) < 1000){ sendResponse(0, __LINE__, 'Please enter a postal code!'); }
if(intval($postalCode) > 9999){ sendResponse(0, __LINE__, 'Please enter a postal code!'); }
if(!ctype_digit($postalCode)){ sendResponse(0, __LINE__, 'Postal code contains only numbers!');  }

$phone = $_POST['phone'] ?? '';
if(empty($phone)){ sendResponse(0, __LINE__, 'Please enter phone number!'); }
if(strlen($phone) != 8){ sendResponse(0, __LINE__, 'Phone number has to be 8 characters!'); }
if(intval($phone) < 10000000){ sendResponse(0, __LINE__, 'Please enter a valid phone number!'); }
if(intval($phone) > 99999999){ sendResponse(0, __LINE__, 'Please enter a valid phone number!'); }
if(!ctype_digit($phone)){ sendResponse(0, __LINE__, 'Phone number contains only numbers!');  }

$info = $_POST['info'] ?? '';
if(empty($info)){ sendResponse(0, __LINE__, "Please enter information!"); }
if(strlen($info) < 10){ sendResponse(0, __LINE__, "Information cannot be less than 10 characters!"); }
if(strlen($info) > 1500){ sendResponse(0, __LINE__, "Information cannot be longer than 1500 characters!"); }

try {

    //select the id from the cities table
    $stmt = $db->prepare('SELECT postal_codes.postal_code_id 
                                    FROM postal_codes 
                                    INNER JOIN cities 
                                    ON cities.city_id = postal_codes.city_id_fk
                                    WHERE postal_codes.code = :postalCode 
                                    /*AND cities.name = :city*/');

    //$stmt->bindValue(':city', $city);
    $stmt->bindValue(':postalCode', $postalCode);
    $stmt->execute();

    $aPostalCodes = $stmt->fetch();

    if ($aPostalCodes === false) {
        echo 'Sorry, you already have this type of account!';
        exit;
    }

    $stmt = $db->prepare( "SELECT password FROM users WHERE user_id=:userId" );
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    $aRows = $stmt->fetchAll();

    //If the field is left empty then the pass is not updated
    if (empty($password)) {

        $stmt = $db->prepare('UPDATE users 
                                       SET email = :email,
                                           address = :address,
                                           postal_code_id_fk = :postalCode,
                                           phone_no = :phone,
                                           info = :info
                                       WHERE user_id=:userId
                        ');

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':postalCode', $aPostalCodes->postal_code_id);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':info', $info);
        $stmt->bindValue(':userId', $userId);

        $stmt->execute();

        echo "All fields but the password have been updated";

    }else{
        $stmt = $db->prepare('UPDATE users 
                                       SET email = :email,
                                           password = :password,
                                           address = :address,
                                           postal_code_id_fk = :postalCode,
                                           phone_no = :phone,
                                           info = :info
                                       WHERE user_id=:userId
                        ');

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));//hashed password
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':postalCode', $aPostalCodes->postal_code_id);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':info', $info);
        $stmt->bindValue(':userId', $userId);

        $stmt->execute();

        echo "All fields have been updated";
    }

} catch (PDOException $ex) {
    echo $ex;
}