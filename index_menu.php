<?php
error_reporting(0);

session_start();
/*=======administrateur=======*/
if ($_SESSION['autoriser'] == 'sp') {
  header("location:sp/consulter/emploi_du_temps/index_emploi_du_temps.php");
  exit;
} 
if ($_SESSION['autoriser'] == 'pd') {
  header("location:prefet/consulter/emploi_du_temps/index_emploi_du_temps.php");
  exit;
} 
if ($_SESSION['autoriser'] == 'da') {
  header("location:da/consulter/emploi_du_temps/index_emploi_du_temps.php");
  exit;
} 
if ($_SESSION['autoriser'] == 'dg') {
  header("location:di/consulter/emploi_du_temps/index_emploi_du_temps.php");
  exit;
} 
/*=======etudiant=======*/
if ($_SESSION['respo']=='respo') {
   header("location:etudiant_respo/cahier_de_text/index_cahier.php?");
           
  exit;
} 
/*=======enseignant=======*/
if ($_SESSION['enseignant'] == 'enseignant') {
  header("location:ensignant/valid_cahier_de_text/index_val_cahier.php?");
  exit;
} 

$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost;dbname=gestdutemps', $username, $password );




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
                $_SESSION['idus']= $doncon['idus'];
                $_SESSION['nomus']= $doncon['nomus'];
                $_SESSION['prenomus']= $doncon['prenomus'];
                $_SESSION['fonctionus']= $doncon['fonctionus'];
                $_SESSION['mailus']= $doncon['mailus'];
                $_SESSION['imagus']= $doncon['imagus'];
               
              $f= $doncon['fonctionus'];
               $vil="porto-novo";
                            date_default_timezone_set('Africa/'.$vil);
                            setlocale(LC_ALL,[ 'fr','fra','fr_FR']);    
              $d=strftime("%A %d %B  %Y "); 
              $h=date("d/m/Y H:i:s");
              $reqrec= $connection-> prepare ('INSERT INTO historique (concerne, datecon, heurcon) VALUES (?,?,?)');
              $reqrec->execute(array($f, $d, $h));


              /*=== update====*/
              $booleen='O';
              $queryupdate = "
   UPDATE  `user` SET `status`=? WHERE idus=?
  ";

  $statementupdate = $connection->prepare($queryupdate);

  $statementupdate->execute(array($booleen,$_SESSION['idus']));
              
             

            if ($f=="Secrétaire Principal")
            {
                $_SESSION['sp1']='sp1';
                $_SESSION['autoriser']="sp";
                
                header("location:sp/consulter/emploi_du_temps/index_emploi_du_temps.php?idus=".$_SESSION['idus']);
                 //echo  '<script>window.location.replace("sp/renseigner/annee_academique/index_annee_acad.php");</script>';
                                
              
            }/*================fin  if fonction sp  ==========================*/

            if ($f=="Préfet"){
                $_SESSION['pd1']='pd1';
                $_SESSION['autoriser']="pd";
                header("location:prefet/consulter/emploi_du_temps/index_emploi_du_temps.php?idus=".$_SESSION['idus']);
                
              
            }/*================fin  if fonction prefet  ==========================*/ 
            if ($f=="Directeur"){
                $_SESSION['dg1']='dg1';
                $_SESSION['autoriser']="dg";
                header("location:di/consulter/emploi_du_temps/index_emploi_du_temps.php?");
                
              
              
            }/*================fin  if fonction directeur  ==========================*/  
            if ($f=="Directeur Adjoint"){
                $_SESSION['da1']='da1';
                $_SESSION['autoriser']="da";
                header("location:DA/consulter/emploi_du_temps/index_emploi_du_temps.php?");
                
              
            }/*================fin  if fonction directeur adjoint  ==========================*/   
            
            }/*================fin count de administrateur  ==========================*/ 
            
            }/*================fin  if de requette sp da   ==========================*/ 


           /*================== etudiant responsable ===========*/
           if ($reqetud= $connection-> prepare ('SELECT * FROM etudiant_respo WHERE mailetud = ? AND motpass = ?')) {
           $reqetud -> execute (array($em,$mp));
            $cntetud = $reqetud->rowcount();
            if ($cntetud==1) {
              $rowetud = $reqetud-> fetch();
            
              $_SESSION['codfiletud']=$rowetud['filet'];
              $_SESSION['codcycletud']=$rowetud['cyclet'];
              $_SESSION['nivetud']=$rowetud['nivet'];
              $_SESSION['annetud']=$rowetud['annet'];
              $_SESSION['nometud']=$rowetud['nomet'];
              $_SESSION['prenometud']=$rowetud['prenomet'];
              $_SESSION['imagetud']=$rowetud['imaget'];
              $_SESSION['mailetud']=$rowetud['mailetud'];
              $_SESSION['respo_num']=$rowetud['respo_num'];
               $_SESSION['respo']='respo';
             
             /* =================== $_SESSION['sp1']='sp1';
              $_SESSION['autoriser']="sp"; ==============================*/
                header("location:etudiant_respo/cahier_de_text/index_cahier.php?");
                                
             
            
             
            }
    
          }/*================fin etudiant ==================*/ 
            
          
          /*================ requet enseignant =================*/
          $reqenseignant= $connection-> prepare ('SELECT * FROM enseignant WHERE mail = ? AND mot_de_pass_ens = ?');
          if (!$reqenseignant=='') {
           $reqenseignant -> execute (array($em,$mp));
            $cntenseignant = $reqenseignant->rowcount();
            if ($cntenseignant==1) {
              $rowenseignant = $reqenseignant-> fetch();
              $_SESSION['nomet']=$rowenseignant['nomprof'];
              $_SESSION['prenomet']=$rowenseignant['prenomprof'];
              $_SESSION['imaget']=$rowenseignant['image'];
              $_SESSION['numatprof']=$rowenseignant['numatprof'];
              $_SESSION['enseignant']='enseignant';
             
             /* =================== $_SESSION['sp1']='sp1';
              $_SESSION['autoriser']="sp"; ==============================*/
              header("location:ensignant/valid_cahier_de_text/index_val_cahier.php?");
                 //echo  '<script>window.location.replace("DA/valid_cahier_de_text/index_val_cahier.php");</script>';
                                
              
            
            
             
            }
    
          }/*================fin enseignant ==================*/ 






          }/*================end if mail et mot de passe vide  ==========================*/ 
         
        }//submit  



