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
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $lesMois = $pdo->getLesMois();
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include 'vues/v_listeVisiteurEtMois.php';
        break;
//    case 'voirFicheFrais';
//
//        break;
//    case 'actualiserFraisForfait';
//
//        break;
//    case 'supprimerFraisHorsForfait';
//
//        break;
//    case 'validerFiche';
//
//        break;
//    case 'demanderReport';
//
//        break;
}