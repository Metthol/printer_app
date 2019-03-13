<?php

function init_session()
{
    include $_SERVER['DOCUMENT_ROOT']. "/src/php/variables.php";

    echo $dir_images;
    mkdir($dir_images);
    mkdir($dir_thumbnails);
}

?>