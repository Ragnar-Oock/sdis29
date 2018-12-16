<?php
session_start();

require_once 'include/fct.inc';
require_once 'include/class.pdo.php';
// require_once '../parametres_connexion/param.php';

$pdo = PdoBD::getPdoBD();
$estConnecte = estConnecte();

// on vérifie que le pompier est authentifié
if(!isset($_REQUEST['choixTraitement']) || !$estConnecte){$_REQUEST['choixTraitement'] = 'connexion';}

// on analyse le cas d'utilisation en cours ...
$choixTraitement= $_REQUEST['choixTraitement'];
switch($choixTraitement)
{
	case 'connexion':
	{
		include("controleurs/c_connexion.php");
		break;
	}
	case 'parametres':
	{
		include("controleurs/c_param.php");
		break;
	}
	case 'gardes':
	{
		include("controleurs/c_gardes.php");
		break;
	}
	case 'interventions':
	{
		include("controleurs/c_interventions.php");
		break;
	}
	case 'pompiers':
	{
		include("controleurs/c_pompiers.php");
		break;
	}
	case 'mentions':
	{
		include("controleurs/v_mentionslegales.html");
		break;
	}
	default:
	{
		echo 'erreur d\'aiguillage !'.$uc;
		break;
	}
}
include("vues/v_pied.php") ;
?>
