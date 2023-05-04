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
