<a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $_GET["id"] ?>" class="retourTopic"><span><i class="fas fa-arrow-left"></i></span>Retour au topic</a>
<form action="index.php?ctrl=post&action=ajouterPost&id=<?= $_GET["id"] ?>" method="post">
    <h1><i>AJOUTER UN POST</i></h1>
    <div>
        <label for="contenu">Contenu du post : </label>
        <textarea name="contenu" id="contenu" cols="60" rows="16" required></textarea>
    </div>
    <input type="submit" value="AJOUTER UN POST" name="ajouterPost" class="lienAjout ajoutFormulaire">
</form>