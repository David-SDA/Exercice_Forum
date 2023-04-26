<?php

$topics = $result["data"]['topics'];
    
?>

<h1>Liste topics dans la catégorie</h1>

<?php
if($topics != NULL){
    foreach($topics as $topic){

?>
        <p><a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $topic->getId() ?>"><?=$topic->getTitre() . " crée le " . $topic->getDateCreation()?></a></p>
<?php
    }
}
else{
?>
    <p>Pas de topic dans cette catégorie !</p>
<?php
}