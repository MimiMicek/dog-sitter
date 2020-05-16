<?php

require_once __DIR__.'/../db-connect.php';

//ini_set('display_errors', 0);

$typeAdmin = 1;
$typeOwner = 2;
$typeSitter = 3;

try{
    $stmt = $db->prepare( "SELECT accounts.account_number, accounts.balance
                                    FROM accounts
                                    INNER JOIN users 
                                    ON users.user_id = accounts.user_id_fk
                                    WHERE users.user_type_id_fk = :typeOwner" );

/*SELECT * FROM accounts WHERE user_id_fk=:toAccount OR (account_number=:fromAccount AND user_id=:userId AND balance > :amount*/

    $stmt->bindValue(':typeOwner', $typeOwner );

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    var_dump($aRows);

    //do a SELECT statement for typeAdmin and typeSitter

   /* if( count($aRows) < 1 ){
        echo 'Sorry, no accounts found!'.__LINE__;
        exit;
    }*/


   /* $db->beginTransaction();
    $stmt = $db->prepare('UPDATE accounts SET balance = balance - :amount WHERE account_number = :fromAccount AND balance > :amount ');

    $stmt->bindValue(':fromAccount', $fromAccount );
    $stmt->bindValue(':amount', $amount );

    if(  !$stmt->execute() ){ // only works because the line PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, in the connect.php
        // has been commented out
        echo 'Cannot update the user '.__LINE__;
        $db->rollBack();
        exit;
    }

    $stmt = $db->prepare('UPDATE accounts SET balance = balance + :amount WHERE account_number = :toAccount ');

    $stmt->bindValue(':toAccount', $toAccount );
    $stmt->bindValue(':amount', $amount );

    if(  !$stmt->execute() ){ // only works because the line PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, in the connect.php
        // has been commented out
        echo 'Cannot update the balance '.__LINE__;
        $db->rollBack();
        exit;
    }



    $stmt = $db->prepare('INSERT INTO transfers VALUES (null, :fromAccount, :toAccount, :amount, :text, null)');

    $stmt->bindValue(':fromAccount', $fromAccount );
    $stmt->bindValue(':toAccount', $toAccount );
    $stmt->bindValue(':amount', $amount );


    if(  !$stmt->execute() ){ // only works because the line PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, in the connect.php has been commented out
        echo 'Cannot transfer'.__LINE__;
        $db->rollBack();
        exit;
    }


    echo 'The money was successfully transfered!';
    $db->commit();*/


}catch( PDOEXception $ex ){
    echo $ex;
}