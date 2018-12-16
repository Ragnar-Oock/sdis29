<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <title>SDIS29</title>
    <meta http-equiv="content-type">
    <meta charset="utf-8">
    <link href="./styles/styles.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
  	<script src="include/proceduresJava.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="include/jquery.js"></script>
  <?php
  	if (isset($_REQUEST['zFormulaire'])){$formulaire=$_REQUEST['zFormulaire'];}
  	if (isset($_REQUEST['zChamp'])){$champ =$_REQUEST['zChamp'];}
  ?>
  <body>
    <div id="page">
  		<div id="entete">
  	    <div id="sommaire">
    <?php
      if (isset($_SESSION['idUtilisateur']))
  		{
        echo '
        <ul>
          <li style="text-align:left;"><a href="index.php?choixTraitement=connexion&action=demandeConnexion" title="Se d&eacute;connecter" style="float:right;width: 60px;"><i class="fas fa-sign-out-alt" title="Se déconnecter"></i></a>';
    		if ($_SESSION['statut']==2)
  			{
          echo '
  			  <li><a href="index.php?choixTraitement=parametres&action=voir" title="parametres">parametres</a>
  			  <li><a href="index.php?choixTraitement=gardes&action=voir" title="gardes">gardes</a>
          <li><a href="index.php?choixTraitement=interventions&action=voir" title="interventions">interventions</a>';
        }
    		if ($_SESSION['statut']==3)
  			{
          echo '
          <li><a href="index.php?choixTraitement=parametres&action=voir" title="parametres">parametres</a>
    			<li><a href="index.php?choixTraitement=interventions&action=voir" title="interventions">interventions</a>
          <li><a href="index.php?choixTraitement=gardes&action=voir" title="gardes">gardes</a>';
        }
  			echo '
  			<li><a href="index.php?choixTraitement=pompiers&action=voir&type=a">pompiers</a>
  			<li><b>Bienvenue '.$_SESSION['prenom'].' '.strtoupper($_SESSION['nom']).' </b>
  			</ul>';
		  }
  ?>
  			<h1>SDIS29 : gestion des gardes et des interventions</h1>
  		</div>
  	</div>
<!-- fin affichage du menu -->
