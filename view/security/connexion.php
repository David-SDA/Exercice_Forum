<form action="index.php?ctrl=security&action=connexion" method="post">
    <h1><i>SE CONNECTER</i></h1>
    <div>
        <label for="email">Email : </label>
        <input type="email" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
    </div>
    <div>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" name="motDePasse" id="motDePasse" required>
    </div>
    <input type="submit" value="SE CONNECTER" name="submitConnexion" class="lienAjout ajoutFormulaire">
</form>