?>
<!DOCTYPE html>
<html lang="fr-FR">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>UCAO-UUC/ESMEA</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo-ucao-uuc.png" rel="icon">
  <link href="assets/img/logo-ucao - apple-touch.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https:fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Main CSS File and fontawesome -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/fontawesome/css/font-awesome.min.css" rel="stylesheet"> <!--load all styles -->
  
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <div id="topbar" class="d-none d-lg-flex align-items-center fixed-top">
    <div class="container d-flex">
      <div class="contact-info mr-auto">
        <i class="icofont-envelope"></i> <a href="mailto:ucao_benin@yahoo.fr">ucao_benin@yahoo.fr</a>
        <i class="icofont-phone"></i> (+229) 21 30 51 18
      </div>
      <div class="social-links">
        <a href="https:twitter.com/login?lang=fr" class="twitter"><i class="icofont-twitter"></i></a>
        <a href="https:www.facebook.com" class="facebook"><i class="icofont-facebook"></i></a>
        <a href="https:www.instagram.com/accounts/login/?hl=fr" class="instagram"><i class="icofont-instagram"></i></a>
        <a href="#" class="skype"><i class="icofont-skype"></i></a>
        <a href="https:www.linkedin.com/uas/login?fromSignIn=true&trk=cold_join_sign_in" class="linkedin"><i class="icofont-linkedin"></i></i></a>
      </div>
    </div>
  </div>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <h1 class="logo mr-auto"><a >
            <img src="assets/img/logo_ucao.jpg" class="img-fluid" alt="" width="50" height="50" style="border-radius: 50px;">
      </a></h1>
     

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active"><a href="index_menu.php">Acceuil</a></li>
          <li><a href="#team">Ecoles & Facultés</a></li>
          <li><a href="#portfolio">Images</a>
            <li class="drop-down"><a >Consulter</a>
            <ul>
              <li class="drop-down"><a >Emplois du temps</a>
                <ul>
                  <li align="Center"><a href="emploi_du_temps/index_emploi_du_temps.php">ESMEA</a></li>
                  <li align="Center"><a href="#">EGEI</a></li>
                  <li align="Center"><a href="#">FDE</a></li>
                  <li align="Center"><a href="#">FSAE</a></li>
                </ul>
              </li>
              <li class="drop-down"><a >Répartition des salles</a>
                <ul>
                 <li align="Center"><a href="repartition_des_salle/index_repartition_des_salle.php">ESMEA</a></li>
                  <li align="Center"><a href="#">EGEI</a></li>
                  <li align="Center"><a href="#">FDE</a></li>
                  <li align="Center"><a href="#">FSAE</a></li>                  
                </ul>
              </li>
              
            </ul>
          </li>
          <li><a href="#services">Communiqués</a></li>
          <li><a href="#contact">Contacts</a></li>
          <li><a  href="#myModal"  data-toggle="modal">Se connecter</a></li>
          <li><a href="#about">A propos</a></li>
          
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">
      <h1>Bienvenue à <span> UCAO-UUC</spa>
      </h1>
      <h2>Université Catholique de l'Afrique de l'Ouest Unité Universitaire à Cotonou</h2>
      <div class="d-flex">
        <a href="#about" class="btn-get-started scrollto">Présentation de UCAO-uuc</a>
        <a href="https://www.youtube.com/watch?v=Ws3DTgbl35c&t=39s" class="venobox btn-watch-video" data-vbtype="video" data-autoplay="true"> Régarder une Video <i class="icofont-play-alt-2"></i></a>
      </div>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    
    <!-- ======= About Section ======= -->
    <section id="about" class="about section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>UCAO-UUC</h2>
          <h3>Présentation de l'<span>UCAO</span></h3>
          <p>La création de l’Université Catholique de l’Afrique de l’Ouest constitue
               une extension et un approfondissement de l'expérience issue de la création de
               l'Institut Catholique de l'Afrique de l'Ouest ( ICAO ).</p>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="zoom-out" data-aos-delay="100">
            <img src="assets/img/logo_ucao.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <h3>Historique et activités de l’ucao</h3>
            <p class="font-italic">
            </p>
            <ul>
              <li>
                <i class="bx bx-store-alt"></i>
                <div>
                  <h5>Historique de ucao</h5>
                  <p>Historiquement, c'est en 1967 que la Conférence Episcopale Régionale de l'Afrique de l'Ouest (CERAO) a créé l'Institut Catholique de l'Afrique de l'Ouest, sous forme d'Institut Supérieur de Culture Religieuse (ISCR). Plus récemment, des établissements d'enseignement secondaire ont ouvert, de façon indépendante, des filières de formation de niveau universitaire. 
