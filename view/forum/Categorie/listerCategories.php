<?php

$categories = $result["data"]['categories'];
    
?>

<div class="liste">
    <h1>CATÉGORIES</h1>
    <?php
    if($categories != NULL){
        foreach($categories as $categorie){
            ?>
            <div class="element">
                <div class="elementGauche"></div>
                <div class="elementCentre">
                    <p><a href="index.php?ctrl=topic&action=listerTopicsDansCategorie&idCategorie=<?= $categorie->getId() ?>"><?=$categorie->getNomCategorie()?></a></p>
                </div>
                <div class="elementDroite centre">
                    <?php
                    if(App\Session::isAdmin()){
                    ?>
                        <a href="index.php?ctrl=categorie&action=supprimerCategorie&idCategorie=<?= $categorie->getId() ?>"><i class="far fa-trash-alt"></i></a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
    else{
        ?>
        <p>Il n'y a pas de catégories !</p>
    <?php    
    }
    ?>
</div>
<?php
if(App\Session::getUser()->hasRole("ROLE_ADMIN")){
    ?>
    <a href="index.php?ctrl=categorie&action=allerPageAjoutCategorie" class="lienAjout grand">AJOUTER UNE CATÉGORIE</a>
    <?php
}
?>