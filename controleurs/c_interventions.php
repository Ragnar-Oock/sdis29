<?php
// ****************************************'
//  Le CASTEL-BTS SIO/ PROJET SDIS29       '
//  Programme: c_interventions.php         '
//  Objet    : gestion des interventions   '
//  Client   : Bts SIO2                    '
//  Version  : 1.0                         '
//  Date     : 11/10/2018 à 22h31          '
//  Auteur   : pascal-blain@wanadoo.fr     '
//*****************************************'
$action = $_REQUEST['action'];
switch($action)
{
	case 'voir': {
		// traitement
		// declaration des variables
		$leGroupement = $pdo->getleCentre($_SESSION['cis']);
		$lesCis = $pdo->getLesCasernes($leGroupement[0][0]);
		$laDate = date('Y-m-d');
		$cis = 0;

		if (isset($_REQUEST['aCaserne']) && $_REQUEST['aCaserne']!='' && isset($_REQUEST['tranche']) && $_REQUEST['tranche']!='') {
			// $lesPompiers = $pdo->getLesPompiersDeGarde($_REQUEST['caserne'], $laDate, $_REQUEST['tranche']);
			$lesPompiers = $pdo->getLesPompiersDeGarde($_REQUEST['aCaserne'], date('Y-m-d'), $_REQUEST['tranche']);
		}
		if (isset($_REQUEST['vCaserne'])) {
			$cis = $_REQUEST['vCaserne'];
		}

		if ($_SESSION['statut'] == 2) {
			$cis = $_SESSION['cis'];
		}

		if (isset($cis)) {
			if (isset($_REQUEST['tDate'])) {
				if (isset($_REQUEST['enCours']) && $_REQUEST['enCours']==1) {
					$lesInterventions = $pdo->getLesInterventions($cis, $_REQUEST['tDate'], true);
				}
				else {
					$lesInterventions = $pdo->getLesInterventions($cis, $_REQUEST['tDate']);
				}
			}
			else {
				if (isset($_REQUEST['enCours']) && $_REQUEST['enCours']==1) {
					$lesInterventions = $pdo->getLesInterventions($cis, null, true);
				}
				else {
					$lesInterventions = $pdo->getLesInterventions($cis, null);
				}
			}
			foreach ($lesInterventions as $uneIntervention) {
				$lesParticipants[$uneIntervention['iId']] = $pdo->getLesParticipants($uneIntervention['iCis'], $uneIntervention['iId']);
			}
		}

		// affichage
		include("vues/v_entete.php");
		include("vues/v_Interventions.php");
		break;
	}
	//-----------------------------------------
	case 'majInter': {
		// if (isset($_REQUEST['listEquipe'])) {
		// 	$lesPompiers[0] = 0;
		// 	foreach ($_REQUEST['listEquipe'] as $unPompier) {
		// 		$lesPompiers[0] = "zmfjvhg";
		// 	}
		// }
		// echo "les pompiers ";
		// var_dump($_REQUEST['listEquipe']);
		}

	case 'ajoutInter' :{
		if(isset($_REQUEST['listEquipe']))
		{
			$idInter=$pdo->ajoutInter($_REQUEST['aCaserne'], $_REQUEST['adresse'], $_REQUEST['description'], date('Y-m-d G:i'), $_REQUEST['tranche']);

		 foreach($_REQUEST['listEquipe'] as $unPompier)
			 {
				 $pdo->ajoutEquipe($_REQUEST['aCaserne'], $unPompier, $idInter[0]);
				}
		}

		//passage des valeurs des champs entres les pages
		$header = 'location: index.php?choixTraitement=interventions&action=voir';
		if (isset($_REQUEST['aCaserne']) && $_REQUEST['aCaserne']!='') {
			$header = $header.'&aCaserne='.$_REQUEST['aCaserne'];
		}
		if (isset($_REQUEST['tranche']) && $_REQUEST['tranche']!='') {
			$header = $header.'&tranche='.$_REQUEST['tranche'];
		}
		if (isset($_REQUEST['adresse']) && $_REQUEST['adresse']!='') {
			$header = $header.'&adresse='.addslashes($_REQUEST['adresse']);
		}
		if (isset($_REQUEST['description']) && $_REQUEST['description']!='') {
			$header = $header.'&description='.addslashes($_REQUEST['description']);
		}
		header ($header);

		break;
	}

	case 'finInter' :{
		if (isset($_REQUEST['idInter']) && !is_null($_REQUEST['idInter']) && isset($_REQUEST['idCis']) && !is_null($_REQUEST['idCis'])) {
			$rs = $pdo->finirInter($_REQUEST['idCis'], $_REQUEST['idInter']);
				}

		//passage des valeurs des champs entres les pages
		$header = 'location: index.php?choixTraitement=interventions&action=voir';
		if (isset($_REQUEST['caserne'])) {
			$header = $header.'&caserne='.addslashes($_REQUEST['caserne']);
		}
		if (isset($_REQUEST['enCours'])) {
			$header = $header.'&enCours='.addslashes($_REQUEST['enCours']);
		}
		if (isset($_REQUEST['tDate'])) {
			$header = $header.'&tDate='.addslashes($_REQUEST['tDate']);
		}
		header ($header);

		break;
	}
	//-----------------------------------------
	default : {
		header('location: index.php?choixTraitement=connexion&action=demandeConnexion');
		break;
	}
}
?>
