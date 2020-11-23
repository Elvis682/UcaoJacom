<?php

//index.php

include('database_connection.php');
session_start();
$vil="porto-novo";
                            date_default_timezone_set('Africa/'.$vil);
                            setlocale(LC_ALL,[ 'fr','fra','fr_FR']);    
              $d=strftime("%A %d %B  %Y "); 
              $h=date("d/m/Y H:i:s");



 /*action
    $reqact="insert into `deconnect`(`concer`, `datedeco`, `heurdeco`)  values ('$fonct','$d','$h') ";
    $resultact=mysqli_query($con,$reqact);*/

    
    $fonct=$_SESSION['fonctionus'];

    $reqrec= $connection-> prepare ("INSERT INTO  deconnect (concer, datedeco, heurdeco) VALUES (?,?,?)");
              $reqrec->execute(array($fonct,$d,$h));

    
          $booleen='N';
              $queryupdate = "
   UPDATE  `user` SET `status`=? WHERE idus=?
  ";

  $statementupdate = $connection->prepare($queryupdate);

  $statementupdate->execute(array($booleen,$_SESSION['idus']));



  $_SESSION = array();
  session_destroy();
  header("location:index_menu.php");
  exit;

?>
