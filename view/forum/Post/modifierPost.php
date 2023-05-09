<?php
$post = $result["data"]["post"];
?>
<form action="index.php?ctrl=post&action=modificationPost" method="post">
    <h1><i>MODIFIER LE POST</i></h1>
    <div>
        <label for="postActuel">Post actuel :</label>
        <textarea name="postActuel" id="postActuel" cols="30" rows="10" readonly><?= $post->getContenu() ?></textarea>
    </div>
    <div>
        <label for="nouveauPost">Nouveau post :</label>
        <textarea name="nouveauPost" id="nouveauPost" cols="30" rows="10"></textarea>
    </div>
    <input type="submit" value="CONFIRMER" name="submitModificationPost" class="lienAjout ajoutFormulaire">
</form>