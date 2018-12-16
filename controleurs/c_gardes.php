<?php
$action = $_REQUEST['action'];
switch($action)
{
case 'voir':
	{
		include("vues/v_entete.php");

		//creation de la variable semaine
		if(!isset($_REQUEST['zSemaine']))
		{
			$_REQUEST['zSemaine'] = date('W')+1;
		}
		$semaine = $_REQUEST['zSemaine'];
		//creation de la variable annee
		if(!isset($_REQUEST['zAnnee']))
		{
			$_REQUEST['zAnnee'] = date('Y');
		}
		$annee = $_REQUEST['zAnnee'];
		//creation de la variable du premier jour de la semaine
		$sem = intval($semaine)-1;
		$premierJour = strtotime("+$sem weeks",mktime(0,0,0,1,1,$annee));
		if (date('w',$premierJour) != 1)
		{
			$premierJour = strtotime("last monday",$premierJour);
		}
		//recuperation des tranches horraires
		// $lesTranches = $pdo->getParametre("tranche");
		//recuperation des type de disponibilités
		// $lesTypesDispos = $pdo->getParametre("dispo");

		//recuperation de id des pompier pour la creation du tebleau d'affichage
		$lesPompiers = $pdo->getLesPompiers($_SESSION['cis']);
		foreach ($lesPompiers as $unPompier) {
			$lesDisposPompiers[$unPompier['pId']] = $pdo->getDisposHebdo($unPompier['pId'], $semaine, $annee);
		}

		include("vues/v_ficheGardes.php");
		break;
	}
//-----------------------------------------
case 'majGarde':
	{
		$lesPompiers = $pdo->getLesPompiers($_SESSION['cis']);
		$annee = $_REQUEST['zAnnee'];
		$semaine = $_REQUEST['zSemaine'];

		foreach ($lesPompiers as $unPompier) {
			$lesDisposPompiers[$unPompier['pId']] = $pdo->getDisposHebdo($unPompier['pId'], $semaine, $annee);
		}
		foreach ($lesPompiers as $unPompier) {
			for ($jour=0; $jour < 7; $jour++) {
				for ($tranche=1; $tranche < 5; $tranche++) {
					if (isset($_REQUEST[$unPompier['pId']."-".$jour."-".$tranche]) and $_REQUEST[$unPompier['pId']."-".$jour."-".$tranche]==1) {
						$nouvelleGarde = 1;
					}
					else {
						$nouvelleGarde = 0;
					}
					$pdo->majGarde(date("Y-m-d H:i:s", $_REQUEST['zPremierJour']+($jour*86400)), $tranche, $unPompier['pId'], $lesDisposPompiers[$unPompier['pId']][1][$jour*4+$tranche-1], $nouvelleGarde);
					// var_dump($nouvelleGarde);
				}
			}
		}
		//var_dump($lesDisposPompiers);
		header ('location: index.php?choixTraitement=gardes&action=voir&zSemaine='.$_REQUEST["zSemaine"].'&zAnnee='.$_REQUEST["zAnnee"]);
		break;
	}
//-----------------------------------------
default :
	{
		echo 'erreur d\'aiguillage !'.$action;
		break;
	}
}
?>
