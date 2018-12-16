<!-- Derniere modification le 16 octobre 2018 par Pascal Blain -->
<div id="contenu">
<?php
 	if ($_REQUEST['action']=="supprimer")
		{ 	echo '<h2>SUPPRESSION DU POMPIER '.$lesInfosPompier['nom'].' '.$lesInfosPompier['prenom'].'</h2>';
		 	echo '<form name="frmA" 	action="index.php?choixTraitement=pompiers&action=validerSupprimer&type='.$type.'&agent='.$lesInfosPompier['id'].'" method="post">';}
 	if ($_REQUEST['action']=="modifier")
		{ 	echo '<h2>MODIFICATION DU POMPIER '.$lesInfosPompier['nom'].' '.$lesInfosPompier['prenom'].'</h2>';
			echo '<form name="frmA" 	action="index.php?choixTraitement=pompiers&action=validerModifier&type='.$type.'&agent='.$lesInfosPompier['id'].'" method="post">';}

 	if ($_REQUEST['action']=="ajouter")
		{ 	echo "<h2>AJOUT D'UN NOUVEAU POMPIER</h2>";
      //&type='.$type.''
			echo ("
			<form name='frmA' action='index.php?choixTraitement=pompiers&action=validerAjouter method='post' onsubmit='return valider(this)'>
    ");}?>
      <select name='LstCasernes' onchange='submit()'>
      <?php
      var_dump($lesInfosCasernes);
      var_dump($lesInfosPompier['cGroupement']);
      foreach($lesInfosCasernes as $uneCaserne)
     {
        echo("<option selected value='".$uneCaserne['cGroupement']."'>".$uneCaserne['cNom']."</option>
        ");
      }
      echo("</select>");



  // echo ("
  //   <table style='border: 0px solid white;'>
	// <tr>
	// <td style='border :0px;'>
	// <!--fieldset><legend>Coordonn&eacute;es</legend>
	// 	<table>
  //   <tr><th>Nom du pompier</th>     <td><input type='text' name='pNom' class='form-control'  placeholder='Nom' value ='".$lesInfosPompier['nom']."'  required /></td>
  //   <tr><th>Prénom du pompier</th>  <td><input type='text' name='pPrenom' class='form-control' placeholder='Prénom' value = '".$lesInfosPompier['prenom']."' required /></td>
  //   <tr><th>Adresse du pompier</th> <td><input type='text' name='pAdresse' class='form-control'  placeholder='pAdresse' value ='".$lesInfosPompier['pAdresse']."'  /></td>
  //   <tr><th>Code Postal</th>        <td><input type='text' name='pCp' class='form-control' placeholder='Code postale' value = '".$lesInfosPompier['pCp']."' required/></td>
  //   <tr><th>Ville</th>              <td><input type='text' name='pVille' class='form-control'  placeholder='Ville' value ='".$lesInfosPompier['pVille']."'  required/></td>
  //   <tr><th>Mail</th>                <td><input type='text' name='pMail' class='form-control' placeholder='Mail' value = '".$lesInfosPompier['pMail']."' required/></td>
  //   </table>
  //   </fieldset>
  //   <fieldset><legend>Centre d'incendie de secours</legend>
  //   <table>
  //   <tr><th>Nom</th>							<td><input type='text' name='cNom' class='form-control' placeholder='cNom' value ='".$lesInfosPompier['cNom']."'  required disabled/></td>
  //   <tr><th>Adresse</th>						<td><input type='text' name='cAdresse' class='form-control' placeholder='cAdresse' value ='".$lesInfosPompier['cAdresse']."'  required disabled/></td>
  //   <tr><th>T&eacute;l&eacute;phone</th>		<td><input type='text' name='cTel' class='form-control' placeholder='cTel' value ='".$lesInfosPompier['cTel']."'  required disabled/></td>
  //   <tr><th>Groupement</th>						<td><input type='text' name='cGroupement' class='form-control' placeholder='cGroupement' value ='".$lesInfosPompier['cGroupement']."'  required disabled/>
  //   <tr><th>Type</th>							<td><input type='text' name='wType' class='form-control' placeholder='Type' value ='".$lesInfosPompier['wType']."'  required disabled/></td>
  //   <tr><th>Grade</th>							<td><input type='text' name='wGrade' class='form-control' placeholder='wGrade' value ='".$lesInfosPompier['wGrade']."'  required disabled/></td>
  //   <tr><th>Statut</th>							<td><input type='text' name='wStatut' class='form-control' placeholder='wStatut' value ='".$lesInfosPompier['wStatut']."'  required disabled/></td>
  //   </table>
  // </fieldset>
  //     </table>
  //   ");

$titre="Pr&eacute;nom";
 if ($_REQUEST['action']=="ajouter")  //-------------------------------------------------------- cas suppression
 {	echo ("
      <table style='border: 0px solid white;'>
 	 <tr>
 	 <td style='border :0px;'>
   <fieldset><legend>Coordonn&eacute;es</legend>
     <table>
     <tr><th>Nom du pompier</th>     <td><input type='text' name='pNom' class='form-control'  placeholder='Nom' value ='".$lesInfosPompier['nom']."'  required /></td>
     <tr><th>Prénom du pompier</th>  <td><input type='text' name='pPrenom' class='form-control' placeholder='Prénom' value = '".$lesInfosPompier['prenom']."' required /></td>
     <tr><th>Adresse du pompier</th> <td><input type='text' name='pAdresse' class='form-control'  placeholder='pAdresse' value ='".$lesInfosPompier['pAdresse']."'  /></td>
     <tr><th>Code Postal</th>        <td><input type='text' name='pCp' class='form-control' placeholder='Code postale' value = '".$lesInfosPompier['pCp']."' /></td>
     <tr><th>Ville</th>              <td><input type='text' name='pVille' class='form-control'  placeholder='Ville' value ='".$lesInfosPompier['pVille']."'  /></td>
     <tr><th>Mail</th>                <td><input type='text' name='pMail' class='form-control' placeholder='Mail' value = '".$lesInfosPompier['pMail']."' /></td>
     <tr><th>Type</th>							<td><input type='text' name='wType' class='form-control' placeholder='Type' value ='".$lesInfosPompier['wType']."'/></td>
     <tr><th>Grade</th>							<td><input type='text' name='wGrade' class='form-control' placeholder='wGrade' value ='".$lesInfosPompier['wGrade']."'/></td>
     <tr><th>Statut</th>							<td><input type='text' name='wStatut' class='form-control' placeholder='wStatut' value ='".$lesInfosPompier['wStatut']."'/></td>
     </table>
     </fieldset>

     <fieldset><legend>Centre d'incendie de secours</legend>
     <table>
     <tr>
     <tr><th>Nom</th>							<td><input type='text' name='cNom' class='form-control' placeholder='cNom' value ='".$uneCaserne['cNom']."'   disabled/></td>
     <tr><th>Adresse</th>						<td><input type='text' name='cAdresse' class='form-control' placeholder='cAdresse' value ='".$uneCaserne['cAdresse']."'   disabled/></td>
     <tr><th>T&eacute;l&eacute;phone</th>		<td><input type='text' name='cTel' class='form-control' placeholder='cTel' value ='".$uneCaserne['cTel']."'   disabled/></td>
     <tr><th>Groupement</th>						<td><input type='text' name='cGroupement' class='form-control' placeholder='cGroupement' value ='".$uneCaserne['cGroupement']."'   disabled/>

     </table>
   </fieldset>");
	}
 else	//------------------------------------------------------------------------------------ cas ajout ou modification
	{
	}
echo ("
	</td>
	</tr>
	</fieldset>
	</table>");
?>
            <table style='border: 0px solid white; '>
            	<tr>
                <td style='border: 0px solid white;'>
                	<fieldset><legend>Observations</legend>
                 	<textarea name='ztObs' cols='70' rows='1'><?php echo $lesInfosPompier['agCommentaire'];?></textarea>
                	</fieldset>
                </td>
                <td style='border: 0px solid white; witdh:130px; text-align:right;'>
                	<input type="hidden" 	name="zTypeAdm"		value="<?php if ($type=="adm") {echo ("true");} else {echo ("false");} ?>">
                	<input type="submit" 	name="zOk"	value="OK">
                </td>
                </tr>
            </table>
    </form>
