<?php
if(App\Session::getUser() && !App\Session::getUser()->hasRole("ROLE_BAN")){
    ?>
    <form action="index.php?ctrl=security&action=modificationMotDePasse" method="post">
        <h1><i>MODIFIER VOTRE MOT DE PASSE</i></h1>
        <div>
            <label for="ancienMotDePasse">Mot de passe actuel :</label>
            <input type="password" name="ancienMotDePasse" id="ancienMotDePasse" required>
        </div>
        <div>
            <label for="nouveauMotDePasse">Nouveau mot de passe :</label>
            <input type="password" name="nouveauMotDePasse" id="nouveauMotDePasse" required>
        </div>
        <div>
            <label for="motDePasseConfirmation">Confirmation du mot de passe :</label>
            <input type="password" name="motDePasseConfirmation" id="motDePasseConfirmation" required>
        </div>
        <input type="submit" value="CONFIRMER" name="submitModificationMotDePasse" class="lienAjout ajoutFormulaire">
    </form>
<?php
}