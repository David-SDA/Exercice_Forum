<?php

$posts = $result["data"]['posts'];
    
?>

<h1>Liste posts du topic</h1>

<?php
foreach($posts as $post){

    ?>
    <h3>Par : <?= $post->getMembre()->getPseudo() ?></h3>
    <p><?=$post->getContenu()?></p>

    <?php
}