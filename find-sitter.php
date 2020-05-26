<?php

require_once 'header.php';
require_once __DIR__.'/db-connect.php';

//ini_set('display_errors', 0);
//session_start();

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}

$userId = $_SESSION['userId'];

$typeSitter = 3;

try{
    //Getting users details
    $stmt = $db->prepare('SELECT *
                                   FROM users
                                   WHERE users.user_type_id_fk = :typeSitter');

    $stmt->bindValue(':typeSitter', $typeSitter);

    $stmt->execute();

    $aRows = $stmt->fetchAll();

    if(count($aRows) == 0){
        echo 'Sorry no users found!';
    }

} catch (PDOException $ex) {
    echo $ex;
}
?>

<div class="form-row pt-3 pb-3">

         <?php
         foreach ($aRows as $aRow){
             echo '
                    <div class="form-group pt-4 col-md-6">
                        <div class="image">
                            <img src="images/'.$aRow->first_name.$aRow->last_name.$aRow->image.'">
                        </div>                 
                    </div>
                    <div class="form-group pt-4 col-md-6 text-left">
                       '.$aRow->first_name." ".$aRow->last_name.'
                       <hr>
                       '.$aRow->info.'     
                       <div class="form-group pt-4 col-md-12 text-center">
                         <a href="send-message?id='.$aRow->user_id.'" class="btn btn-primary">Message</a>
                         <a href="book-sitter?id='.$aRow->user_id.'" class="btn btn-success">Book</a>
                       </div>
                     </div>
                    <hr>
                ';
         }
         ?>
</div>

<?php

require_once 'footer.php';

?>

