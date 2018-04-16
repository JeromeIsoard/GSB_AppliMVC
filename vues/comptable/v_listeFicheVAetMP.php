<?php
/**
 * Vue Liste des fiches de frais validées
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
?>

<h2>Suivre le paiement des fiches de frais</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner une fiche : </h3>
    </div>
    <div class="col-md-4">
        <form action="index.php?uc=suivrePaiement&action=voirFicheFrais" 
              method="post" role="form">
            <div class="form-group">
                <label for="lstFiche" accesskey="n">Fiche validée : </label>
                <select id="lstFiche" name="lstFiche" class="form-control">
                    <?php
                    foreach ($lesFichesVAetMP as $uneFiche) {
                        $id = $uneFiche['id'];
                        $nom = $uneFiche['nom'];
                        $prenom = $uneFiche['prenom'];
                        $mois = $uneFiche['mois'];
                        if ($mois . ' - ' . $id == $ficheASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois . ' - ' . $id ?>">
                                <?php echo $id . ' ' . $nom . ' ' . $prenom . ' - ' . $mois ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois . ' - ' . $id ?>">
                                <?php echo $id . ' ' . $nom . ' ' . $prenom . ' - ' . $mois ?> </option>
                            <?php
                        }
                    }
                    ?>
                            
                </select>
            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                   role="button">
        </form>
    </div>
</div>
