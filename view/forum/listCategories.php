<?php

$categories = $result["data"]['categories'];
    
?>

<h1>Liste catégories</h1>

<?php
foreach($categories as $categorie){

    ?>
    <p><a href="#"><?=$categorie->getNomCategorie()?></a></p>
    <?php
}


  
