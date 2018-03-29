<?php
/**
 * Vue Actualisation des frais au forfait
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
<div class="panel panel-info">
    <div class="panel-heading">Eléments forfaitisés</div>
    <form method="post" 
          action="index.php?uc=validerFrais&action=actualiserFraisForfait" 
          role="form">
        <fieldset>
            <table class="table table-bordered table-responsive">
                <tr>
                    <?php
                    foreach ($lesFraisForfait as $unFraisForfait) {
                        $libelle = $unFraisForfait['libelle'];
                        ?>
                        <th> <?php echo htmlspecialchars($libelle) ?></th>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($lesFraisForfait as $unFraisForfait) {
                        $idFrais = $unFraisForfait['idfrais'];
                        $quantite = $unFraisForfait['quantite'];
                        ?>
                    <td class="qteForfait">
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </td>
                        <?php
                    }
                    ?>
                </tr>
            </table>
            <button class="btn btn-success" type="submit">Valider</button>
            <button class="btn btn-danger" type="reset">Effacer</button>
        </fieldset>
    </form>
</div>
