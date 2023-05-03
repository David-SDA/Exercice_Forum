<?php

$categories = $result["data"]['categories'];
    
?>

<div class="liste">
    <h1>CATÉGORIES</h1>
    <?php
    if($categories != NULL){
        foreach($categories as $categorie){

            ?>
            <p class="uneCategorie"><a href="index.php?ctrl=topic&action=listerTopicsDansCategorie&id=<?= $categorie->getId() ?>"><?=$categorie->getNomCategorie()?></a></p>
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