<?php

$topics = $result["data"]['topics'];
    
?>

<h1>Liste topics</h1>

<?php
if($topics != NULL){
    foreach($topics as $topic){

        ?>
        <p><a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $topic->getId() ?>"><?=$topic->getTitre()?></a></p>
<?php
    }
}
else{
    ?>
    <p>Il n'y a pas de topics !</p>
<?php
}
?>

    <a href="index.php?ctrl=categorie&action=allerPageAjoutTopic" class="lienAjout">AJOUTER UN TOPIC</a>