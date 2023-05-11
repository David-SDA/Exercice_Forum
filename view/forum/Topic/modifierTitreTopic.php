<?php
$topic = $result["data"]["topic"];
?>
<form action="index.php?ctrl=topic&action=modificationTitreTopic&idTopic=<?= $topic->getId() ?>" method="post">
    <h1><i>MODIFIER LE TITRE DU TOPIC</i></h1>
    <div>
        <label for="titreActuel">Titre actuel :</label>
        <input type="text" name="titreActuel" id="titreActuel" value="<?= $topic->getTitre() ?>" readonly>
    </div>
    <div>
        <label for="nouveauTitre">Nouveau titre :</label>
        <input type="text" name="nouveauTitre" id="nouveauTitre">
    </div>
    <input type="submit" value="CONFIRMER" name="submitModificationTitreTopic" class="lienAjout ajoutFormulaire">
</form>