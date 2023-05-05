<form action="index.php?ctrl=security&action=modificationMotDePasse" method="post">
    <h1><i>MODIFIER VOTRE MOT DE PASSE</i></h1>
    <div>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" name="motDePasse" id="motDePasse" required>
    </div>
    <div>
        <label for="motDePasseConfirmation">Confirmation du mot de passe :</label>
        <input type="password" name="motDePasseConfirmation" id="motDePasseConfirmation" required>
    </div>
    <input type="submit" value="CONFIRMER" name="submitModificationMotDePasse" class="lienAjout ajoutFormulaire">
</form>