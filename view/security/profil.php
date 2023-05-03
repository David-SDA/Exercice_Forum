<?php
if(App\Session::getUser()){
    ?>
    <h1>Votre profil</h1>

    <p><b><i>Pseudo : </i></b><?= App\Session::getUser()->getPseudo() ?></p>
    <p><b><i>Email : </i></b><?= App\Session::getUser()->getEmail() ?></p>
    <p><b><i>Date d'inscription : </i></b><?= App\Session::getUser()->getDateInscription() ?></p>
    <p><b><i>Nombre de topics : </i></b><?= $result["data"]["nombreTopics"] ?></p>
    <p><b><i>Nombre de posts : </i></b><?= $result["data"]["nombrePosts"] ?></p>
    <?php
}