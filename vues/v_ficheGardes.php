<!-- affichage de la feuille de gardes / Dernière modification le 18 octobre 2016 par Pascal BLAIN -->
<div id="contenu">
	<div style='display: block;' class='unOnglet' id='contenuOnglet1'>
		<fieldset><legend>Saisie des gardes</legend>
		<form name="frmDispos" action="index.php?choixTraitement=gardes&action=voir" method="post">

			<input type="hidden" maxlength="2" name="zSemaine"	value='<?php echo $semaine;?>'>
			<input type="hidden" maxlength="2" name="zAnnee" 	value='<?php echo $annee;?>'>
		</form>
			<table id="tableau" class="tableau">
				<tbody>
					<tr>
						<th><input id="sPrecedente" name="gauche" title="semaine précédente" src="images/gauche.gif" onclick="autreSemaine('<?php echo date('W',strtotime("-7 days",$premierJour))."', '".date('Y',strtotime("-7 days",$premierJour))?>')"type="image"></th>

						<th colspan="26">
							<b><big>Semaine <?php echo $semaine." : du lundi ".date('d/m/Y',$premierJour)." au dimanche ".date('d/m/Y',strtotime("6 days",$premierJour))."</big></b>";?>

						<th><input id="sSuivante" name="droite" title="semaine suivante" src="images/droite.gif" onclick="autreSemaine('<?php echo date('W',strtotime("+7 days",$premierJour))."', '".date('Y',strtotime("+7 days",$premierJour));?>')" onmouseover="document.droite.src='images/droite_.gif'" onmouseout="document.droite.src='images/droite.gif'"type="image">
				</tbody>
			</table>
			<form name="disponibilite" action="index.php?choixTraitement=gardes&action=majGarde" method="post">
				<input type="hidden" name="zSemaine" value='<?php echo $semaine;?>'>
				<input type="hidden" name="zAnnee" value='<?php echo $annee;?>'>
				<input type="hidden" name="zPremierJour" value="<?php echo $premierJour;?>">
				<table id="tableDispo" class="tableau">
					<tr>
						<td>
						<?php
						$nomJour = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
						for($jour=0; $jour<= 6; $jour++)
						{
						echo ('
						<th colspan="4" class="jourSemaine">'.$nomJour[$jour].' '.date('d/m',strtotime('+'.$jour.' day',$premierJour)).'</th>');
					}
					?>
					<tr>
						<td>
					<?php
						for($jour = 0; $jour < 7; $jour++)
						{
							for($tranche = 1; $tranche < 5; $tranche++){
								echo '
						<th class="semaine">'.$tranche;
							}
						}
					foreach ($lesPompiers as $unPompier) {
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
						echo '<tr>
						<th>'.$unPompier['pNom'].'&nbsp;'.$unPompier['pPrenom'];
						for ($jour = 0; $jour < 7; $jour++) {
							for ($tranche = 1; $tranche < 5; $tranche++) {
								echo "
						<th class='garde'>
							<input class='numDispo' type='text'  value='".$lesDisposPompiers[$unPompier['pId']][0][$jour*4+$tranche-1]."' disable max-length='1' autocomplete='off'>
							<input class='etatGarde' type='checkbox' value='1' name='".$unPompier['pId']."-".$jour."-".$tranche."' ";
								if ($lesDisposPompiers[$unPompier['pId']][1][$jour*4+$tranche-1] == 1) {
									echo "checked";
								}
								if ($lesDisposPompiers[$unPompier['pId']][0][$jour*4+$tranche-1] == 0) {
									echo "disabled";
								}
								echo ">
								";
							}
						}
					}
					?>
			</table>
			<input type="submit" value="enregistrer">
		</form>
		</fieldset>
	</div>
