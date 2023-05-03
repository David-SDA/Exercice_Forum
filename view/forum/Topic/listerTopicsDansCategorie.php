<?php

$topics = $result["data"]['topics'];
    
if($topics != NULL){
    ?>
    <div class="liste">
        <h1>LISTE DES TOPICS DANS LA CATÉGORIE</h1>
        <?php
        foreach($topics as $topic){
        ?>
        <div class="element">
            <p class="elementGauche"><i><?= $topic->getMembre()->getPseudo() ?></i></p>
            <div class="elementCentre">
                <a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $topic->getId() ?>"><?=$topic->getTitre()?></a>
                <p><i><?= $topic->getDateCreation() ?></i></p>
                <?php
                if($topic->getVerrouiller()){
                ?>
                    <p><i class="fas fa-lock"></i></p>
                <?php
                }
                else{
                    ?>
                    <p><i class="fas fa-lock-open"></i></p>
                <?php
                }
                ?>
            </div>
            <p class="elementDroite"></p>
        </div>
        <?php
        }
        ?>
    </div>
<?php
}
else{
?>
    <h1>Pas de topic dans cette catégorie !</h1>
<?php
}
?>