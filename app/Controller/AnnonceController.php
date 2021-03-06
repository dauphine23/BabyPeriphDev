<?php

namespace Controller;

use \W\Controller\Controller;
use \W\Model\UsersModel;
use \W\Security\AuthentificationModel;
use \W\Security\AuthentificationModel as Auth;
use \Model\annonce;

class AnnonceController extends Controller {

  /**
  * Page de création d'annonce
  */
  public function index()
  {
    $loggedUser = $this->getUser();
    $this->show('annonce/index',['user' => $loggedUser]);
  }

  /**
  * Ajout de l'annonce en base de données
  */

  public function traitementAnnonce(){
      $auth = new AuthentificationModel;
      $user = $auth->getLoggedUser();
      if (!isset($user)) {
        $this->redirectToRoute('connexion_index');
      }
      $name = $_POST['name'];
      $desc = $_POST['desc'];
      $prix = $_POST['prix'];
      $duree = $_POST['duree'];
      $address = $_POST['address'];
      $city = $_POST['city'];
      $tel = $_POST['tel'];
      $imagePrincipale = $_FILES['fichier']['name'];
      $selectTypeAnnonce = $_POST['select_type_annonce'];
      //var_dump($address)
      switch ($selectTypeAnnonce) {
        case 1:
        $isLocation =true;
        $isVente =false;
        $isService = false;
        break;
        case 2:
        $isLocation =false;
        $isVente =true;
        $isService = false;
        break;
        case 3:
        $isLocation =false;
        $isVente =false;
        $isService = true;
        break;
        default:
        $isLocation =false;
        $isVente =false;
        $isService = false;
        break;
      }

      $annonce = new annonce();
      $annonceData = $annonce->Insert([ 'annonceName' => $name,
      'annonceDescription' => $desc,
      'prix' => $prix,
      'dureeDuPrix' => $duree,
      'idUtilisateur' => 4,
      'isLocation' => $isLocation,
      'isVente' => $isVente,
      'isService' => $isService,
      'annonceAddress' => $address,
      'annonceCity' => $city,
      'annoncePhone' => $tel,
      'idUtilisateur' => $user['id']
    ]);

    if (isset($imagePrincipale) && strlen($imagePrincipale)>0) {
      $imagePrincipale = $annonceData['id'] .'_' .$imagePrincipale;
      $update = $annonce->Update(['imagePrincipale' => $imagePrincipale],$annonceData['id']);
      $file_name = $_FILES['fichier']['name'];
      $destination_folder = '../public/assets/img/annonce/' .$annonceData['id'] .'_'  .$file_name;
      $tmp = $_FILES['fichier']['tmp_name'];
      if (!move_uploaded_file($tmp,$destination_folder)) {
        $erreur = 'Erreur, impossible de copier le fichier dans:' .$destination_folder;
        exit($erreur);
      }
    }

  	$this->redirectToRoute('default_home');
  }

  //afficher la liste des annonces par thème
  public function allAnnonce($theme){
    $annonce = new annonce();
    $allAnnonce = $annonce->findAllAnnonceByTheme($theme);
    // echo '<pre>';
    // print_r($allAnnonce);
    // echo '</pre>';
    $this->show('annonce/allAnnonce',['allAnnonce' => $allAnnonce]);
  }
  // afficher mes annonces
  public function myAnnonce(){
    $loggedUser = $this->getUser();
    $annonce = new annonce();
    $myAnnonce = $annonce->findMyAnnonce($loggedUser);
    // echo '<pre>';
    // print_r($myAnnonce);
    // echo '</pre>';
    $this->show('annonce/myAnnonce',['myAnnonce' => $myAnnonce]);
  }

  // afficher mes annonces
  public function detail($id){
    $loggedUser = $this->getUser();
    $annonce = new annonce();
    //$detailAnnonce = $annonce->getAnnonce($id);
    $detailAnnonce = $annonce->getAnnonceById($id);
    // echo '<pre>';
    // print_r($detailAnnonce);
    // echo '</pre>';
    // var_dump($detailAnnonce);
    $this->show('annonce/detail',['detailAnnonce' => $detailAnnonce]);
  }

