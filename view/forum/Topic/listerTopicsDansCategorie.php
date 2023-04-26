<?php

$topics = $result["data"]['topics'];
    
?>

<h1>Liste topics dans la catégorie</h1>

<?php
foreach($topics as $topic){

    ?>
    <p><a href="#"><?=$topic->getTitre() . " crée le " . $topic->getDateCreation()?></a></p>
    <?php
}