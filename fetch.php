<?php
session_start();
include('database_connection.php');


$valid='';
if (isset($_POST['submit']))
   {
          if (!empty($_POST['mailu']) or !empty($_POST['passu']))
          {
          @$em=htmlentities(trim($_POST['mailu']));
          @$mp=htmlentities(trim(sha1($_POST['passu'])));
          /*================ requet 1 =======================*/
           /*================ $reqcon = $connection-> prepare ('SELECT * FROM user WHERE mailus = ? AND motpasus = ?');==========================*/
          /*================requet administrateur ensei ===========================*/
          if ($reqcon = $connection-> prepare ('SELECT * FROM user WHERE mailus = ? AND motpasus = ?')) {
           $reqcon -> execute (array($em,$mp));
            $cnt = $reqcon->rowcount();



            if ($cnt==1) {
              $doncon = $reqcon-> fetch();
              

              $f= $doncon['fonctionus'];
              
             

            if ($f=="Secrétaire Principal")
            {
                $_SESSION['sp1']='sp1';
                $_SESSION['autoriser']="sp";
                 $_SESSION['idus']= $doncon['idus'];
                $_SESSION['nomus']= $doncon['nomus'];
                $_SESSION['prenomus']= $doncon['prenomus'];
                $_SESSION['fonctionus']= $doncon['fonctionus'];
                $_SESSION['mailus']= $doncon['mailus'];
                header("location:sp/renseigner/annee_academique/index_annee_acad.php?idus=");
                 //echo  '<script>window.location.replace("sp/renseigner/annee_academique/index_annee_acad.php");</script>';
                                
              $valid='ok';
            }/*================fin  if fonction sp  ==========================*/ 
            
            }/*================fin count de sp  ==========================*/ 
            
            }/*================fin  if de requette sp da   ==========================*/ 
          }/*================end if mail et mot de passe vide  ==========================*/ 
         
        }//submit  


echo $valid;

?>
