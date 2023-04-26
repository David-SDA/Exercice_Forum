<?php

$topics = $result["data"]['topics'];
    
?>

<h1>Liste topics</h1>

<?php
foreach($topics as $topic ){

    ?>
    <p><?=$topic->getTitle()?></p>
    <?php
}


  
