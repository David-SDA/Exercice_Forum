<?php

$categories = $result["data"]['categories'];
    
?>

<h1>Liste catégories</h1>

<?php
foreach($categories as $categorie){

    ?>
    <p><a href="index.php?ctrl=topic&action=listerTopicsDansCategorie&id=<?= $categorie->getId() ?>"><?=$categorie->getNomCategorie()?></a></p>
    <?php
}
?>

    <a href="index.php?ctrl=categorie&action=allerPageAjoutCategorie" class="lienAjout">AJOUTER UNE CATÉGORIE</a>