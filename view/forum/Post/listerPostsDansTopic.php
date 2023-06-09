<?php
if(App\Session::getUser() && !App\Session::getUser()->hasRole("ROLE_BAN")){

    $posts = $result["data"]["posts"];
    $idAncienPost = $result["data"]["ancien"];
    $topic = $result["data"]["topic"];
    ?>

    <?php
    $verrouiller = 0;
    if($posts != NULL){
        if(App\Session::isAdmin() || App\Session::getUser()->getId() == $topic->getMembre()->getId()){
            if(!$topic->getVerrouiller()){
            ?>
                <a href="index.php?ctrl=topic&action=verrouillerTopic&id=<?= $topic->getId() ?>" class="lienAjout grand">VERROUILLER LE TOPIC</a>
            <?php
            }
            else{
                ?>
                <a href="index.php?ctrl=topic&action=deverrouillerTopic&id=<?= $topic->getId() ?>" class="lienAjout grand">DÉVERROUILLER LE TOPIC</a>
            <?php
            }
        }
        ?>
        <h1><i><?= $topic->getTitre() ?></i></h1>
        <?php
        if(App\Session::getUser()->getId() == $topic->getMembre()->getId() && !$topic->getVerrouiller()){
        ?>
            <a href="index.php?ctrl=topic&action=allerPageModificationTitreTopic&id=<?= $topic->getId() ?>"><i class="fas fa-edit" style="color: blue;"></i></a>
        <?php
        }
        ?>
        <?php
        foreach($posts as $post){
            $verrouiller = $post->getTopic()->getVerrouiller();

            ?>
            <div class="postDuTopic">
                <div class="infoPost">
                    <h3><?= $post->getMembre()->getPseudo() ?></h3>
                    <div>
                        <h5><?= $post->getDateCreation() ?></h5>
                        <h5><i>Dernière modification : <?= $post->getDateDerniereModification() ?></i></h5>
                        <h5>
                            <?php
                            if(App\Session::isAdmin() || App\Session::getUser()->getId() == $post->getMembre()->getId()){
                                if($idAncienPost != $post->getId() && $post->getTopic()->getVerrouiller() != 1){
                            ?>
                                    <a href="index.php?ctrl=post&action=supprimerPost&id=<?= $post->getId() ?>"><i class="far fa-trash-alt"></i></a>
                            <?php
                                }
                            }
                            if(App\Session::getUser()->getId() == $post->getMembre()->getId()){
                                if($post->getTopic()->getVerrouiller() != 1){
                                    ?>
                                    <a href="index.php?ctrl=post&action=allerPageModificationPost&id=<?= $post->getId() ?>"><i class="fas fa-edit"></i></a>
                                    <?php
                                }
                            }
                            ?>
                        </h5>
                    </div>
                </div>
                <p><?=$post->getContenu()?></p>
            </div>
    <?php
        }
    }
    else{
        ?>
        <p>Il n'y a pas post dans le topic</p>
    <?php
    }

    if(!$verrouiller){
    ?>
        <a href="index.php?ctrl=post&action=allerPageAjoutPost&id=<?= $_GET["id"] ?>" class="lienAjout">AJOUTER UN POST</a>
    <?php
    }
}