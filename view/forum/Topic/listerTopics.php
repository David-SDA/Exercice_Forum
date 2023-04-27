<?php

$topics = $result["data"]['topics'];
    
?>

<h1>Liste topics</h1>

<?php
foreach($topics as $topic){

    ?>
    <p><a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $topic->getId() ?>"><?=$topic->getTitre()?></a></p>
    <?php
}
?>

    <a href="index.php?ctrl=categorie&action=allerPageAjoutTopic">AJOUTER UN TOPIC</a>