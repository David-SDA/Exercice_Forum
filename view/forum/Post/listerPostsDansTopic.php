<?php

$posts = $result["data"]["posts"];
?>

<h1><i>Liste des posts du topic</i></h1>

<?php
$verrouiller = 0;
if($posts != NULL){
    foreach($posts as $post){
        $verrouiller = $post->getTopic()->getVerrouiller();

        ?>
        <div class="postDuTopic">
            <div class="infoPost">
                <h3><?= $post->getMembre()->getPseudo() ?></h3>
                <div>
                    <h5><?= $post->getDateCreation() ?></h5>
                    <h5>
                        <?php
                        if(App\Session::isAdmin()){
                        ?>
                            <a href="index.php?ctrl=post&action=supprimerPost&id=<?= $post->getId() ?>&idTopic=<?= $post->getTopic()->getId() ?>"><i class="far fa-trash-alt"></i></a>
                        <?php
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
?>