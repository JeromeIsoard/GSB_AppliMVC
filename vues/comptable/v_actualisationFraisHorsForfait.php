<?php
/**
 * Vue Liste des frais hors forfait
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
<hr>
<div class="panel panel-info">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>  
                <th class="montant">Montant</th>  
                <th class="action">&nbsp;</th> 
            </tr>
        </thead>  
        <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>           
                <tr>
                    <td> <?php echo $date ?></td>
                    <td> <?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td><a href="index.php?uc=validerFrais&action=refuserFraisHorsForfait&idFrais=<?php echo $id ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer ce frais</a></td>
                    <td><a href="index.php?uc=validerFrais&action=reporterFraisHorsForfait&idFrais=<?php echo $id ?>" 
                           onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">Reporter ce frais</a></td>       
                </tr>
                <?php
            }
            ?>
        </tbody>  
    </table>
</div>
<div id="center-div">
    <form method="post" 
          action="index.php?uc=validerFrais&action=validerFiche" 
          role="form">
        <fieldset>
            <input type="hidden" name="leVisiteur" value="<?php echo $leVisiteur ?>">
            <input type="hidden" name="leMois" value="<?php echo $leMois ?>">
            <button class="btn btn-success" type="submit">Valider la fiche</button>
        </fieldset>
    </form>
</div>


