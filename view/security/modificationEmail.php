<form action="index.php?ctrl=security&action=modificationEmail" method="post">
    <h1><i>MODIFIER VOTRE EMAIL</i></h1>
    <div>
        <label for="ancienEmail">Email actuel :</label>
        <input type="email" name="ancienEmail" id="ancienEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
    </div>
    <div>
        <label for="nouveauEmail">Nouvel email :</label>
        <input type="email" name="ancienEmail" id="ancienEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
    </div>
    <div>
        <label for="EmailConfirmation">Confirmation de l'email :</label>
        <input type="email" name="ancienEmail" id="ancienEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
    </div>
    <div>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" name="motDePasse" id="motDePasse" required>
    </div>
    <input type="submit" value="CONFIRMER" name="submitModificationEmail" class="lienAjout ajoutFormulaire">
</form>