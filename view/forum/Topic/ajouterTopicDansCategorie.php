<?php
$categorie = $result["data"]["categorie"]
?>
<form action="index.php?ctrl=topic&action=ajouterTopic" method="post">
    <h1><i>AJOUTER UN TOPIC DANS LA CATÉGORIE "<?= $categorie->getNomCategorie() ?>"</i></h1>
    <div>
        <label for="titre">Titre : </label>
        <input type="text" name="titre" id="titre" required>
    </div>
    <div>
        <label for="contenu">Contenu du premier post :</label>
        <textarea name="contenu" id="contenu" cols="30" rows="10" required></textarea>
    </div>
    <input type="submit" value="AJOUTER UN TOPIC" name="submitTopic" class="lienAjout ajoutFormulaire">
</form>