<h1>Ajouter une catégorie</h1>

<form action="index.php?ctrl=categorie&action=ajouterCategorie" method="post">
    <label for="nomCategorie">Nom de la catégorie : </label>
    <input type="text" name="nomCategorie" id="nomCategorie" required>

    <input type="submit" value="Ajouter la catégorie" name="submitCategorie" class="boutonAjout">
</form>