<?php

/**
 * Suivi du paiement des fiches de frais
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
$lesFichesVAetMP = $pdo->getLesFichesVAetMP();
$laFiche = filter_input(INPUT_POST, 'lstFiche', FILTER_SANITIZE_STRING);
$ficheASelectionner = $laFiche;
$leVisiteur = substr($laFiche, 9, 4);
$leMois = substr($laFiche, 0, 6);
$numAnnee = substr($laFiche, 0, 4);
$numMois = substr($laFiche, 4, 2);
$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
$libEtat = $lesInfosFicheFrais['libEtat'];
$dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
$montantValide = $lesInfosFicheFrais['montantValide'];
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
$lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

switch ($action) {
    case 'selectionnerFiche':
        include 'vues/comptable/v_listeFicheVAetMP.php';
        break;

    case 'voirFicheFrais':
        include 'vues/comptable/v_listeFicheVAetMP.php';
        include 'vues/comptable/v_ficheFraisVAetMP.php';
        break;

    case 'mettreEnPaiementFiche':
        include 'vues/comptable/v_listeFicheVAetMP.php';
        // modification de l'état de la fiche à "mise en paiement"
        $pdo->majEtatFicheFrais($leVisiteur, $leMois, 'MP');
        // affichage de la vue
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include 'vues/comptable/v_ficheFraisVAetMP.php';
        break;

    case 'indiquerRemboursement':
        include 'vues/comptable/v_listeFicheVAetMP.php';
        // modification de l'état de la fiche à "mise en paiement"
        $pdo->majEtatFicheFrais($leVisiteur, $leMois, 'RB');
        // affichage de la vue
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include 'vues/comptable/v_ficheFraisVAetMP.php';
        break;
}