La perspective de création d'une Université Catholique, à l'échelle des territoires de la CERAO, a été exprimée au cours de la réunion du Conseil Permanent de la CERAO de février 1995. Une étude du projet a été décidée par le Conseil, et confiée notamment à une Commission Consultative, en étroite collaboration avec des experts de la Conférence Episcopale Italienne (C.E.I). 
Le principe de création de l'UCAO a alors été adopté par la CERAO au cours de l'Assemblée Plénière de la Conférence tenue à Dakar, du 4 au 9 février 1997. 
En son Assemblée Plénière de Conakry, tenue du 1er au 6 février 2000, la Conférence Episcopale Régionale de l'Afrique de l'Ouest (CERAO) a créé l'Université Catholique de l'Afrique de l'Ouest (UCAO), comme Université de Droit Pontifical sans limitation de durée, à l'échelle des pays de la CERAO.</p>
                </div>
              </li>
              <li>
                <i class="bx bx-book"></i>
                <div>
                  <h5>Activités d'ucao</h5>
                  <p></p>
                </div>
              </li>
            </ul>
            <p>
              L’Université Catholique de l'Afrique de l'Ouest (UCAO) répond, dans sa constitution, son organisation et son fonctionnement, aux normes de l'Eglise régissant les Universités et Instituts Supérieurs, notamment aux normes des constitutions apostoliques Sapientia Christiana et Ex corde Ecclesiae et leurs Directives d'application. 
