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

  $mysqli = new mysqli($server, $username, $password, $database);

  $sql = "SELECT * FROM history ORDER BY id DESC LIMIT 1";

  if (!$result = $mysqli->query($sql))
  {
    echo "erreur pendant la requête <br/>";
    echo $mysqli->errno . " " . $mysqli->error . "<br/>";
  }

  $last_entry = "";
  $offset = 0;
  echo "<br/>num rows : " . $result->num_rows . "<br/>";
  if ($result->num_rows === 1)
  {
    $row = $result->fetch_assoc();
    $last_entry = $row['last_value'];
    $offset = $row['offset'];
    echo "ok - " . $last_entry;
  }

  $files = scandir($dir_images);

  echo strval($last_entry) . " " . strval($offset) . "<br/>";
  if($last_entry == "")
  {
    $i = 2;
    echo "not good";
  }
  else
  {
    $i = intval($offset);
    echo "<br/> offset : " . intval($offset) . "<br/>";
  }

  $files = array_map('strtolower', $files);
  sort($files);

  for(; $i < sizeof($files); $i++)
  {
    echo $i . " " . $files[$i] . " ";
    $startScan = microtime(true);
    make_thumbnails($files[$i]);
    $endScan = microtime(true);
    echo ($endScan-$startScan) . "<br/>";
  }

  if ($stmt = $mysqli->prepare("INSERT INTO history (last_value, offset) VALUES(?, ?)"))
  {
    $stmt->bind_param("si", $files[$i - 1], $i);
    $stmt->execute();
    $stmt->close();
    echo "<br/>good request";
  }

  $result->free();
  $mysqli->close();


}

?>