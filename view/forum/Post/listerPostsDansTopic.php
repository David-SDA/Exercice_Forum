<?php

$posts = $result["data"]['posts'];
    
?>

<h1>Liste posts du topic</h1>

<?php
foreach($posts as $post){

    ?>
    <h3>Par : <?= $post->getMembre()->getPseudo() ?></h3>
    <h5><?= $post->getDateCreation() ?></h5>
    <p><?=$post->getContenu()?></p>

    <?php
}
?>

<h2>Ajouter un post</h2>
<form action="index.php?ctrl=post&action=ajouterPost&id=<?= $_GET["id"] ?>" method="post">
    <div>
        <label for="contenu">Contenu du message : </label>
        <textarea name="contenu" id="contenu" cols="60" rows="10" require></textarea>
    </div>
    <input type="submit" value="Ajouter le post" name="ajouterPost">
</form>