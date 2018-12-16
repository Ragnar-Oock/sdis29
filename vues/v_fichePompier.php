
<!-- affichage du detail de la fiche pompier / Derniere modification le 11/10/2018 à 22h31 par Pascal BLAIN -->

<?php

if (isset($_SESSION['idUtilisateur']))
{
// echo '
//  	<div id="fiche">
// 		<ul class="lesOnglets">
// 			<li class="actif onglet" 	id="onglet1" onclick="javascript:Affiche(\'1\',3);">Mes disponibilit&eacute;s</li>
// 			<li class="inactif onglet" 	id="onglet2" onclick="javascript:Affiche(\'2\',3);">Historique des gardes</li>
// 			<li class="inactif onglet" 	id="onglet3" onclick="javascript:Affiche(\'3\',3);">Mon profil</li>
// 		</ul>';

  echo '<input class="tabs" id="Dispo" type="radio" name="tabs"';
  if (isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 1 || $_REQUEST['tab'] == null) || !isset($_REQUEST['tab'])) {
    echo "checked";
  }
  echo '>
  <label class="labelTab" for="Dispo"><span>Mes disponibilités</span></label>

  <input class="tabs" id="Histo" type="radio" name="tabs">
  <label class="labelTab" for="Histo"><span>Historique des gardes</span></label>

  <input class="tabs" id="Profil" type="radio" name="tabs"';
  if(isset($_REQUEST['tab']) && $_REQUEST['tab']==3){
    echo "checked";
  }
  echo '>
  <label class="labelTab" for="Profil"><span>Mon Profil</span></label>

  <section class="tabs" id="section1">';

/*================================================================================================== DISPONIBILITEES (1) */
 ?>
		<!-- <div style='display: block;' class='unOnglet' id='contenuOnglet1'> -->
			<fieldset><legend>Saisie des disponibilités</legend>
  		<form name="frmDispos" action="index.php?choixTraitement=pompiers&action=voir" method="post">
        <input type="hidden" name="lstPompiers" value="<?php echo $choix; ?>">
        <input type="hidden" name="choix" value="<?php echo $choix; ?>">
  			<input type="hidden" maxlength="2" name="zSemaine"	value='<?php echo $semaine;?>'>
  			<input type="hidden" maxlength="2" name="zAnnee" 	value='<?php echo $annee;?>'>
  		</form>
    		<table id="tableau" class="tableau">
    			<tbody>
      			<tr>
      				<th><input id="sPrecedente" name="gauche" title="semaine précédente" src="images/gauche.gif" onclick="autreSemaine('<?php echo date('W',strtotime("-7 days",$premierJour))."', '".date('Y',strtotime("-7 days",$premierJour))?>')"type="image"></th>

      				<th colspan="26">
                <b><big>Semaine <?php echo $semaine." : du lundi ".date('d/m/Y',$premierJour)." au dimanche ".date('d/m/Y',strtotime("6 days",$premierJour))."</big></b>";?>

      				<th><input id="sSuivante" name="droite" title="semaine suivante" src="images/droite.gif" onclick="autreSemaine('<?php echo date('W',strtotime("+7 days",$premierJour))."', '".date('Y',strtotime("+7 days",$premierJour));?>')" onmouseover="document.droite.src='images/droite_.gif'" onmouseout="document.droite.src='images/droite.gif'"type="image"></th>
      			</tr>
          </tbody>
        </table>
        <form name="disponibilite" action="index.php?choixTraitement=pompiers&action=majActivite" method="post">
          <input type="hidden" name="zSemaine" value='<?php echo $semaine;?>'>
    			<input type="hidden" name="zAnnee" value='<?php echo $annee;?>'>
          <input type="hidden" name="zPremierJour" value="<?php echo $premierJour;?>">
        <table id="tableDispo" class="tableau">
      			<tr>
      			<?php
      			$nomJour = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
      			for($jour=0; $jour<= 6; $jour++)
      			{
      			  echo ('<th colspan="4" class="jourSemaine">'.$nomJour[$jour].' '.date('d/m',strtotime('+'.$jour.' day',$premierJour)).'</th>');
      			}
      			?>
      			</tr>
      			<tr>
      			<?php
      			for($jour=0; $jour< 7; $jour++)
      			{
              for($tranche=1; $tranche <5; $tranche++){
                echo '<th class="semaine">'.$tranche.'</th>
                ';
              }
      			}
      			?>
            </tr>
      			<tr>
              <input type="hidden" name="Semaine" value="">
      			<?php
      			$lePompier	= $choix;
      			// for ($i=0; $i < 28; $i++) {
            //   // if ($lesDispos[0][$i]==0) {
            //   //   echo "
            //   //   <th class='indisponible'>";
            //   // }
            //   // if ($lesDispos[0][$i]==1) {
            //   //   echo "
            //   //   <th class='disponibilite'>";
            //   // }
            //   // if ($lesDispos[0][$i]==2) {
            //   //   echo "
            //   //   <th class='travail'>";
            //   // }
            //   echo "<th class='dispo'><input class='numDispo' type='text' name='$i' value='".$lesDispos[0][$i]."' disable max-length='1'>";
            // }
            echo '<input type="hidden" name="Semaine" value="'.$semaine.'">';
            for ($jour=0; $jour < 7; $jour++) {
              for ($tranche=1; $tranche < 5; $tranche++) {
                echo "<th class='dispo'><input class='numDispo' type='text' name='".$jour."-".$tranche."' value='".$lesDispos[0][$jour*4+$tranche-1]."' disable max-length='1' autocomplete='off'>
                ";
              }
            }
      			?>
      			</tr>
      	</table>
        <ul class="legende">
          <li id="indispo">
            <input id="radIndispo" type="radio" name="radLegend" selected>
            <label for="radIndispo">Indisponible</label>
          <li id="dispo">
            <input id="radDispo" type="radio" name="radLegend">
            <label for="radDispo">Disponible</label>
          <li id="travail">
            <input id="radTravail" type="radio" name="radLegend">
            <label for="radTravail">Travail</label>
        </ul>
        <input type="submit" value="enregistrer">
      </form>
		<!--/div-->
			</fieldset>
		<!-- </div> -->
  </section>
