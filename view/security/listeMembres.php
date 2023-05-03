<h1><i>LISTE DES MEMBRES</i></h1>
<?php
$membres = $result["data"]["membres"];

foreach($membres as $membre){
    ?>
    <p><?= $membre->getPseudo() ?></p>
    <?php
}
?>