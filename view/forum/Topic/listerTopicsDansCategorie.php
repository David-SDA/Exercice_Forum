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
            <p class="elementDroite">
                <i><?= $topic->getCategorie()->getNomCategorie() ?></i>
                <?php
                if(App\Session::isAdmin() || App\Session::getUser()->getId() == $topic->getMembre()->getId()){
                ?>
                    <a href="index.php?ctrl=topic&action=supprimerTopic&id=<?= $topic->getId() ?>"><i class="far fa-trash-alt"></i></a>
                <?php
                }
                ?>
            </p>
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