<?php

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'generate' : generate_thumbnails();break;
        case 'get_images' : get_images($_POST['full']);break;
    }
}

function get_images($full_preview)
{
  $array = array();

  include $_SERVER['DOCUMENT_ROOT']. "/src/php/variables.php";
    $mysqli = new mysqli($server, $username, $password, $database);


  $sql = "SELECT * FROM history ORDER BY id DESC LIMIT 1";

    if (!$result = $mysqli->query($sql))
  {
    echo "erreur pendant la requête <br/>" . $mysqli->errno . " " . $mysqli->error . "<br/>";
  }

  if ($result->num_rows === 1)
  {
    $row = $result->fetch_assoc();
    $last_entry = $row['last_id'];
  }

  if ($full_preview == 1)
    $last_entry = 0;

  if ($last_entry != -1) 
  {
    $sql = "SELECT * FROM thumbnails WHERE id >= " . $last_entry . " ORDER BY id";

    if (!$result = $mysqli->query($sql))
    {
      echo "erreur pendant la requête <br/>";
      echo $mysqli->errno . " " . $mysqli->error . "<br/>";
    }

    while ($row = $result->fetch_assoc()) {
      array_push($array, $row['thumbnail']);
    }
  }

  echo json_encode($array);
}

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

  $j = -1;

  for(; $i < sizeof($files); $i++)
  {
    echo $i . " " . $files[$i] . " ";
    $startScan = microtime(true);
    make_thumbnails($files[$i]);
    $endScan = microtime(true);
    echo ($endScan-$startScan) . "<br/>";

    if ($stmt = $mysqli->prepare("INSERT INTO thumbnails (thumbnail, picture) VALUES(?, ?)"))
    {
      $name = "thumbnail_" . $files[$i];
      $stmt->bind_param("ss", $name, $files[$i]);
      $stmt->execute();
      $stmt->close();
    }

    if ($j == -1)
    {
        $sql2 = "SELECT * FROM thumbnails ORDER BY ID DESC LIMIT 1";
        if (!$result2 = $mysqli->query($sql2))
        {
          echo "erreur pendant la requête <br/>" . $mysqli->errno . " " . $mysqli->error . "<br/>";
        }

        if ($result2->num_rows === 1)
        {
          $row2 = $result2->fetch_assoc();
          $j = intval($row2['id']);
        }
    }

  }


  $sql = "SELECT * FROM thumbnails ORDER BY id DESC LIMIT 1";

  if (!$result = $mysqli->query($sql))
  {
    echo "erreur pendant la requête <br/>";
    echo $mysqli->errno . " " . $mysqli->error . "<br/>";
  }

  $last_id = 0;

  if ($result->num_rows === 1)
  {
    $row = $result->fetch_assoc();
    $last_id = intval($row['id']);
  }


  if ($stmt = $mysqli->prepare("INSERT INTO history (last_value, offset, last_id) VALUES(?, ?, ?)"))
  {
    $val_offset = $i;
    $stmt->bind_param("sii", $files[$i - 1], $val_offset, $j);
    $stmt->execute();
    $stmt->close();
    echo "<br/>good request";
  }

  $result->free();
  $mysqli->close();


}

?>