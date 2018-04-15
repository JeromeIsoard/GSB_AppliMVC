<?php

/**
 * Script de clôture de toutes les fiches du mois précédent
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

require './fonctions.php';

$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', '');
$pdo->query('SET CHARACTER SET utf8');

set_time_limit(0);

$etat = 'CL';

// récupération du mois qui va être traité: le mois précédent
$moisActuel = getMois(date('d/m/Y'));
$moisPrecedent = getMoisPrecedent($moisActuel);

// maj de l'état de toutes les fiches de frais du mois précédent
$req = 'UPDATE ficheFrais '
        . 'SET idetat = "' . $etat . '", datemodif = now() '
        . 'WHERE fichefrais.mois = "' . $moisPrecedent . '"';
        
$pdo->exec($req);

echo '<br>' . 'Fiches de frais de ' . $moisPrecedent . ' clôturées avec succès.';



