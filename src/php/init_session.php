<?php

function init_session()
{
    include $_SERVER['DOCUMENT_ROOT']. "/src/php/variables.php";

    echo $dir_images;
    echo "<br/>";
    echo $dir_thumbnails;

    if(!file_exists($dir_images))
    {
        mkdir($dir_images);
    }

    if(!file_exists($dir_thumbnails))
    {
        mkdir($dir_thumbnails);
    }
}

?>