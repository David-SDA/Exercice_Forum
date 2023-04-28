<?php

$posts = $result["data"]['posts'];
    
?>

<h1>Liste posts du topic</h1>

<?php
if($posts != NULL){
    foreach($posts as $post){

        ?>
        <h3>Par : <?= $post->getMembre()->getPseudo() ?></h3>
        <h5><?= $post->getDateCreation() ?></h5>
        <p><?=$post->getContenu()?></p>

<?php
    }
}
else{
    ?>
    <p>Il n'y a pas post dans le topic</p>
<?php
}
?>

<a href="index.php?ctrl=post&action=allerPageAjoutPost&id=<?= $_GET["id"] ?>" class="lienAjout">AJOUTER UN POST</a>