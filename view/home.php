<?php
    if(App\Session::getUser()){
        if(!App\Session::getUser()->hasRole("ROLE_BAN")){
            ?>
            <h1>BIENVENUE SUR LE FORUM</h1>
            <p>Vous pouvez accéder au différentes rubriques dans la barre de navigation !</p>
    <?php
        }
        else{
            ?>
            <h1>VOUS ÊTES BANNIS</h1>
            <p>Vous ne pouvez plus accéder au forum</p>
            <?php
        }
    }
    else{
        ?>
        <h1>BIENVENUE SUR LE FORUM</h1>
        <p>Connectez-vous pour accéder au forum.</p>
        <p>Si vous n'avez pas de compte, vous pouvez en créer un !</p>
        <p>Tout est disponible dans la barre de navigation !</p>
        <?php
    }