Se donnant pour objectif, conformément à la constitution apostolique Ex corde Ecclesiae, « d'assurer sous une forme institutionnelle une présence chrétienne dans le monde universitaire face aux grands problèmes de la société et de la culture » l'UCAO doit répondre, en tant que catholique, aux caractéristiques essentielles suivantes : « Faire preuve d'inspiration chrétienne, de la part non seulement des individus mais aussi de la Communauté universitaire en tant que telle ; fournir une réflexion continuelle, à la lumière de la foi catholique, sur les acquisitions récentes de la connaissance humaine auxquelles elle cherche à apporter une contribution par ses propres recherches ; faire preuve de fidélité au message chrétien tel qu'il est présenté par l'Eglise ; faire preuve d'un engagement institutionnel au service du peuple de Dieu et de la famille humaine en marche vers la fin transcendante qui donne sons sens à la vie ». 
L'UCAO veut répondre, en conformité avec cet objectif de l'Université Catholique, aux attentes des populations. Elle correspond aux nouvelles dimensions de la mission éducatrice de l'Eglise en Afrique au niveau universitaire. 
L'UCAO constitue : 
-une base d'évangélisation des intelligences en Afrique, par le biais d'une imprégnation de toute la vie par l'esprit des Béatitudes ; 
-une institution académique tutelle pour l'ensemble des structures et filières de formation de niveau universitaire existantes ou à créer ; 
-un réseau universitaire délivrant des diplômes de valeur internationale sur la base d'activités d'enseignement et de recherche axées sur les réalités locales, sans omission de l'environnement international. 
Par sa nature de Réseau d'Unités Universitaires (UU) installées dans différents pays, selon des options spécifiques, l'UCAO a pour vocation de couvrir le champ le plus large possible des sciences et de la technologie, avec le plus grand souci d'efficience et de fidélité au dialogue entre la raison et la foi.

            </p>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients section-bg">
      <div class="container" data-aos="zoom-in">

        <div class="row">
          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            
          </div><div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
           
          </div>
          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center"  style="width: 1000px;">
            <img src="assets/img/clients/logo-boa-benin.png" class=" mw-100 img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center" style=" width: 2000px;">
            <img src="assets/img/clients/gbfi.jpg" class=" img-fluid mw-100 mh-100" alt=""  style="" >
          </div>

        </div>

      </div>
    </section><!-- End Clients Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Communiqués</h2>
          <h3>Récents <span>Communiqués</span></h3>
          <p></p>
        </div>
         
        <div class="row">
          <?php 

         $query = " SELECT * FROM ativucao ORDER BY id DESC LIMIT 6";
       $statement = $connection->prepare($query);
       $statement->execute();
       $result = $statement->fetchAll();
       $compt = $statement->rowCount();
       $count=0;       
       foreach ( $result as $row)
        {
           $count=$count+100;
               $img= $row['img'];
               $debdat= $row['debdat'];
               $info= ucfirst($row['info']);
               if ($count<=300) {

                if ($img!='') {
                  
                    echo '
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="'.$count.'" style="margin-bottom:8px;margin-bottom:8px;">
            <div class="icon-box">
              <div class="">
              <img src="assets/img/communique/'.$img.'" class="img-rounded" style="width: 300px;height: 200px;"   />
              
              </div>
              
              <p  style="width: 300px;">'.$info.'</p>
            </div>
          </div>
          ';
        }//image
        else{
          echo '
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="'.$count.'" style="margin-bottom:8px;margin-bottom:8px;">
            <div class="icon-box">
                         
              <p  style="width: 300px;">'.$info.'</p>
            </div>
          </div>
          ';
        }//fin img
                }
                if ($count>300) {
                  $count=0;
                  $count=$count+100;
                  if ($img!='') {
                  
                    echo '
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="'.$count.'" style="margin-bottom:8px;margin-bottom:8px;">
            <div class="icon-box">
              <div class="">
              <img src="assets/img/communique/'.$img.'" class="img-rounded" style="width: 300px;height: 200px;"   />
              
              </div>
              
              <p  style="width: 300px;">'.$info.'</p>
            </div>
          </div>
          ';
        }//image
        else{
          echo '
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="'.$count.'" style="margin-bottom:8px;margin-bottom:8px;">
            <div class="icon-box">
                         
              <p  style="width: 300px;">'.$info.'</p>
            </div>
          </div>
          ';
        }//fin img
                  
                }

          }//fin foreach



          ?>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
      <div class="container" data-aos="zoom-in">

        <div class="owl-carousel testimonials-carousel">

          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Action Commerçiale et froce de Vente (ACFV)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Assurance (ASS)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Audit et Contrôle de Gestion (ACG)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Commerce International (CI)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Communication et Action Publicitaire (CAP)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Gestion des Banques et Finances (GBF)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
               Gestion des Ressources Humaines  (GRH)

              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Informatique de Gestion (IG)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Management des Ressources Humaines (MRH)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          
          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
               Marketing et Communication (MC)

              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

          <div class="testimonial-item">
            <img src="assets/img/testimonials/ndi.jpg" class="testimonial-img" alt="">
            <h3>ESMEA</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              Transport et Logistique (TL)

              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
             
             <!--  EGEI-->
            <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Electronique(ELN)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Electrotechnique(ETCH)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Génie Télécoms et TIC (GTTIC)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Informatique Industrielle et Maintenance (IIM)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          
          <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Automatisme et Système de Production (ASP)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/logo.jpg" class="testimonial-img" alt="">
            <h3>EGEI</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Télécommunication et Réseaux Informatiques  (TRI)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <!-- AGRO-->
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Gestion des Entreprises Rurales et Agricoles  (GERA)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Production et Gestion des Ressources Animales (PGRA)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Stockage – Conservation et Conditionnement des Produits Agricoles (SCCPA)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Sciences et Techniques de Production Végétale  (STPV)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Gestion de l’Environnement et Aménagement du Territoire   (GEAT)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Administration et Politique de l'Environement (APE)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
         
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Gestion de l'Espace Urbain  (GEU)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/donbosco.jpg" class="testimonial-img" alt="">
            <h3>FSAE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Gestion des Ressources Naturelles   (GRN)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

   <!-- DROIT-->
           <div class="testimonial-item">
            <img src="assets/img/testimonials/aupiais.jpg" class="testimonial-img" alt="">
            
            <h3>FDE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Economie   (ECO)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>
          <div class="testimonial-item">
            <img src="assets/img/testimonials/aupiais.jpg" class="testimonial-img" alt="">
            
            <h3>FDE</h3>
            <h4>ucao-uuc</h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
             Droit   (DRT)
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>




        </div>

      </div>
    </section><!-- End Testimonials Section -->

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Images</h2>
          <h3>Ecoles <span>&</span> Facultés</h3>
          <p></p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">Toutes</li>
              <li data-filter=".filter-app">MEMBRES AMINISTRATIFS</li>
              <li data-filter=".filter-card">ETUDIANTS</li>
              <li data-filter=".filter-web">ACTIVITES</li>
            </ul>
          </div>
        </div>

        
        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/per.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p >Pére BEDJRA Fokomlan</p>
              <p >Président émérite de ucao-uuc</p>
              <a href="assets/img/portfolio/per.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <img src="assets/img/portfolio/elect.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Travaux pratiques</h4>
              <p>Apprenants en situaion de formation </p>
              <p> en atelier au laboratoir de l'EGEI</p>
              <a href="assets/img/portfolio/elect.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Travaux pratiques"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/membre4.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Messe de rentrée solennelle 2019-2020</h4>
              <p>Membres de l'administration</p>
              <a href="assets/img/portfolio/elect.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Messe de rentrée solennelle 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/cla.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Apprenants</h4>
              <p>Situation de classe</p>
              <a href="assets/img/portfolio/cla.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Apprenants"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <img src="assets/img/portfolio/equip.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Vie associative</h4>
              <p>L'equipe de football en entraînnement</p>
              <a href="assets/img/portfolio/equip.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Vie associative"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/develop.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Présidium/conférencier : professeur SOGBOSSI Bertrand,Directeur de</p>
              <p> l'ESMEA ,professeur titulaire des universités de CAMES </p>
              <a href="assets/img/portfolio/develop.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/bonpas.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Rentrée solannelle 2019-2020</h4>
              <p>Messe de rentrée 2019-2020</p>
              <a href="assets/img/portfolio/bonpas.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Rentrée solannelle 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/claset.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Apprenants</h4>
              <p>Situation de classe</p>
              <a href="assets/img/portfolio/claset.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Apprenants"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/etudi.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Rentrée solannelle 2019-2020</h4>
              <p>Messe de rentrée 2019-2020</p>
              <a href="assets/img/portfolio/etudi.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Rentrée solannelle 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>
          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/ceremoni.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Dernier hommage des apprenants de l'uuc</h4>
              <p>Dernier hommage des apprenants </p>
              <p>de l'uuc au pére Jacob Mèdéwalé </p>
              <p> AGOSSOU 1<sup>er</sup> pro-président de l'uuc et principale artisant de l'implantaion de l'uuc à cotonou</p>
              <a href="assets/img/portfolio/ceremoni.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Dernier hommage des apprenants de l'uuc"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>
           <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <img src="assets/img/portfolio/seu.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Rentrée solannelle 2019-2020</h4>
              <p>Messe de rentrée 2019-2020</p>
              <a href="assets/img/portfolio/seu.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Rentrée solannelle 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>


          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <img src="assets/img/portfolio/equip2.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Vie associative</h4>
              <p>L'equipe de football en entraînnement</p>
              <a href="assets/img/portfolio/equip2.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Vie associative"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/evec.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Monseigneur GONZALO saluant</p>
              <p> le directeur de ESMEA</p>
              <a href="assets/img/portfolio/evec.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

           <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/membre1.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Vue partielle du personnelle </p>
              <p> administratif</p>
              <a href="assets/img/portfolio/membre1.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

           <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/decorat.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Journée culturelle 2016-2017</h4>
              <p>Remise de trophée à l'équipe de football</p>
              <a href="assets/img/portfolio/decorat.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Journée culturelle 2016-2017"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>
          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/membre22.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Vue partielle du personnelle </p>
              <p> administratif</p>
              <a href="assets/img/portfolio/membre22.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>
          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/sap.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Présidium/conférencier : professeur SOGBOSSI Bertrand,Directeur de</p>
              <p> l'ESMEA ,professeur titulaire des universités de CAMES </p>
              <a href="assets/img/portfolio/sap.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>

           <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <img src="assets/img/portfolio/membre3.jpg" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Conférence inaugurale 2019-2020</h4>
              <p>Monseigneur GONZALO et Dr BOYI Bonaventure, ancien directeur de ESMEA</p>
              <a href="assets/img/portfolio/membre3.jpg" data-gall="portfolioGallery" class="venobox preview-link" title="Conférence inaugurale 2019-2020"><i class="bx bx-plus"></i></a>
              <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
            </div>
          </div>






        </div>

      </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>UCAO-UUC</h2>
          <h3>Ecoles <span>&</span> Facultés</h3>
          <p><!--Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expedita at voluptas atque vitae autem.--></p>

        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/ndi.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h5><div class="rn_post_wrap ">
                  <div class="rn_post_loop">
                    <div class="title_content_wrap">                                                  <div class="tn_title"><a href=""><span class="fa fa-graduation-cap">                  
                    </span> Ecole Superieure de Management et d'Economie Appliquée</a></div>                                                 
                                </div>
                              </div>
                            </h5>
                <span> ( ESMEA )</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/logo.jpg" width="600" height="600" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h5><div class="rn_post_wrap ">
                  <div class="rn_post_loop">
                    <div class="title_content_wrap">                                                  <div class="tn_title"><a href=""><span class="fa fa-graduation-cap">                  
                    </span> Ecole de Génie Electrique et Informatique</a></div>                                                 
                                </div>
                              </div>
                            </h5>
                <span> ( EGEI )</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/donbosco.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h5><div class="rn_post_wrap ">
                  <div class="rn_post_loop">
                    <div class="title_content_wrap">                                                  <div class="tn_title"><a href=""><span class="fa fa-graduation-cap">                  
                    </span>Faculté des Sciences de l'Agronomie et de l'Environement </a></div>                                                 
                                </div>
                              </div>
                            </h5>
                <span>( FSAE )</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <div class="member-img">
                <img src="assets/img/team/aupiais.jpg" class="img-fluid" alt="" >
                <div class="social">
                  <a href=""><i class="icofont-twitter"></i></a>
                  <a href=""><i class="icofont-facebook"></i></a>
                  <a href=""><i class="icofont-instagram"></i></a>
                  <a href=""><i class="icofont-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h5><div class="rn_post_wrap ">
                  <div class="rn_post_loop">
                    <div class="title_content_wrap">                                                  <div class="tn_title"><a href=""><span class="fa fa-graduation-cap">                  
                    </span>Faculté de Droit et d'Economie</a></div>                                                 
                                </div>
                              </div>
                            </h5>
                <span>( FDE )</span>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Team Section -->

    
    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Inscriptions</h2>
          <h3>Conditions à <span>Remplir</span></h3>
         <!-- <p>Demarrage des inscriptions: 14 septembre 2020 (voir avec PHP)</p>-->
        </div>

        <ul class="faq-list" data-aos="fade-up" data-aos-delay="100">

          <li>
            <a data-toggle="collapse" class="" href="#faq1">Conditions d'admission<i class="icofont-simple-up"></i></a>
            <div id="faq1" class="collapse show" data-parent=".faq-list">
              <p>
                Avoir obligatoirement le bac ou un diplôme équivalant.Les inscriptions sont soumises à une étude préalable de dossier.
              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq2" class="collapsed">Pièces à fournir<i class="icofont-simple-up"></i></a>
            <div id="faq2" class="collapse" data-parent=".faq-list">
              <p>
                <ul type="disc">
                  <li>1 Exrait d'acte de naissance légalisé.</li>
                  <li>1 Certificat de scolarité.</li>
                  <li>1 Photocopie des deux bulletins de notes de la dernière année scolaire.</li>
                  <li>1 Photocopie légalisé des diplômes obtenus (CEP-BEPC-BAC).</li>
                  <li>1 Photocopie de la carte d'identité nationale.</li>
                  <li>4 Photos d'identité <strong>(prise de vue sur place).</strong></li>
                  <li>1 Demande d'inscription manuscrite précisant la filière choisie et portant l'adresse des parents ou du tuteur, leurs contacts: Tél, Fax ,Email. Demande vidée par les parents.</li>
                  <li>1 Certificat de nationalité légalisé.</li>
                  <label style="color: blue;">NB: Les inscriptions à l'EGEI et à la FSAE sont subordonnées à la présentation de tous les relevés de la note 2<sup>nde</sup> enTle.</label>
                </ul>
              </p>
            </div>
          </li>

          <li>
            <a data-toggle="collapse" href="#faq3" class="collapsed"><i class="icofont-simple-up"></i>Etude de dossier</a>
            <div id="faq3" class="collapse" data-parent=".faq-list">
              <p>
                <ul type="none" style="color: blue;">
                  <li><label STYLE="padding:0 0 0 20px;"> =>     Première année:</label>                                                              <label STYLE="padding:0 0 0 20px;"> 5.000 f cfa</label></li>
                  <li><label STYLE="padding:0 0 0 20px;"> =>     Semestre intermédiaires: </label>                                                              <label STYLE="padding:0 0 0 20px;"> 10.000 f cfa</label></li>
                  <li><label STYLE="padding:0 0 0 20px;"> =>     Licence:   </label>                                                               <label STYLE="padding:0 0 0 20px;"> 10.000 f cfa</label></li>
                  <li><label STYLE="padding:0 0 0 20px;"> =>     Master: </label>                                                               <label STYLE="padding:0 0 0 20px;"> 20.000 f cfa</label></li>
                </ul>
              </p>
            </div>
          </li>
         
      </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Contacts</h2>
          <h3><span>Direction générale de ucao-uuc</span></h3>
          <p></p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-6">
            <div class="info-box mb-4">
              <i class="bx bx-map"></i>
              <h3>Address</h3>
              <p>Eglise Catholique Bon Pasteur, Cadjehoun, Cotonou</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-envelope"></i>
              <h3>Email</h3>
              <p>ucao_benin@yahoo.fr</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <i class="bx bx-phone-call"></i>
              <h3>Téléphone</h3>
              <p>(+229) 21 30 51 18/(+229) 21 30 51 17</p>
            </div>
          </div>

        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-6 ">
            <iframe src="https:www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.28491770187!2d2.395753114082175!3d6.3571543268401145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10235434f70b1b67%3A0x54a068596be0378c!2sUCAO-UUC!5e0!3m2!1sfr!2sbj!4v1598356142037!5m2!1sfr!2sbj"  frameborder="0" style="border:0; width: 100%; height: 384px;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="form-row">
                <div class="col form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Nom" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                  <div class="validate"></div>
                </div>
                <div class="col form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Email" data-rule="email" data-msg="Veillez saisir un email" />
                  <div class="validate"></div>
                </div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                <div class="validate"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                <div class="validate"></div>
              </div>
              <div class="mb-3">
                <div class="loading">Chargement...</div>
                <div class="error-message"></div>
                <div class="sent-message">Le message a été envoyé avec success. Merci!</div>
              </div>
              <div class="text-center"><button type="submit">Envoyer</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

     <div class="footer-newsletter">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <h4>Devise de UCAO-UUC</h4>
            <p>Foi-Science-Action</p>
           
          </div>
        </div>
      </div>
    </div>

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>UCAO-UUC<span></span></h3>
            <p>
              04 B.P 928 Cotonou <br>
              Eglise Bon Pasteur ( Cadjehoun ) , Cotonou<br>
              Benin <br><br>
              <strong>Téléphone:</strong> (+229) 21 30 51 18/(+229) 21 30 51 17<br>
              <strong>Email:</strong> ucao_benin@yahoo.fr<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>LIENS UTILES</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Acceuil</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Consulter</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#services">Communiqués</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#myModal"  data-toggle="modal" >Se connecter</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#team">Ecoles & Facultés</a></li>
              <li><i class="bx bx-chevron-right"></i><a  href="#contact">Contact</a></li>
            </ul>
          </div>
         
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>UCAO</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Abidjan (Côte d'Ivoire)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Bamako (Mali)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Bobo Dioulasso (Burkina Faso)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Conakry (Guinée)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Cotonou (Bénin)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Lomé (Togo)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Zinguinchor (Sénégal)</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Yamoussokro (Côte d'Ivoir)</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>REJOIGNEZ-NOUS</h4>
            <p>Toute l'actualité de l'UCAO-UUC en temps réel sur nos réseaux sociaux</p>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container py-4">
      <div class="copyright">
        &copy; Copyright <strong><span>UCAO/UUC-ESMEA</span></strong>. Tous droits réservés
      </div>
      <div class="credits">
        
         Conçut par <a href="https://m.facebook.com/elvis.sodjo.1">SODJO Elvis </a> & <a href="https://m.facebook.com/profile.php?id=100009751115449">OUEDRAOGO Abdoul Azize dit Jean </a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>








