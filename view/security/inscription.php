<form action="index.php?ctrl=security&action=inscription" method="post">
    <h1><i>S'INSCRIRE</i></h1>
    <div>
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
    </div>
    <div>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" name="motDePasse" id="motDePasse" required>
    </div>
    <div>
        <label for="motDePasseConfirmation">Confirmation du mot de passe :</label>
        <input type="password" name="motDePasseConfirmation" id="motDePasseConfirmation" required>
    </div>
    <input type="submit" value="S'INSCRIRE" name="submitInscription" class="lienAjout ajoutFormulaire">
</form>