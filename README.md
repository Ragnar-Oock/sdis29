[FR-fr]
# sdis29
Repo d'un projet de développement d'interface de gestion d'un centre de pompiers:
- gestion des profils des individus en fonction de leur niveau d'autorisation (modification partielle ou totales des informations)
- gestion des disponibilité des individus
- fixation des gardes pas les chefs de centres
- creation des interventions sur le terrain par les chefs de groupement
- affichage des interventions en cours et cloture par les personnes habilitées
- protection du systeme par mots de passes
   
# Amélioration possibles :   
1. mise a nivau du système de login   
  -- remplacer MD5 par SHA256
  -- ajout d'un salage efficace
  -- interoperabilité avec le syteme actuel (transparence de migration pour l'utilisateur)
1. refonte de la structure de la base de données (non presente dans le git a ce jour)
1. refonte complete de la charte graphique et netoyage de la feuille css
1. minisation du code et optimisation globale des balises HTML
