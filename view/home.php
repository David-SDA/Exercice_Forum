<h1>BIENVENUE SUR LE FORUM</h1>

<?php
    if(App\Session::getUser()){
        ?>
        <p>Vous pouvez accéder au différentes rubriques dans la barre de navigation !</p>
    <?php
    }
    else{
        ?>
        <p>Connectez-vous pour accéder au forum.</p>
        <p>Si vous n'avez pas de compte, vous pouvez en créer un !</p>
        <p>Tout est disponible dans la barre de navigation !</p>
        <?php
    }