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
switch ($action) {
    case 'selectionnerVisiteurEtMois':
        // affichage de la vue v_listeVisiteursEtMois
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $lesMois = $pdo->getLesMois();
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include 'vues/v_listeVisiteurEtMois.php';
        break;
    
    case 'voirFicheFrais';
        // affichage de la vue v_listeVisiteurEtMois
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $lesMois = $pdo->getLesMois();
        $leVisiteur = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $leVisiteur;
        include 'vues/v_listeVisiteurEtMois.php';

        // affichage de la vue v_detailFicheFrais
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        $montantValide = $lesInfosFicheFrais['montantValide'];
        include 'vues/v_detailFicheFrais.php';

        // affichage de la vue v_actualisationFraisForfait
        $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
        include 'vues/v_actualisationFraisForfait.php';

        // affichage de la vue v_actualisationFraisHorsForfait
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        include 'vues/v_actualisationFraisHorsForfait.php';
        break;
    
    case 'actualiserFraisForfait';
        // affichage de la vue v_listeVisiteurEtMois
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $lesMois = $pdo->getLesMois();
        $leVisiteur = filter_input(INPUT_POST, 'leVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $leVisiteur;
        include 'vues/v_listeVisiteurEtMois.php';
        
        // affichage de la vue v_detailFicheFrais
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        $montantValide = $lesInfosFicheFrais['montantValide'];
        include 'vues/v_detailFicheFrais.php';

        // affichage de la vue v_actualisationFraisForfait
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
        include 'vues/v_actualisationFraisForfait.php';

        // affichage de la vue v_actualisationFraisHorsForfait
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        include 'vues/v_actualisationFraisHorsForfait.php';
        break;
        
    case 'refuserFraisHorsForfait';
        // affichage de la vue v_listeVisiteurEtMois
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $lesMois = $pdo->getLesMois();
        $leVisiteur = filter_input(INPUT_POST, 'leVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $leVisiteur;
        include 'vues/v_listeVisiteurEtMois.php';

        // affichage de la vue v_detailFicheFrais
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        $montantValide = $lesInfosFicheFrais['montantValide'];
        include 'vues/v_detailFicheFrais.php';

        // affichage de la vue v_actualisationFraisForfait
        $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
        include 'vues/v_actualisationFraisForfait.php';
        
        // affichage de la vue v_actualisationFraisHorsForfait
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $leFraisHorsForfait = getLeFraisHorsForfait($idFrais);
        var_dump($leFraisHorsForfait);
        $pdo->refuserFraisHorsForfait($idFrais);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        include 'vues/v_actualisationFraisHorsForfait.php';
        break;
        
//    case 'validerFiche';
//
//        break;
//    case 'demanderReport';
//
//        break;
}

