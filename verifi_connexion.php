<?php
session_start();
$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost;dbname=gestdutemps', $username, $password );




if (isset($_POST['operation'])){
					if (!empty($_POST['mailu']) or !empty($_POST['passu']))
					{
					@$em=htmlentities(trim($_POST['mailu']));
					@$mp=htmlentities(trim(sha1($_POST['passu'])));
						$reqcon = $connection-> prepare ('SELECT * FROM user WHERE mailus = ? AND motpasus = ?');
						$reqcon -> execute (array($em,$mp));
						$cnt = $reqcon->rowcount();
						if ($cnt==1) {
							$doncon = $reqcon-> fetch();
							$_SESSION['idus']= $doncon['idus'];
							$_SESSION['nomus']= $doncon['nomus'];
							$_SESSION['prenomus']= $doncon['prenomus'];
							$_SESSION['fonctionus']= $doncon['fonctionus'];
							$_SESSION['mailus']= $doncon['mailus'];

							$f= $doncon['fonctionus'];
							
							$vil="porto-novo";
                            date_default_timezone_set('Africa/'.$vil);
                            setlocale(LC_ALL,[ 'fr','fra','fr_FR']);    
							$d=strftime("%A %d %B  %Y "); 
							$h=date("H:i");
							$reqrec= $connection-> prepare ('INSERT INTO historique (concerne, datecon, heurcon) VALUES (?,?,?)');
							$reqrec->execute(array($f, $d, $h));

							if ($f=="Secrétaire Principal"){
								$_SESSION['sp1']='sp1';
								$_SESSION['autoriser']="sp";
                                 echo  '<script>window.location.replace("mon_doc.php");</script>';
                                 //window.location.replace("DA/filiere/index_filiere.php?idus=".$_SESSION['idus']);
							header("location:DA/filiere/index_filiere.php?idus=".$_SESSION['idus']);
							$reqd=$connection->exec('DELETE FROM `essai`'  );
						}//fin sp if 
						elseif ($f=="Directeur Adjoint"){
								$_SESSION['da1']='da1';
								$_SESSION['autoriser']="da";
								header("location:DA/consultemploda.php?idus=".$_SESSION['idus']);
								$reqd=$connection->exec('DELETE FROM `essai`'  );
							
							
						}//fin da if

							elseif ($f=="Directeur"){
								$_SESSION['dg1']='dg1';
								$_SESSION['autoriser']="dg";
								header("location:DG/consultemplodg.php?idus=".$_SESSION['idus']);
								$reqd=$connection->exec('DELETE FROM `essai`'  );
							
						}//fin d if

							elseif ($f=="Préfet"){
								$_SESSION['pd1']='pd1';
								$_SESSION['autoriser']="pd";
								header("location:prefet/emploprefet.php?idus=".$_SESSION['idus']);
								$reqd=$connection->exec('DELETE FROM `essai`'  );
							
						}//fin prefet if
							else {
								$_SESSION['autoriser']="respo";
								header("location:cahier.php?idus=".$_SESSION['idus']);
								$reqd=$connection->exec('DELETE FROM `essai`'  );
								}

						}
						else{
						
						$req=$connection->prepare('INSERT INTO `essai`(`nbre`) VALUES (?)'  );
	                    $req->execute(array($em));
	                    $reqn=$connection->query('SELECT * FROM `essai` ');
	                    $nbre=$reqn->rowcount();
	//echo $nbre;
	if ($nbre==1) {
		echo  '<script> alert("	Email ou mot de passe inccorect. Il vous reste deux (02) essais."); </script>';
	}
	if ($nbre==2) {
		echo  '<script> alert("	Email ou mot de passe inccorect. Il vous reste un (01) essai."); </script>';
	}
	if ($nbre==3) {
		
		$reqd=$connection->exec('DELETE FROM `essai`'  );
		header("location: menu.php");
		exit;
		
	}
}}
					}//fin if count
					
					
					

?>