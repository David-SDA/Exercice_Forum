<?php
$membre = $result["data"]["membre"];
$topics = $result["data"]["topics"];
?>
<h2><i><?= $membre->getPseudo() ?></i></h2>
<div class="detailProfil">
    <p><b><i>Pseudo : </i></b><?= $membre->getPseudo() ?></p>
    <p><b><i>Email : </i></b><?= $membre->getEmail() ?></p>
    <p><b><i>Date d'inscription : </i></b><?= $membre->getDateInscription() ?></p>
    <p><b><i>Nombre de topics : </i></b><?= $result["data"]["nombreTopics"] ?></p>
    <p><b><i>Nombre de posts : </i></b><?= $result["data"]["nombrePosts"] ?></p>
</div>

<div class="liste">
    <h1>TOPICS CRÉÉS</h1>
    <?php
    if($topics != NULL){
        foreach($topics as $topic){
            ?>
            <div class="element">
                <p class="elementGauche">
                    <b><i><?= $topic->getMembre()->getPseudo() ?></i></b><br>
                    <i>Posts : <?= $topic->getNombrePosts() ?></i>
                </p>
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
    }
    else{
        ?>
        <p>Le membre n'a pas publié de topics</p>
    <?php
    }
    ?>
</div>