<?php
if(App\Session::getUser() && !App\Session::getUser()->hasRole("ROLE_BAN")){
?>
    <a href="index.php?ctrl=post&action=listerPostsDansTopic&idTopic=<?= $_GET["idTopic"] ?>" class="retourTopic"><span><i class="fas fa-arrow-left"></i></span>Retour au topic</a>
    <form action="index.php?ctrl=post&action=ajouterPost&idTopic=<?= $_GET["idTopic"] ?>" method="post">
        <h1><i>AJOUTER UN POST</i></h1>
        <div>
            <label for="contenu">Contenu du post : </label>
            <textarea name="contenu" id="contenu" cols="60" rows="16" required></textarea>
        </div>
        <input type="submit" value="AJOUTER UN POST" name="ajouterPost" class="lienAjout ajoutFormulaire">
    </form>
<?php
}