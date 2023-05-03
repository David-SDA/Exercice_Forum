<h1>Votre profil</h1>

<p><b><i>Pseudo : </i></b><?= App\Session::getUser()->getPseudo() ?></p>
<p><b><i>Email : </i></b><?= App\Session::getUser()->getEmail() ?></p>
<p><b><i>Date d'inscription : </i></b><?= App\Session::getUser()->getDateInscription() ?></p>