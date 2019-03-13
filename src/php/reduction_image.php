<?php

function make_thumbnails($name)
{
  include $_SERVER['DOCUMENT_ROOT']. "/src/php/variables.php";

  $img = imagecreatefromjpeg($dir_images . $name);
  
  $img_width = imagesx($img);
  $img_height = imagesy($img);

  $thumbnail_width = 200;
  $thumbnail_height = (200 / $img_width) * $img_height;

  $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
  imagecopyresized($new_image, $img, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $img_width, $img_height);
  imagejpeg($new_image, $dir_thumbnails . "/thumbnail_" . $name);
}

function generate_thumbnails()
{
  include $_SERVER['DOCUMENT_ROOT']. "/src/php/variables.php";

  mysql_connect($server, $username, $password)
  mysql_select_db(strval($database));

  );

  $result = mysql_query("SELECT * FROM history ORDER BY id DESC LIMIT=1");
  $last_entry = 0;

  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $last_entry = $row[1];
    $offset = $row[2];
  }

  $files = scandir($dir_images);
  print_r($files);

  if(last_entry == 0)
    $i = 2;
  else
    $i = intval($offset);

  for($i; $i < sizeof($files); $i++)
  {
    echo $files[$i] . " ";
    $startScan = microtime(true);
    make_thumbnails($files[$i]);
    $endScan = microtime(true);
    echo ($endScan-$startScan) . "<br/>";
  }

  mysql_query("INSERT INTO history ('last_value', 'offset') VALUES($files[$i - 1], $i)");

  mysql_free_result($result);
  mysql_close();


}

?>