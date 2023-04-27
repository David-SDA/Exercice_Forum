<a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $_GET["id"] ?>">Retour au topic</a>
<h2>Ajouter un post</h2>
<form action="index.php?ctrl=post&action=ajouterPost&id=<?= $_GET["id"] ?>" method="post">
    <div>
        <label for="contenu">Contenu du message : </label>
        <textarea name="contenu" id="contenu" cols="60" rows="10" required></textarea>
    </div>
    <input type="submit" value="Ajouter le post" name="ajouterPost" class="boutonAjout">
</form>