<?php
  echo '
    <div id="fiche">
      <input class="tabs" id="ajoutInter" type="radio" name="tabs"';
      if (isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 1 || $_REQUEST['tab'] == null) || !isset($_REQUEST['tab'])) {
        echo "checked";
      }
      echo '>
      <label class="labelTab" for="ajoutInter"><span>Ajouter</span></label>

      <input class="tabs" id="voirInter" type="radio" name="tabs"';
      if (isset($_REQUEST['tab']) && $_REQUEST['tab']==2) {
        echo "checked";
      }
      echo '>
      <label class="labelTab" for="voirInter"><span>Consulter</span></label>

      <section class="tabs" id="section1">
        <form class="VueInter" action="index.php?choixTraitement=interventions&action=majInter" id="formInter" method="post">
          <input class="ChampInter" type="text" name="adresse" placeholder="Lieu d\'intervention" value="';
          if (isset($_REQUEST['adresse'])) {
            echo $_REQUEST['adresse'];
          }
          echo '">
          <input class="ChampInter" type="text" name="description" placeholder="Description" value="';
          if (isset($_REQUEST['description'])) {
            echo $_REQUEST['description'];
          }
          echo '">
          <label for="tranche">Tranche : </label>
          <select name="tranche" onchange="submit()">
            <option selected disabled hidden></option>';
            for ($tranche=1; $tranche < 5; $tranche++) {
              if (isset($_REQUEST['tranche']) && $_REQUEST['tranche']==$tranche) {
                  echo '<option selected value="'.$tranche.'">'.$tranche;
              }
              else {
                  echo '<option value="'.$tranche.'">'.$tranche;
              }
            }
            echo '
          </select>
          <label for="aCaserne">Caserne : </label>
          <select name="aCaserne" onchange="submit()">
            <option selected disabled hidden></option>';
              foreach ($lesCis as $unCis) {
                if (isset($_REQUEST['aCaserne']) && $_REQUEST['aCaserne']==$unCis['cId']) {
                    echo '<option selected value="'.$unCis['cId'].'">'.$unCis['cNom'];
                }
                else {
                    echo '<option value="'.$unCis['cId'].'">'.$unCis['cNom'];
                }
              }
             ?>
          </select>
          <div class="forminter">
            <div class="inputinter">
              <p>Pompiers libres : </p>
              <select class="lstInter" name="listLibre" id="listLibre" multiple size="7">
                <optgroup label="DISPONIBLE">
                  <?php
                  if (isset($lesPompiers)) {
                    foreach ($lesPompiers as $unPompier) {
                      if ($unPompier['dispo']==1) {
                        echo '<option value="'.$unPompier['pId'].'">'.$unPompier['pNom']." ".$unPompier['pPrenom'];
                      }
                      else {
                        break;
                      }
                    }
                  }
                  ?>
                </optgroup>
                <optgroup label="AU TRAVAIL">
                  <?php
                  if (isset($lesPompiers)) {
                    foreach ($lesPompiers as $unPompier) {
                      if ($unPompier['dispo']==2) {
                        echo '<option value="'.$unPompier['pId'].'">'.$unPompier['pNom']." ".$unPompier['pPrenom'];
                      }
                    }
                  }
                  ?>
                </optgroup>
              </select>
            </div>
            <div class="inputinter">
              <p>Pompiers pour l'intervention : </p>
              <select class="lstInter" name="listEquipe[]" id="listEquipe" multiple size="7">
                <optgroup label="DISPONIBLE">
                  <?php
                  if (isset($lesPompiers)) {
                    foreach ($lesPompiers as $unPompier) {
                      if ($unPompier['dispo']==1) {
                        echo '<option hidden value="'.$unPompier['pId'].'">'.$unPompier['pNom']." ".$unPompier['pPrenom'];
                      }
                      else {
                        break;
                      }
                    }
                  }
                  ?>
                </optgroup>
                <optgroup label="AU TRAVAIL">
                  <?php
                  if (isset($lesPompiers)) {
                    foreach ($lesPompiers as $unPompier) {
                      if ($unPompier['dispo']==2) {
                        echo '<option hidden value="'.$unPompier['pId'].'">'.$unPompier['pNom']." ".$unPompier['pPrenom'];
                      }
                    }
                  }
                  echo'
                </optgroup>
              </select>
            </div>
          </div>
          <input class="button" type="submit" id="vldEquip" value="valider">
        </form>
      </section>

      <section class="tabs" id="section2">
        <form class="form_inter" action="index.php?choixTraitement=interventions&action=voir" method="post">
          <input type="hidden" name="tab" value="2">';
          switch ($_SESSION['statut']) {
            case '2': {
              $cis = $_SESSION['cis'];
              break;
            }
            case '3': {
              echo '
              <select class="choix_caserne" name="vCaserne" onchange="submit()">
                <option selected disabled hidden></option>';
              foreach ($lesCis as $unCis) {
                echo '
                <option';
                if (isset($_REQUEST['vCaserne']) && $_REQUEST['vCaserne'] == $unCis['cId']) {
                  echo ' selected';
                }
                echo ' value="'.$unCis['cId'].'">'.$unCis['cNom'].'</option>';
              }
              echo '
              </select>
              ';
              break;
            }
          }
          echo '
          <input type="checkbox" id="enCours" name="enCours" value="1" onchange="submit()" ';
          if (isset($_REQUEST['enCours'])) {
            echo"checked";
          }
          echo'>
          <label for="enCours">N\'afficher que les interventions en cours</label>
          <input type="date" name="tDate" onchange="submit()" value="';
          if (isset($_REQUEST['tDate'])) {
            echo $_REQUEST['tDate'];
          }
          echo'">
        </form>';

        if (isset($lesInterventions)) {
          foreach($lesInterventions as $uneIntervention)
          {
            echo '
          <div class="inter ';
            if ($uneIntervention['iHeureFin']==NULL || $uneIntervention['iHeureFin']=='') {
              echo 'inter_en_cours';
            }
            echo '">
            <div class="inter_grid">
              <p class="inter_adresse">'.$uneIntervention['iLieu'].'
              <p class="inter_descrip">'.$uneIntervention['iDescription'];
            if ($uneIntervention['iHeureFin']!=NULL && $uneIntervention['iHeureFin']!='') {
                echo'
              <p class="inter_date">Début&nbsp;: '.$uneIntervention['iDate'].'
              <br>
              Fin&nbsp;: '.$uneIntervention['iHeureFin'];
            }
            else {
              echo'
              <p class="inter_date">Début&nbsp;: '.$uneIntervention['iDate'];
            }
            echo '
              <div class="inter_participants inter_participants__hidden">
                <table>';
            foreach ($lesParticipants[$uneIntervention['iId']] as $unParticipant) {
              echo '
                  <tr>
                    <td class="inter_participants__nom">'.$unParticipant['pNom'].' '.$unParticipant['pPrenom'].'
                    <td class="inter_participants__dispo">';
              if ($unParticipant['aDisponibilite']==1) {
                echo 'disponible';
              }
              elseif ($unParticipant['aDisponibilite']==2) {
                echo 'au travail';
              }
            }
            echo '
                </table>';
            if ($uneIntervention['iHeureFin']==NULL || $uneIntervention['iHeureFin']=='') {
              echo '
                <form id="'.$uneIntervention['iId'].'" action="index.php?choixTraitement=interventions&action=finInter" name="inter_number" class="inter_form" method="post">';
                  if (isset($_REQUEST['vCaserne'])) {
                    echo '
                  <input type="hidden" name="caserne" value="'.$_REQUEST['vCaserne'].'">';
                  }
                  if (isset($_REQUEST['enCours'])) {
                    echo '
                  <input type="hidden" name="enCours" value="'.$_REQUEST['enCours'].'">';
                  }
                  if (isset($_REQUEST['tDate'])) {
                    echo '
                  <input type="hidden" name="tDate" value="'.$_REQUEST['tDate'].'">';
                  }
                  echo '
                  <input type="hidden" name="idInter" value="'.$uneIntervention['iId'].'">
                  <input type="hidden" name="idCis" value="'.$uneIntervention['iCis'].'">
                </form>
              </div>
            </div>
            <button class="inter_button button" onclick="document.getElementById(\''.$uneIntervention['iId'].'\').submit(); ">terminer l\'intervention</button>';
            }
            else {
              echo '
              </div>
            </div>';
            }
            echo '
          </div>';
          }
        }
        echo '
      </section>
    </div>';
    ?>
