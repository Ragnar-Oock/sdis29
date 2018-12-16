<?php
/**
 * @author 	: Pascal BLAIN, lycee le Castel à Dijon
 * @version : 1.2018-10-11
 * Classe dacces aux donnees. Utilise les services de la classe PDO pour lapplication
 * Les attributs sont tous statiques, les 4 premiers pour la connexion
 * $monPdo est de type PDO - $monPdoBD contient lunique instance de la classe
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoBD
{
	private static $serveur='mysql:host=localhost';
	private static $bdd='dbname=sdis29';
	private static $user='phpmyadmin';
	private static $mdp='admin';
	private static $monPdo;
	private static $monPdoBD=null;

	private function __construct()
	{
		PdoBD::$monPdo = new PDO(PdoBD::$serveur.';'.PdoBD::$bdd, PdoBD::$user, PdoBD::$mdp);
		PdoBD::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct()
	{
		PdoBD::$monPdo = null;
	}

	/**
	 * Fonction statique qui cree lunique instance de la classe PdoBD
	 * Appel : $instancePdoBD = PdoBD::getPdoBD();
	 */
	public  static function getPdoBD()
	{
		if(PdoBD::$monPdoBD==null)	{PdoBD::$monPdoBD= new PdoBD();}
		return PdoBD::$monPdoBD;
	}

	/**
	 * Retourne les informations dun centre de coordination
	 */
	public function getLesCasernes($leGroupement="")
	{
		$req = "SELECT cId, cNom, cAdresse, cTel, cGroupement
					FROM caserne
					WHERE cGroupement='".$leGroupement."'
					ORDER BY cNom;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des casernes ..", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}
	public function getleCentre($cis)
	{
		$req = "SELECT cGroupement FROM caserne WHERE cId=$cis;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la recuperation du centre", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

	/**
	 * Retourne les informations des pompiers
	*/
	public function getLesPompiers($cis)
	{
		$req = "SELECT pCis, pId, pNom, pPrenom, pStatut
					FROM pompier
					WHERE pCis=$cis
					ORDER BY pNom;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des pompiers ...", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}
	public function getLesPompiersDeGarde($cis=0, $date="2018-12-13", $tranche="1")
	{
		$req = "SELECT pCis,
		 							 pId,
									 pNom,
									 pPrenom,
									 aDisponibilite as dispo
						FROM pompier
							inner join activite on pCis = aCis and pId = aPompier
						WHERE pCis = $cis and aDateGarde = '$date' and aTranche=$tranche and aGarde = 1 and pId not in (
							SELECT ePompier
							from equipe
								INNER JOIN intervention on eIntervention = iId
								WHERE iHeureFin is null)
						ORDER BY dispo, pNom, pPrenom ASC";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des pompiers ...", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		// echo $req;
		return $lesLignes;
	}

	/**
	 * Retourne les informations dun pompier sous la forme dun tableau associatif
	*/
	public function getInfosPompier($login,$mdp)
	{
		$req = "SELECT pCis,
										pId as id,
										pNom as nom,
										pPrenom as prenom,
										pStatut,
										pMail,
										pLogin,
										pMdp,
										pompier.pType,
										pGrade,
										pAdresse,
										pCp,
										pVille,
										pBip,
										pCommentaire,
										cNom,
										cAdresse,
										cGroupement,
										cTel,
										g.pLibelle as wGrade,
										s.pLibelle as wStatut,
										t.pLibelle as wType
						FROM pompier
						INNER JOIN caserne ON pompier.pCis = caserne.cId
						INNER JOIN parametre AS s ON pompier.pStatut = s.pIndice
						INNER JOIN parametre AS g ON pompier.pGrade = g.pIndice
						INNER JOIN parametre AS t ON pompier.pType = t.pIndice
						WHERE s.pType = 'statAgt'
						AND g.pType = 'grade'
						and t.pType = 'typePer'";
		if ($login==="*")
		{
			$req.=" AND pCis=".$_SESSION['cis']." AND pId='$mdp'";
		}
		else
		{
			$req.=" AND pLogin='$login' AND pMdp='$mdp'";
		}
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des informations dun pompier...", $req, PdoBD::$monPdo->errorInfo());
		}
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Met à jour lactivité dun pompier sur une tranche
*/
	public function majActivite($jour, $tranche, $ancienneDispo, $nouvelleDispo)
	{
		$cis = $_SESSION['cis'];
		$pompier = $_SESSION['idUtilisateur'];

		//On teste l'égalité de la nouvelle disponibilite et de l'ancienne
		if($ancienneDispo != $nouvelleDispo)
		{
			//Ensuite si elles ne sont pas égales
			//On vérifie si le pompier est indisponible
			if($ancienneDispo == 0)
			{
				//On ajoute dans la table activite la disponibilite du pompier soit au travail, soit dispo
				$req= "INSERT INTO activite (aCis, aPompier, aDateGarde, aTranche, aDisponibilite)
							VALUES
							($cis, $pompier, '$jour', $tranche, $nouvelleDispo);";
			}
			//Si le pompier est disponible ou au travail
			//On fait un test pour savoir si le pompier devient indisponible
			elseif($nouvelleDispo == 0 )
			{
				//Si le pompier devient indisponible
				//On supprime la ligne où le pompier était indiqué comme disponible
				$req="DELETE
							FROM activite
							WHERE aCis = $cis
							AND aPompier = $pompier
							AND aDateGarde = '$jour'
							AND aTranche = $tranche;";
			}
			else {
				//Si le pompier ne devient pas indisponible
				//On modifie sa nouvelle disponibilite à la place  de l'ancienne
				$req="UPDATE
				 			SET aDisponibilite = $nouvelleDispo
							WHERE aCis = $cis
							AND aPompier = $pompier
							AND aDateGarde = '$jour'
							AND aTranche = $tranche;";
			}
			$rs = PdoBD::$monPdo->exec($req);
			if ($rs === false) {
				afficherErreurSQL("Probleme lors de la mise à jour de lactivité dans la base de donn&eacute;es.<br> ancienne = ".$ancienneDispo." <br> nouvelle = ".$nouvelleDispo."<br> tranche = ".$tranche."<br> jour = ".$jour, $req, PdoBD::$monPdo->errorInfo());
			}
		}
	}

/**
 * Met à jour la garde dun pompier sur une tranche
*/
	public function majGarde($jour, $tranche, $pompier, $ancienneGarde, $nouvelleGarde)
	{
		//  $cis = $_SESSION['cis'];
	// // 	$Garde= !$Garde;
	// // 	$req = " UPDATE garde SET
	// // 		gDate= $jour,
	// // 		gTranche= $tranche,
	// // 		gNbPompiers= $pompier
	// // 		where gCis= $cis and gTranche = $tranche and gDate = $jour
	// // 			;";
	// 	$req = "UPDATE activite
	// 					SET aGarde=".$Garde."
	// 					WHERE aCis=".$_SESSION['cis']."
	// 						AND aPompier=".$pompier."
	// 						AND aDateGarde='".$jour."'
	// 						AND aTranche=".$tranche.";";
	// 	$rs = PdoBD::$monPdo->exec($req);
	// 	if ($rs === false) {
	// 		afficherErreurSQL("Probleme lors de la mise à jour de la garde dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
	// 	}
		$cis = $_SESSION['cis'];
		// $pompier = $_SESSION['idUtilisateur'];

		//On teste l'égalité de la nouvelle disponibilite et de l'ancienne
		if($ancienneGarde != $nouvelleGarde)
		{	//Si le pompier devient indisponible
			//On supprime la ligne où le pompier était indiqué comme disponible
			$req="UPDATE activite
						SET aGarde = $nouvelleGarde
						WHERE aCis = $cis
							AND aPompier = $pompier
							AND aDateGarde = '$jour'
							AND aTranche = $tranche;";
			$rs = PdoBD::$monPdo->exec($req);
			if ($rs === false) {
				afficherErreurSQL("Probleme lors de la mise à jour de lactivité dans la base de donn&eacute;es.<br> ancienne = ".$ancienneGarde." <br> nouvelle = ".$nouvelleGarde."<br> tranche = ".$tranche."<br> jour = ".$jour, $req, PdoBD::$monPdo->errorInfo());
			}
		}
	}
/**
	* Met à jour une ligne de la table pompier
*/
	 public function majPompier($cp,$mail,$nom,$prenom,$statut,$login,$adresse,$ville,$Bip,$grade,$commentaire,$Id,$cis)
	{
		$req = "UPDATE pompier SET
										pCp= '$cp',
										pMail= '$mail',
										pNom= '$nom',
										pPrenom= '$prenom',
										pStatut= '$statut',
										pLogin= '$login',
										pAdresse= '$adresse',
										pVille='$ville',
										pBip= '$Bip',
									  pGrade= '$grade',
										pCommentaire= '$commentaire'
						WHERE pId = $Id and pCis= $cis;";
						echo $req;
		// $rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la mise à jour du pompier dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}

/**
	* supprime une ligne de la table pompier
*/
	public function supprimePompier($cis, $valeur)
	{
		$req = "DELETE
				FROM pompier
				WHERE  pCis=$cis AND pId=$valeur;";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la suppression du pompier dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}

/**
 * ajoute une ligne dans la table pompier
*/
	public function ajoutPompier($cis,$valeur,$nom,$prenom,$statut,$mail,$login,$mdp,$grade,$adresse,$cp,$ville,$tel,$commentaire)
	{
		$req = "INSERT INTO pompier
				(pCis,pId,pNom,pPrenom,pStatut,pMail,pLogin,pMdp,pGrade,pAdresse,pCp,pVille,pBip,pCommentaire,pDateEnreg,pDateModif)
				VALUES
					($cis, $valeur, $nom, $prenom, $statut, $mail, $login, $mdp, $grade, $adresse, $cp, $ville, $tel,$commentaire, NOW(), NOW());";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de linsertion du pompier dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}

/**
 * Retourne les informations des gardes dun pompier (ou des pompiers) sous la forme dun tableau associatif
*/
	public function getInfosGardes($pompier)
	{
		$req = "SELECT aPompier, DATE_FORMAT(aDateGarde,'%d/%m/%Y') as wDate, aTranche, pLibelle as tLibelle
				FROM  activite INNER JOIN parametre ON pType='tranche' AND aTranche= pIndice
				WHERE aCis=".$_SESSION['cis'];
		if ($pompier!="*") {
		$req .= " AND aPompier=".$pompier;}
		$req .= " AND aGarde=True
				ORDER BY aPompier, aDateGarde DESC, aTranche ASC;";

		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des gardes dun pompier...", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

/**
 * Retourne les informations des disponibilites hebdomadaires dun pompier sous la forme dun tableau associatif
*/
	public function getDisposHebdo($pompier,$semaine,$annee)
	{
		$sem=intval($semaine)-1;
		$premierJour = strtotime("+$sem weeks",mktime(0,0,0,1,1,$annee));
		if (date('w',$premierJour) != 1){$premierJour = strtotime("last monday",$premierJour);}
		$debut=date('Y/m/d',$premierJour);
		$fin=date('Y/m/d',strtotime("6 days",$premierJour));

		$req = "SELECT pId, pNom, pPrenom, DATE_FORMAT(aDateGarde,'%d/%m/%Y') as wDate, WEEKDAY(aDateGarde) as wJour, aTranche, aDisponibilite, aGarde
				FROM (activite INNER JOIN parametre t ON t.pType='tranche'AND aTranche=t.pIndice
				INNER JOIN parametre d ON d.pType='dispo' AND aDisponibilite=d.pIndice)
				RIGHT OUTER JOIN pompier ON aCis=pCis AND aPompier=pId
				WHERE aCis=".$_SESSION['cis']." AND aPompier='".$pompier."' AND aDateGarde BETWEEN '".$debut."' AND '".$fin."'
				AND aDisponibilite>0
				ORDER BY aPompier, aDateGarde, aTranche ASC;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des gardes dun pompier...", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		/*construction du tableau*/
		$n=0;
		$lesDispos = array(array_fill(0, 28, "0"),
		             array_fill(0, 28, "0"));
		foreach ($lesLignes as $uneligne) {
			$lesDispos[0] [$uneligne['wJour'] * 4 + $uneligne['aTranche'] - 1] = $uneligne['aDisponibilite'];
			$lesDispos[1] [$uneligne['wJour'] * 4 + $uneligne['aTranche'] - 1] = $uneligne['aGarde'];
		}
		return $lesDispos;
	}

/**
 * Retourne dans un tableau associatif les informations de la table tranche
*/
	public function getLesTranches()
	{
		$req = "SELECT pIndice as tId, pLibelle as tLibelle
				FROM parametre WHERE pType=tranche
				ORDER by 1;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la recherche des tranches dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

/**
	 * Retourne les informations de la table typeParametre
	*/
	public function getLesParametres()
	{
		$req = "SELECT tpId, tpLibelle, tpBooleen, tpChoixMultiple
					FROM typeParametre
					ORDER BY tpLibelle;";
		$rs = PdoBD::$monPdo->query($req);
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

/**
 * Retourne dans un tableau associatif les informations de la table PARAMETRE (pour un type particulier)
*/
	public function getParametre($type)
	{
		$req = "SELECT pIndice, pLibelle, pValeur, pPlancher, pPlafond
				FROM parametre
				WHERE pType='$type'
				ORDER by pIndice;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {afficherErreurSQL("Probleme lors de la recherche des parametres ".$type." dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

	/**
	 * Retourne dans un tableau associatifles informations de la table PARAMETRE (pour un type particulier)
	*/
	public function getInfosParam($type, $valeur)
	{
		if ($valeur=="NULL")
		{
			$req = "SELECT pType, max(pIndice)+1 AS pIndice,   AS pLibelle, tpLibelle
							FROM parametre INNER JOIN typeParametre ON typeParametre.tpId=parametre.pType
							WHERE pType=$type;";
		}
		else
		{
			$req = "SELECT pType, pIndice, pLibelle, tpLibelle, pPlancher, pPlafond
					 		FROM parametre INNER JOIN typeParametre ON typeParametre.tpId=parametre.pType
					 		WHERE pType=$type
					 		AND pIndice=$valeur;";
		}
		$rs = PdoBD::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Met a jour une ligne de la table PARAMETRE
*/
	public function majParametre($type, $valeur, $libelle, $plancher, $plafond)
	{
		$req = "UPDATE parametre SET pLibelle=$libelle, pPlancher=$plancher, pPlafond=$plafond
					WHERE pType=$type
					AND pIndice=$valeur;";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la modification dun parametre dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}

/**
 * supprime une ligne de la table PARAMETRE
*/
	public function supprimeParametre($type, $valeur)
	{
		$req = "DELETE
					FROM parametre
					WHERE pType=$type
					AND pIndice=$valeur;";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la suppression dun parametre dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}

/**
 * ajoute une ligne dans la table PARAMETRE
*/
	public function ajoutParametre($type, $valeur, $libelle, $plancher, $plafond)
	{
		$req = "INSERT INTO parametre
					(pType, pIndice, pLibelle, pPlancher, pPlafond)
					VALUES ($type, $valeur, $libelle, $plancher, $plafond);";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de linsertion dun parametre dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());
		}
	}


/**
 * envoyer un message electronique
*/
	public function envoyerMail($mail, $sujet, $msg, $entete)
	{
		if (mail($mail, $sujet, $msg, null)==false)
		{
			echo 'Suite à un problème technique, votre message n a pas été envoyé a' .$mail. sujet.$sujet.message .$msg. entete .$entete;
		}
	}

/**
 * Retourne les informations dune intervention
 */
	public function getInfosIntervention($intervention)
	{
		$req = "SELECT iCis, iId, iLieu, iDescription, iDate , iTranche, iHeureDebut, iHeureFin
					FROM intervention
					WHERE iId=".$intervention.";";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false)
		{
			afficherErreurSQL("Probleme lors de la lecture de lintervention ...", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}
	public function ajoutInter($cis, $lieu, $descr, $date, $tranche)
	{
		$req = "INSERT INTO intervention(iCis, iLieu, iDescription, iDate, iTranche)
		VALUE($cis, '$lieu', '$descr', '$date', $tranche);";
		$rs = PdoBD::$monPdo->query($req);

		$req = "SELECT MAX(iId) FROM intervention WHERE iCis=$cis AND iDate='$date'";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false)
		{
			afficherErreurSQL("Probleme lors de la recuperation de l'id de l'intervention", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetch();
		return $lesLignes;
	}

/**
 * Retourne les informations de toutes les interventions dune caserne
 */
	public function getLesInterventions($cis=0, $date='2018-09-10', $enCours=false)
	{
		$req = "SELECT * FROM `intervention` WHERE iCis=$cis";
		if(!is_null($date) && $date !='')
		{
			$req = $req." AND iDate = '$date'";
		}
		if ($enCours) {
			$req = $req." AND iHeureFin IS NULL";
		}
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {afficherErreurSQL("Probleme lors de la lecture des interventions de la caserne ...", $req, PdoBD::$monPdo->errorInfo());}
		$lesLignes = $rs->fetchAll();
		// echo $req;
		return $lesLignes;
	}

	/**
	* Met fin a une intervention
	**/
	public function finirInter($cis, $id)
	{
		$req = "UPDATE intervention
						SET iHeureFin = NOW()
						WHERE intervention.iCis = $cis AND intervention.iId = $id";
		$rs = PdoBD::$monPdo->query($req);
		return $rs;
	}

	/**
 * Retourne les participants à une intervention
 */
	public function getLesParticipants($cis, $intervention)
	{
 		$req = "SELECT eCis, ePompier, pNom, pPrenom, aDisponibilite
						from equipe
							inner join pompier on pompier.pId = equipe.ePompier
							inner join intervention on equipe.eIntervention = intervention.iId
							inner join activite on pId = aPompier and pCis = aCis and activite.aTranche=intervention.iTranche and DATE_FORMAT(iDate,'%Y-%m-%d')= DATE_FORMAT(aDateGarde, '%Y-%m-%d')
						where eIntervention = $intervention
							and pCis=$cis";

		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("Probleme lors de la lecture des participants ..", $req, PdoBD::$monPdo->errorInfo());
		}
 		$lesLignes = $rs->fetchAll();
 		return $lesLignes;
	}

	public function getPompierGarde($cis)
	{
		$req='SELECT * FROM pompier
					inner join activite on aPompier = pId
					WHERE aDateGarde= date("Y-m-d") and pCis ='.$_SESSION['pCis'];

		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false)
		{
			afficherErreurSQL("Probleme lors de la lecture des participants ..", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignes = $rs->fetchAll();
		return $lesLignes;
	}

	public function ajoutEquipe($cis, $id, $numInter)
	{
		$req = "INSERT INTO equipe(eCis, ePompier, eIntervention)
		VALUE($cis, $id,$numInter);";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			return false;
		}
		else {
			return true;
		}
	}
}


?>
