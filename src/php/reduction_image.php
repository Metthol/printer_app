<?php

function make_thumbnails($name)
{
  $img = imagecreatefromjpeg("../images/");
  
  $img_width = imagesx($img);
  $img_height = imagesy($img);

  $thumbnail_width = 200;
  $thumbnail_height = (200 / $img_width) * $img_height;

  $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
  imagecopyresized($new_image, $img, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $img_width, $img_height);
  imagejpeg($new_image, "../thumbnail/thumbnail_1.jpg");
}

function generate_thumbnails()
{

}

?>