<?php

function init_session()
{
    include "variables.php";

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