<!-- ========modal se connecter====-->



<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content" style="height: 500px;">

      <!-- Modal Header -->
      <div class="modal-header" style="">
        <h4 class="modal-title" style="margin-left: 150px;font-family: arial black;letter-spacing: 1px;color: white;text-shadow: 2px 1px 2px black;">Connexion</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" style="background-color: ;"><!--  style="background: url('assets/img/se_connecter/educ.png') no-repeat;" -->
           <h3   style="width: 100px; height: 100px; border-radius: 50px;border:1px solid; text-align:  center;margin-left: 170px;background-color: white; "> <font size="35"> <span class="fa fa-user" style="margin: 20px; "></span></font></h3>
        <!-- =====  formulaire ===== -->
        <form method="post" id="user_form" action="" style="margin-top: 20px;">

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" style="width: 125px;color: black;background-color: silver;">Email</span>
    </div>
    <input type="text" name="mailu" id="mailu" class="form-control mailu" placeholder="Entrez votre email">
  </div>
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" style="width: 125px;color: black;background-color: silver;">Mot de passe</span>
    </div>
    <input type="password"  name="passu" id="passu" class="form-control passu"  placeholder="Entrez votre mot de passe" maxlength="8" minlength="6">
  </div><br>

<div class="text-group-prepend text-center "><button type="submit"  name="submit" id="submit" class="btn btn-primary submit" style="font-family: arial black;letter-spacing: 1px;text-shadow: 2px 1px 2px black; ">Se connecter</button>
</div>
</form>
</div>


    </div>
  </div>
</div>
