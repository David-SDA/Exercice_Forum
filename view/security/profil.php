<?php
if(App\Session::getUser()){
    ?>
    <h2><i><?= App\Session::getUser()->getPseudo() ?></i></h2>
    <div class="detailProfil">
        <p><b><i>Pseudo : </i></b><?= App\Session::getUser()->getPseudo() ?></p>
        <p><b><i>Email : </i></b><?= App\Session::getUser()->getEmail() ?></p>
        <p><b><i>Date d'inscription : </i></b><?= App\Session::getUser()->getDateInscription() ?></p>
        <p><b><i>Nombre de topics : </i></b><?= $result["data"]["nombreTopics"] ?></p>
        <p><b><i>Nombre de posts : </i></b><?= $result["data"]["nombrePosts"] ?></p>
        <p class="modificationProfil"><a href="" class="lienAjout">MODIFIER L'EMAIL</a><a href="" class="lienAjout grand">MODIFIER LE MOT DE PASSE</a></p>
    </div>
    <?php
}

$topics = $result["data"]['topics'];
?>
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
        <p>Vous n'avez pas publiez de topics</p>
    <?php
    }
    ?>
</div>

<?php
$derniersPosts = $result["data"]["derniersPosts"];
?>
<div class="liste">
<?php
    if($derniersPosts != NULL){
        ?>
        <h2><i>VOS DERNIERS POSTS</i></h2>
        <div class="element bordBas">
            <p class="elementGauche centre"><i><b>DATE DE CRÉATION</b></i></p>
            <p class="elementCentre"><i><b>CONTENU</b></i></p>
            <p class="elementDroite centre"><i><b>NOM DU TOPIC</b></i></p>
        </div>
        <?php
        foreach($derniersPosts as $post){
            ?>
            <div class="element">
                <p class="elementGauche"><i><?= $post->getDateCreation() ?></i></p>
                <p class="elementCentre"><?= $post->getContenu() ?></p>
                <div class="elementDroite"><p><a href="index.php?ctrl=post&action=listerPostsDansTopic&id=<?= $post->getTopic()->getId() ?>"><?= $post->getTopic()->getTitre() ?></a></p></div>
            </div>
            <?php
        }
    }
    else{
        ?>
        <h2>VOUS N'AVEZ ÉCRIT DE POST</h2>
        <?php
    }
?>
</div>