  public function updateDelete($id){
    //var_dump($id);
    $annonce = new annonce();
    $detailAnnonce = $annonce->getAnnonceById($id);
    //var_dump($detailAnnonce);
    if (isset($_POST['deleteBtn'])) {

      if (isset($detailAnnonce['imagePrincipale'])) {

        $filePath = dirname(__FILE__) . "/../../public/assets/img/annonce/" .$detailAnnonce['imagePrincipale'];
        var_dump($filePath);
        if(file_exists($filePath)){
          unlink($filePath);
        }

      }

      //supprimer item in DB
      $delete = $annonce->Delete($detailAnnonce['id']);
      echo "<script>
              alert('Suppression avec succès');
            </script>";
      $this->redirectToRoute('default_home');
    }
    elseif (isset($_POST['updateBtn'])) {
      $this->show('annonce/index',['detailAnnonce' => $detailAnnonce]);
    }

 

    
  }

  public function update($id){
      $auth = new AuthentificationModel;
      $user = $auth->getLoggedUser();
      if (!isset($user)) {
        $this->redirectToRoute('connexion_index');
      }
      if (!isset($_POST['updateBtn'])) {
        $this->redirectToRoute('default_home');
      }
      $name = htmlentities(strip_tags($_POST['name']));
      $desc = htmlentities(strip_tags($_POST['desc']));
      $prix = htmlentities(strip_tags($_POST['prix']));
      $duree = htmlentities(strip_tags($_POST['duree']));
      $address = htmlentities(strip_tags($_POST['address']));
      $city = htmlentities(strip_tags($_POST['city']));
      $imagePrincipale = htmlentities(strip_tags($_FILES['fichier']['name']));
      $selectTypeAnnonce = $_POST['select_type_annonce'];
      //var_dump($address)
      switch ($selectTypeAnnonce) {
        case 1:
        $isLocation =true;
        $isVente =false;
        $isService = false;
        break;
        case 2:
        $isLocation =false;
        $isVente =true;
        $isService = false;
        break;
        case 3:
        $isLocation =false;
        $isVente =false;
        $isService = true;
        break;
        default:
        $isLocation =false;
        $isVente =false;
        $isService = false;
        break;
      }
      //var_dump($id);
      $annonce = new annonce();
      $annonceData = $annonce->Update([ 'annonceName' => $name,
      'annonceDescription' => $desc,
      'prix' => $prix,
      'dureeDuPrix' => $duree,
      'idUtilisateur' => 4,
      'isLocation' => $isLocation,
      'isVente' => $isVente,
      'isService' => $isService,
      'annonceAddress' => $address,
      'annonceCity' => $city,
      'idUtilisateur' => $user['id']
    ],$id);

    if (isset($imagePrincipale) && strlen($imagePrincipale)>0) {
      $imagePrincipale = $annonceData['id'] .'_' .$imagePrincipale;
      $update = $annonce->Update(['imagePrincipale' => $imagePrincipale],$annonceData['id']);
      $file_name = $_FILES['fichier']['name'];
      $destination_folder = '../public/assets/img/annonce/' .$annonceData['id'] .'_'  .$file_name;
      $tmp = $_FILES['fichier']['tmp_name'];
      if (!move_uploaded_file($tmp,$destination_folder)) {
        $erreur = 'Erreur, impossible de copier le fichier dans:' .$destination_folder;
        exit($erreur);
      }
    }
    echo "<script>
            alert('Mise à jour avec succès');
          </script>";
  	$this->redirectToRoute('default_home');
  }

  public function search(){
    //var_dump($_POST['searchAnnonce']);
    $annonce = new annonce();
    $allAnnonce = $annonce->findAllAnnonceBySearch($_POST['searchAnnonce']);
    // echo '<pre>';
    // print_r($allAnnonce);
    // echo '</pre>';
    $this->show('annonce/search',['allAnnonce' => $allAnnonce]);
  }
}
