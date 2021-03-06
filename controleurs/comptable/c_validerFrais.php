<?php

/**
 * Validation des fiches de frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Jérome ISOARD <isoard.jerome@gmail.com>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

// récupération du visiteur et du mois
$leVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
if (!$leVisiteur && !$leMois){
    $leVisiteur = filter_input(INPUT_GET, 'lstVisiteur', FILTER_SANITIZE_STRING);
    $leMois = filter_input(INPUT_GET, 'lstMois', FILTER_SANITIZE_STRING);
}

// données nécessaires à l'affichage de la vue v_listeVisiteursEtMois
$lesVisiteurs = $pdo->getLesVisiteurs();
$lesMois = $pdo->getLesMois();
$moisASelectionner = $leMois;
$visiteurASelectionner = $leVisiteur;

// données nécessaires à l'affichage de la vue v_detailFicheFrais
$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
$numAnnee = substr($leMois, 0, 4);
$numMois = substr($leMois, 4, 2);
$libEtat = $lesInfosFicheFrais['libEtat'];
$dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
$montantValide = $lesInfosFicheFrais['montantValide'];

// données nécessaires à l'affichage des vues v_actualisationFraisForfait
// et v_actualisationFraisHorsForfait
$lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

switch ($action) {
    case 'selectionnerVisiteurEtMois':
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        break;

    case 'voirFicheFrais':
        // pas de traitement, uniquement affichage des vues
        break;

    case 'actualiserFraisForfait':
        // actualisation des frais forfait
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);
            ajouterSucces('Les modifications ont été prises en compte.');
            include 'vues/v_succes.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
        break;

    case 'refuserFraisHorsForfait':
        // refus d'un frais hors forfait et actualisation des frais hors forfait
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $leFraisHorsForfait = $pdo->getLeFraisHorsForfait($idFrais);
        // test si le frais n'est pas déjà refusé                
        if (substr($leFraisHorsForfait['libelle'], 0, 6) != 'REFUSE') {
            $pdo->refuserFraisHorsForfait($idFrais);
        }
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        break;

    case 'validerFiche':
        // calcul et modification du montant validé
        $montantValide = 0;
        // frais forfait:
        foreach ($lesFraisForfait as $unFraisForfait){            
            $montantFrais = $pdo->getMontantFraisForfait($unFraisForfait['idfrais']);
            $montantFrais = floatval($montantFrais) * $unFraisForfait['quantite'];
            $montantValide += $montantFrais;
        }
        // frais hors forfait:
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait){
            // test si le frais n'est pas refusé                
            if (substr($unFraisHorsForfait['libelle'], 0, 6) != 'REFUSE'){
                $montantValide += floatval($unFraisHorsForfait['montant']);
            }
        }
        // maj du montant validé
        $pdo->majMontantValide($leVisiteur, $leMois, $montantValide);
        
        // modification de l'état de la fiche de frais
        $pdo->majEtatFicheFrais($leVisiteur, $leMois, 'VA');
        // récupération des données pour la vue v_detailFicheFrais
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        $montantValide = $lesInfosFicheFrais['montantValide'];
        break;

    case 'reporterFraisHorsForfait':
        // report d'un frais hors forfait
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $leMoisSuivant = getMois(date('d/m/Y')); //correspont au mois actuel
        
        // test si une fiche du mois suivant n'existe pas
        if (!$pdo->existeFicheFrais($leVisiteur, $leMoisSuivant)) {
            // création d'une nouvelle fiche pour le mois suivant
            $pdo->creeNouvellesLignesFrais($leVisiteur, $leMoisSuivant);
        }
        
        // ajout de la ligne de frais dans la fiche du mois suivant
        $leFraisReporte = $pdo->getLeFraisHorsForfait($idFrais);
        $pdo->creeNouveauFraisHorsForfait(
        $leFraisReporte['idvisiteur'], $leMoisSuivant, $leFraisReporte['libelle'], 
        dateAnglaisVersFrancais($leFraisReporte['date']), $leFraisReporte['montant']);
        
        // suppression de la ligne dans la fiche courante
        $pdo->supprimerFraisHorsForfait($idFrais);

        // actualisation des frais hors forfait pour l'affichage
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        break;
}

// affichage des vues
include 'vues/comptable/v_listeVisiteurEtMois.php';

if ($action != 'selectionnerVisiteurEtMois'){
    // test s'il existe bien une fiche de frais pour ce visiteur et ce mois
    if ($pdo->existeFicheFrais($leVisiteur, $leMois)) {
            include 'vues/comptable/v_detailFicheFrais.php';
            include 'vues/comptable/v_actualisationFraisForfait.php';
            include 'vues/comptable/v_actualisationFraisHorsForfait.php';
        } else {
            ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois.');
            include 'vues/v_erreurs.php';
        }
}