<?php
/*================================================================================================== GARDES (2)*/
// echo ("
// 		<div style='display: none;' class='unOnglet' id='contenuOnglet2'>
// 		<fieldset><legend>Gardes r&eacute;alis&eacute;es");
echo '<section class="tabs" id="section2">';

	if (count($lesGardes)==0) {echo "<i>(aucune garde enregistrée)</i></legend>";} else
{
	echo ("<i>(".count($lesGardes)." gardes)</i></legend>
			<table style='border: 0px solid white;'>
				<tr><th class='controleLong'>Date de la garde</th>");
	// foreach ($lesTranches as $uneLigne)	{ echo ("<th>".$uneLigne['tLibelle']."</th>");}
	$dateGarde="premiere";
	$colonne=1;
	echo "</tr>";
	foreach ($lesGardes as $uneLigne)
	{
		if ($dateGarde != $uneLigne['wDate'])
		{
			if ($dateGarde !=  "premiere")
			{
			while ($colonne<=count($lesTranches)) {echo "<td class='controle' style='text-align : center;'>&nbsp;</td>"; $colonne++;}
			echo "</tr>
			";
			}
			echo "<tr><td class='controle' style='text-align : center;'>".$uneLigne['wDate']."</td>";
			$dateGarde = $uneLigne['wDate'];
			$colonne=1;
		}
		while ($colonne<$uneLigne['aTranche']) {echo "<td class='controle' style='text-align : center;'>&nbsp;</td>"; $colonne++;}
		echo ("<td class='controle' style='text-align : center;background-color : lime;'>&nbsp;</td>");
		$colonne=$uneLigne['aTranche']+1;
	}
	while ($colonne<=count($lesTranches)) {echo "<td class='controle' style='text-align : center;'>&nbsp;</td>"; $colonne++;}
	echo "</tr>";
	echo ("</table>");
}
echo ("
		</fieldset>
		<!--/div-->
    </section>");

/*================================================================================================== COORDONNEES (3) */

  echo "<section class='tabs' id='section3'>";
  // Pour les chefs de centres
  if ($_SESSION['statut']==2)
  {
    echo ("
   	 	<!--<div style='display: none;' class='unOnglet' id='contenuOnglet3'>-->
    			<table style='border: 0px solid white;'>
   			<tr>
   				<td style='border :0px;'>
   				<!--<fieldset><h1>Coordonn&eacute;es</h1>
   					<table>
   						<tr><th style='width:130px;'>Nom</th>		<td style='width:130px;'>".$lesInfosPompier['nom']."</td> </tr>
   						<tr><th>Pr&eacute;nom</th>					<td>".$lesInfosPompier['prenom']."</td> </tr>
   						<tr><th>Adresse</th>						<td>".$lesInfosPompier['pAdresse']."</td> </tr>
   						<tr><th>Code postal</th>					<td>".$lesInfosPompier['pCp']."</td> </tr>
   						<tr><th>Ville</th>							<td>".$lesInfosPompier['pVille']."</td> </tr>
   						<tr><th>T&eacute;l&eacute;phone</th>		<td>".$lesInfosPompier['pBip']."</td> </tr>
   						<tr><th>Adresse &eacute;lectronique</th>	<td>".$lesInfosPompier['pMail']."</td> </tr>
   						<tr><th>Nom de compte</th>					<td>".$lesInfosPompier['pLogin']."</td></tr>
   						<tr><th>&nbsp;</th>							<td>&nbsp;</td> </tr>
   						<br /-->
               <form name='frmCoordonnees' action='index.php?choixTraitement=pompiers&action=validerModifier' method='post'>
               <fieldset>
                 <h1>Coordonnées</h1>
                 <table>
                 <tr><th>Nom</th>          <td><input type='text' name='pNom' class='form-controlCor'  placeholder='Nom' value ='".$lesInfosPompier['nom']."'  required /></td>
                 <tr><th>Prenom</th>       <td><input type='text' name='pPrenom' class='form-controlCor' placeholder='Prénom' value = '".$lesInfosPompier['prenom']."' required /></td>
                 <tr><th>Adresse</th>      <td><input type='text' name='pAdresse' class='form-controlCor'  placeholder='Adresse' value ='".$lesInfosPompier['pAdresse']."'  /></td>
                 <tr><th>Code Postal</th>  <td><input type='text' name='pCp' class='form-controlCor' placeholder='Code postale' value = '".$lesInfosPompier['pCp']."' required/></td>
                 <tr><th>Ville</th>        <td><input type='text' name='pVille' class='form-controlCor'  placeholder='Ville' value ='".$lesInfosPompier['pVille']."'  required/></td>
                 <tr><th>Mail</th>         <td><input type='text' name='pMail' class='form-controlCor' placeholder='Mail' value = '".$lesInfosPompier['pMail']."' required/></td>
                 <tr><th>Login</th>        <td><input type='text' name='pLogin' class='form-controlCor'  placeholder='Nom de compte' value ='".$lesInfosPompier['pLogin']."'  required disabled/></td>
                 <tr><th>Bip</th>          <td><input type='text' name='pBip' class='form-controlCor'  placeholder='Numéro Bip' value ='".$lesInfosPompier['pBip']."'  required disabled/></td>
   			        </table>
   				    </fieldset>

   				<fieldset><h1>Centre d'Incendie et de Secours</h1>
   					<table>
   						<tr><th style='width:130px;'>Code</th>		<td><input type='text' name='cCode' class='form-controlCor' placeholder='IdentifiantCaserne' value ='".$lesInfosPompier['pCis']."'  required disabled/></td> </tr>
   						<tr><th>Nom</th>							<td><input type='text' name='cNom' class='form-controlCor' placeholder='NomCaserne' value ='".$lesInfosPompier['cNom']."'  required disabled/></td> </tr>
   						<tr><th>Adresse</th>						<td><input type='text' name='cAdresse' class='form-controlCor' placeholder='AdresseCaserne' value ='".$lesInfosPompier['cAdresse']."'  required disabled/></td> </tr>
   						<tr><th>T&eacute;l&eacute;phone</th>		<td><input type='text' name='cTel' class='form-controlCor' placeholder='Telephone' value ='".$lesInfosPompier['cTel']."'  required disabled/></td> </tr>
   						<tr><th>Groupement</th>						<td><input type='text' name='cGroupement' class='form-controlCor' placeholder='Groupement' value ='".$lesInfosPompier['cGroupement']."'  required disabled/></td> </tr>
   					</table>
   				</fieldset>
   				<fieldset><h1>Fonction</h1>
   					<table>
   						<tr>
                <th>Type
                <td><select name='lstType' class='form-controlCor' style='width:200px;' value ='".$lesInfosPompier['wType']."'>");

	            if (!isset($_REQUEST['lstType']))
							{
								$choix = $lesInfosPompier['pType'];
							}
							 else
							{
								$choix = $_REQUEST['lstType'];
							}
	            foreach ($lesTypes as $unType)
							{
								if($unType['pIndice'] == $choix)
								{
									echo "<option selected value=\"".$unType['pIndice']."\">".$unType['pLibelle']."</option>\n	";
									$choix = $unType['pIndice'];
								}
								else
								{
									echo "<option value=\"".$unType['pIndice']."\">".$unType['pLibelle']."</option>\n		";
								}
							}
              echo "</select>";

              echo("
   						<tr>
                <th>Grade
                <td><select name='lstGrade' class='form-controlCor'  style='width:200px;' value ='".$lesInfosPompier['wGrade' ]."'>");

              if (!isset($_REQUEST['lstGrade']))
							{
								$choix = $lesInfosPompier['pGrade'];
							}
							 else
							{
								$choix = $_REQUEST['lstGrade'];
							}
	            foreach ($lesGrades as $unGrade)
							{
								if($unGrade['pIndice'] == $choix)
								{
									echo "<option selected value=\"".$unGrade['pIndice']."\">".$unGrade['pLibelle']."</option>\n	";
									$choix = $unGrade['pIndice'];
								}
								else
								{
									echo "<option value=\"".$unGrade['pIndice']."\">".$unGrade['pLibelle']."</option>\n		";
								}
							}
              echo "</select>";
              echo("
   						<tr>
                <th>Statut
                <td><select name='lstStatut' class='form-controlCor' style='width:200px;' value ='".$lesInfosPompier['wStatut']."'>");

                if (!isset($_REQUEST['lstStatut']))
  							{
  								$choix = $lesInfosPompier['pStatut'];
  							}
  							 else
  							{
  								$choix = $_REQUEST['lstStatut'];
  							}
  	            foreach ($lesStatuts as $unStatut)
  							{
  								if($unStatut['pIndice'] == $choix)
  								{
  									echo "<option selected value=\"".$unStatut['pIndice']."\">".$unStatut['pLibelle']."</option>\n	";
  									$choix = $unStatut['pIndice'];
  								}
  								else
  								{
  									echo "<option value=\"".$unStatut['pIndice']."\">".$unStatut['pLibelle']."</option>\n		";
  								}
  							}
            echo "</select>";
            echo ("
            </table>
   				</fieldset></td>
   			</tr>
   			</table>

   			<fieldset><h1>Observations</h1>
   			<table style='border: 0px solid white;'>
   				<tr>
   					 <td><textarea rows='3' cols='90' value='' name='pObs'></textarea></td>
   				</tr>
   			 </table>
   			</fieldset>
         <input type='hidden' value=".$lesInfosPompier['id']." name='pId'>
         <input type='hidden' value='OK' name='zOk'>
           <input type='submit' value='Valider'>
       </form>
   		</div>");
  }

  // Pour les pompiers
  if ($_SESSION['statut']==1 || $_SESSION['statut']==3)
  {
    echo ("
	 	<!--div style='display: none;' class='unOnglet' id='contenuOnglet3'-->
 			<table style='border: 0px solid white;'>
			<tr>
				<td style='border :0px;'>
				<!--<fieldset><h1>Coordonn&eacute;es</h1>
					<table>
						<tr><th style='width:130px;'>Nom</th>		<td style='width:130px;'>".$lesInfosPompier['nom']."</td> </tr>
						<tr><th>Pr&eacute;nom</th>					<td>".$lesInfosPompier['prenom']."</td> </tr>
						<tr><th>Adresse</th>						<td>".$lesInfosPompier['pAdresse']."</td> </tr>
						<tr><th>Code postal</th>					<td>".$lesInfosPompier['pCp']."</td> </tr>
						<tr><th>Ville</th>							<td>".$lesInfosPompier['pVille']."</td> </tr>
						<tr><th>T&eacute;l&eacute;phone</th>		<td>".$lesInfosPompier['pBip']."</td> </tr>
						<tr><th>Adresse &eacute;lectronique</th>	<td>".$lesInfosPompier['pMail']."</td> </tr>
						<tr><th>Nom de compte</th>					<td>".$lesInfosPompier['pLogin']."</td></tr>
						<tr><th>&nbsp;</th>							<td>&nbsp;</td> </tr>
						<br /-->
            <form name='frmCoordonnees' action='index.php?choixTraitement=pompiers&action=validerModifier' method='post'>
            <fieldset>
              <h1>Coordonnées</h1>
              <table>
              <tr><th>Nom</th>          <td><input type='text' name='pNom' class='form-controlCor'  placeholder='Nom' value ='".$lesInfosPompier['nom']."'  required /></td>
              <tr><th>Prenom</th>       <td><input type='text' name='pPrenom' class='form-controlCor' placeholder='Prénom' value = '".$lesInfosPompier['prenom']."' required /></td>
              <tr><th>Adresse</th>      <td><input type='text' name='pAdresse' class='form-controlCor'  placeholder='Adresse' value ='".$lesInfosPompier['pAdresse']."'  /></td>
              <tr><th>Code Postal</th>  <td><input type='text' name='pCp' class='form-controlCor' placeholder='Code postale' value = '".$lesInfosPompier['pCp']."' required/></td>
              <tr><th>Ville</th>        <td><input type='text' name='pVille' class='form-controlCor'  placeholder='Ville' value ='".$lesInfosPompier['pVille']."'  required/></td>
              <tr><th>Mail</th>         <td><input type='text' name='pMail' class='form-controlCor' placeholder='Mail' value = '".$lesInfosPompier['pMail']."' required/></td>
              <tr><th>Login</th>        <td><input type='text' name='pLogin' class='form-controlCor'  placeholder='Nom de compte' value ='".$lesInfosPompier['pLogin']."'  required disabled/></td>
              <tr><th>Bip</th>          <td><input type='text' name='pBip' class='form-controlCor'  placeholder='Numéro Bip' value ='".$lesInfosPompier['pBip']."'  required disabled/></td>
			        </table>
				    </fieldset>

				<fieldset><h1>Centre d'Incendie et de Secours</h1>
					<table>
						<tr><th style='width:130px;'>Code</th>		<td><input type='text' name='cCode' class='form-controlCor' placeholder='IdentifiantCaserne' value ='".$lesInfosPompier['pCis']."'  required disabled/></td> </tr>
						<tr><th>Nom</th>							<td><input type='text' name='cNom' class='form-controlCor' placeholder='NomCaserne' value ='".$lesInfosPompier['cNom']."'  required disabled/></td> </tr>
						<tr><th>Adresse</th>						<td><input type='text' name='cAdresse' class='form-controlCor' placeholder='AdresseCaserne' value ='".$lesInfosPompier['cAdresse']."'  required disabled/></td> </tr>
						<tr><th>T&eacute;l&eacute;phone</th>		<td><input type='text' name='cTel' class='form-controlCor' placeholder='Telephone' value ='".$lesInfosPompier['cTel']."'  required disabled/></td> </tr>
						<tr><th>Groupement</th>						<td><input type='text' name='cGroupement' class='form-controlCor' placeholder='Groupement' value ='".$lesInfosPompier['cGroupement']."'  required disabled/></td> </tr>
					</table>
				</fieldset>
				<fieldset><h1>Fonction</h1>
					<table>
						<tr><th>Type</th>							<td><input type='text' name='wType' class='form-controlCor' placeholder='Type' value ='".$lesInfosPompier['wType']."'  required disabled/></td> </tr>
						<tr><th>Grade</th>							<td><input type='text' name='wGrade' class='form-controlCor' placeholder='wGrade' value ='".$lesInfosPompier['wGrade']."'  required disabled/></td> </tr>
						<tr><th>Statut</th>							<td><input type='text' name='wStatut' class='form-controlCor' placeholder='wStatut' value ='".$lesInfosPompier['wStatut']."'  required disabled/></td> </tr>					</table>
				</fieldset></td>
			</tr>
			</table>

			<fieldset><h1>Observations</h1>
			<table style='border: 0px solid white;'>
				<tr>
					 <td><textarea rows='3' cols='90' value='' name='pObs'></textarea></td>
				</tr>
			 </table>
			</fieldset>
      <input type='hidden' value=".$lesInfosPompier['id']." name='pId'>
      <input type='hidden' value='OK' name='zOk'>
        <input type='submit' value='Valider'>
    </form>
    ");
  }
  echo "</section>";
}

/*================================================================================================== Onglet X */

// echo ("
// 		<div style='display: none;' class='unOnglet' id='contenuOngletX'>
// 			<fieldset><legend>XXXX</legend>
// 			<table>
// 				<tr><th style='width:130px;'>.....</th></tr>
// 				<tr><td>xxxx</td></tr>
// 			</table>
// 			</fieldset>
// 		</div>
//
// 	</div>
// </div>");
?>
