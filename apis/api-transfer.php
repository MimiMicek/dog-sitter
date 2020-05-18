<?php

require_once __DIR__.'/../db-connect.php';

//ini_set('display_errors', 0);

/*ini_set("SMTP","ssl://smtp.gmail.com");
ini_set("smtp_port","465");

$headers = "From: Dog sitter app";
$headers .= "Reply-To: email@gmail.com\n";
$headers .= "Return-Path: email@gmail.com\n";
$headers .= "X-Mailer: PHP 5\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-Type: text/html; charset=iso-8859-1;\n";
$headers .= "X-Priority: 3 \n";*/

$typeAdmin = 1;
$typeOwner = 2;
$typeSitter = 3;
$amountOwner = 100;
$amountSitter = 50;

$email = 'email@gmail.com';
$emailSubject = 'List of users with insufficient funds';

try{
    //Check if accounts exist and if users have money in their accounts
    $stmt = $db->prepare( 'SELECT accounts.account_number, accounts.balance, users.first_name, users.last_name
                                    FROM accounts
                                    INNER JOIN users 
                                    ON users.user_id = accounts.user_id_fk
                                    WHERE users.user_type_id_fk = :typeOwner
                                    AND :amountOwner > accounts.balance' );

    $stmt->bindValue(':typeOwner', $typeOwner );
    $stmt->bindValue(':amountOwner', $amountOwner );

    $stmt->execute();

    $insufficientFundsOwner = $stmt->fetchAll();

    //var_dump($insufficientFundsOwner);

    if( count($insufficientFundsOwner) > 0 ){
        echo 'Sorry, these accounts have no money!';
        $ownerFullName = $insufficientFundsOwner[0]->first_name . ' ' . $insufficientFundsOwner[0]->last_name;
        $ownerAccountNumber = $insufficientFundsOwner[0]->account_number;
        $ownerAccountBalance = $insufficientFundsOwner[0]->balance;
        echo $ownerFullName . ' ' . $ownerAccountNumber . ' ' . $ownerAccountBalance;
        exit;
    }

    //TODO MAYBE test when I upload the app to the server
   /*$emailContents = 'Users full name: ' . $ownerFullName . ' Account number: ' . $ownerAccountNumber . ' Balance: ' . $ownerAccountBalance;
    mail($email, $emailSubject, $emailContents);*/

    //Checking the number of users that have money in their account
    $stmt = $db->prepare( 'SELECT accounts.account_id
                                    FROM accounts
                                    INNER JOIN users 
                                    ON users.user_id = accounts.user_id_fk
                                    WHERE users.user_type_id_fk = :typeOwner
                                    AND accounts.balance > :amountOwner' );

    $stmt->bindValue(':typeOwner', $typeOwner );
    $stmt->bindValue(':amountOwner', $amountOwner );

    $stmt->execute();

    $aOwnerAccountId = $stmt->fetchAll();

    $numberOfValidOwners = count($aOwnerAccountId);

    $validOwnersAmount = $numberOfValidOwners * $amountOwner;

    $db->beginTransaction();

    //Updating the balance on Owner accounts
    $stmt = $db->prepare('UPDATE accounts
                                   INNER JOIN users 
                                   ON users.user_id = accounts.user_id_fk
                                   SET accounts.balance = accounts.balance - :amountOwner
                                   WHERE users.user_type_id_fk = :typeOwner
                                   AND accounts.balance > :amountOwner');

    $stmt->bindValue(':typeOwner', $typeOwner );
    $stmt->bindValue(':amountOwner', $amountOwner );

    if(  !$stmt->execute() ){
        echo 'Cannot update the user balance '.__LINE__;
        $db->rollBack();
        exit;
    }

    //Updating the balance on Admin accounts
    $stmt = $db->prepare('UPDATE accounts
                                   INNER JOIN users 
                                   ON users.user_id = accounts.user_id_fk
                                   SET accounts.balance = accounts.balance + :validOwnersAmount
                                   WHERE users.user_type_id_fk = :typeAdmin');

    $stmt->bindValue(':typeAdmin', $typeAdmin );
    $stmt->bindValue(':validOwnersAmount', $validOwnersAmount );

    if(  !$stmt->execute() ){
        echo 'Cannot update the Admin balance '.__LINE__;
        $db->rollBack();
        exit;
    }

    //Selecting Admin accountId
    $stmt = $db->prepare('SELECT accounts.account_id
                                   FROM accounts 
                                   INNER JOIN users 
                                   ON users.user_id = accounts.user_id_fk 
                                   WHERE users.user_type_id_fk = :typeAdmin');

    $stmt->bindValue(':typeAdmin', $typeAdmin );

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    $adminsAccountId = $aRows[0]->account_id;

    $timestamp = $_SERVER['REQUEST_TIME'];

    foreach ($aOwnerAccountId as $key => $accountId){
        $listOfOwnerAccountIds[] = $accountId->account_id;
    }

    //Inserting the values
    $query = "INSERT INTO bank_transfers VALUES ";

    foreach ($listOfOwnerAccountIds as $fromAccountId){
        $query .= "('null','" . $fromAccountId . "','" . $adminsAccountId . "','" . $amountOwner . "','" . $timestamp . "'),";
    }

    $query = substr($query, 0, -1);

    $stmt = $db->prepare($query);

    $stmt->execute();

    var_dump($stmt);

    if(  !$stmt->execute() ){
        echo 'Cannot transfer'.__LINE__;
        $db->rollBack();
        exit;
    }

     echo 'The money was successfully transferred!';
     $db->commit();

    //TODO do a TRANSFER for typeAdmin and typeSitter

}catch( PDOEXception $ex ){
    echo $ex;
}