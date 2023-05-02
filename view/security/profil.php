<h1>Votre profil</h1>

<p>Pseudo : <?= App\Session::getUser()->getPseudo() ?></p>
<p>Email : <?= App\Session::getUser()->getEmail() ?></p>
<p>Date d'inscription : </p>
<?php
    var_dump(App\Session::getUser())
?>