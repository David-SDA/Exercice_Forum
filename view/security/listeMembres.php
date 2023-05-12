<?php
if(App\Session::getUser()->hasRole("ROLE_ADMIN")){

    $membres = $result["data"]["membres"];
    ?>
    <div class="liste">
        <h1><i>LISTE DES MEMBRES</i></h1>
        <div class="element bordBas">
            <p class="elementGauche centre"><i><b>DATE D'INSCRIPTION</b></i></p>
            <p class="elementCentre"><i><b>PSEUDO</b></i></p>
            <p class="elementDroite centre"><i><b>RÔLE</b></i></p>
        </div>
        <?php
        foreach($membres as $membre){
            ?>
            <div class="element">
                <p class="elementGauche centre"><?= $membre->getDateInscription() ?></p>
                <p class="elementCentre"><a href="index.php?ctrl=membre&action=profilAdmin&id=<?= $membre->getId() ?>"><b><?= $membre->getPseudo() ?></b></a></p>
                <p class="elementDroite centre">
                    <?php 
                    if($membre->hasRole("ROLE_ADMIN")){
                        echo "ADMIN";
                    }
                    elseif($membre->hasRole("ROLE_MEMBER")){
                        echo "MEMBRE";
                    }
                    else{
                        echo "BANNI";
                    }

                    if(!$membre->hasRole("ROLE_ADMIN")){
                        if($membre->hasRole("ROLE_BAN")){
                        ?>
                            <a href="index.php?ctrl=security&action=debannir&id=<?= $membre->getId() ?>" class="lienAjout deban">DÉBANNIR</a>
                        <?php
                        }
                        else{
                            ?>
                            <a href="index.php?ctrl=security&action=bannir&id=<?= $membre->getId() ?>" class="lienAjout ban">BANNIR</a>
                            <?php
                        }
                    }
                    ?>
                </p>
            </div>
            <?php
        }
        ?>
    </div>
<?php
}