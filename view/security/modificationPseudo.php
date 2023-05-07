<form action="index.php?ctrl=security&action=modificationPseudo" method="post">
    <h1><i>MODIFIER VOTRE PSEUDO</i></h1>
    <div>
        <label for="ancienPseudo">Pseudo actuel :</label>
        <input type="text" name="ancienPseudo" id="ancienPseudo" value="<?= App\Session::getUser()->getPseudo() ?>" readonly>
    </div>
    <div>
        <label for="nouveauPseudo">Nouveau pseudo :</label>
        <input type="text" name="nouveauPseudo" id="nouveauPseudo" required>
    </div>
    <input type="submit" value="CONFIRMER" name="submitModificationPseudo" class="lienAjout ajoutFormulaire">
</form>