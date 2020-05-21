<?php

require_once __DIR__.'/../db-connect.php';

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
$timestamp = date("Y-m-d H:i:s");

/*$email = 'email@gmail.com';
$emailSubject = 'List of users with insufficient funds';*/

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

    if(  !$stmt->execute() ){
        echo 'Cannot transfer'.__LINE__;
        $db->rollBack();
        exit;
    }

    echo 'The money from Owners was successfully transferred!';
    $db->commit();

}catch( PDOEXception $ex ){
    echo $ex;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//TRANSFER for typeAdmin and typeSitter

try{
    //Check if accounts exist and if Sitter users have money in their accounts
    $stmt = $db->prepare( 'SELECT accounts.account_number, accounts.balance, users.first_name, users.last_name
                                    FROM accounts
                                    INNER JOIN users 
                                    ON users.user_id = accounts.user_id_fk
                                    WHERE users.user_type_id_fk = :typeSitter
                                    AND :amountSitter > accounts.balance' );

    $stmt->bindValue(':typeSitter', $typeSitter );
    $stmt->bindValue(':amountSitter', $amountSitter );

    $stmt->execute();

    $insufficientFundsSitter = $stmt->fetchAll();

    //var_dump($insufficientFundsSitter);

    if( count($insufficientFundsSitter) > 0 ){
        echo 'Sorry, these accounts have no money!';
        $sitterFullName = $insufficientFundsSitter[0]->first_name . ' ' . $insufficientFundsSitter[0]->last_name;
        $sitterAccountNumber = $insufficientFundsSitter[0]->account_number;
        $sitterAccountBalance = $insufficientFundsSitter[0]->balance;
        echo $sitterFullName . ' ' . $sitterAccountNumber . ' ' . $sitterAccountBalance;
        exit;
    }

    //Checking the number of Sitters that have money in their account
    $stmt = $db->prepare( 'SELECT accounts.account_id
                                    FROM accounts
                                    INNER JOIN users 
                                    ON users.user_id = accounts.user_id_fk
                                    WHERE users.user_type_id_fk = :typeSitter
                                    AND accounts.balance > :amountSitter' );

    $stmt->bindValue(':typeSitter', $typeSitter );
    $stmt->bindValue(':amountSitter', $amountSitter );

    $stmt->execute();

    $aSitterAccountId = $stmt->fetchAll();

    $numberOfValidSitters = count($aSitterAccountId);

    $validSittersAmount = $numberOfValidSitters * $amountSitter;

    $db->beginTransaction();

    //Updating the balance on Sitter accounts
    $stmt = $db->prepare('UPDATE accounts
                                   INNER JOIN users 
                                   ON users.user_id = accounts.user_id_fk
                                   SET accounts.balance = accounts.balance - :amountSitter
                                   WHERE users.user_type_id_fk = :typeSitter
                                   AND accounts.balance > :amountSitter');

    $stmt->bindValue(':typeSitter', $typeSitter );
    $stmt->bindValue(':amountSitter', $amountSitter);

    if(  !$stmt->execute() ){
        echo 'Cannot update the user balance '.__LINE__;
        $db->rollBack();
        exit;
    }

    //Updating the balance on Admin accounts
    $stmt = $db->prepare('UPDATE accounts
                                   INNER JOIN users 
                                   ON users.user_id = accounts.user_id_fk
                                   SET accounts.balance = accounts.balance + :validSittersAmount
                                   WHERE users.user_type_id_fk = :typeAdmin');

    $stmt->bindValue(':typeAdmin', $typeAdmin );
    $stmt->bindValue(':validSittersAmount', $validSittersAmount );

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

    foreach ($aSitterAccountId as $key => $accountId){
        $listOfSitterAccountIds[] = $accountId->account_id;
    }

    //Inserting the values
   $query = "INSERT INTO bank_transfers VALUES ";

   foreach ($listOfSitterAccountIds as $fromAccountId){
        $query .= "('null','" . $fromAccountId . "','" . $adminsAccountId . "','" . $amountSitter . "','" . $timestamp . "'),";
   }

   $query = substr($query, 0, -1);

   $stmt = $db->prepare($query);

   if(  !$stmt->execute() ){
       echo 'Cannot transfer'.__LINE__;
       $db->rollBack();
       exit;
   }

   echo 'The money from Sitters was successfully transferred!';
   $db->commit();

}catch( PDOEXception $ex ){
    echo $ex;
}