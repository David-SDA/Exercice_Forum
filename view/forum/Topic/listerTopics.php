<?php

$topics = $result["data"]['topics'];
    
?>
<div class="listeTopic">
    <h1>TOPICS</h1>
    <?php
    if($topics != NULL){
        foreach($topics as $topic){
            ?>
            <div class="unTopic">
                <p class="pseudo"><i><?= $topic->getMembre()->getPseudo() ?></i></p>
                <div class="unTopicCentre">
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
                <p class="categorie"><i><?= $topic->getCategorie()->getNomCategorie() ?></i></p>
            </div>
    <?php
        }
    }
    else{
        ?>
        <p>Il n'y a pas de topics !</p>
    <?php
    }
    ?>
</div>
<a href="index.php?ctrl=categorie&action=allerPageAjoutTopic" class="lienAjout">AJOUTER UN TOPIC</a>