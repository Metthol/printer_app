<?php

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'generate' : generate_thumbnails();break;
        case 'get_images' : get_images($_POST['full']);break;
        case 'export' : header('Content-Type: application/json'); echo exporter($_POST['liste_image'], $_POST['qte_image']);break;
    }
}

function exporter($li, $qi)
{
  include "variables.php";
  
  $lli = json_decode($li);
  $qqi = json_decode($qi);

  $stamp1 = imagecreatefrompng("../../assets/watermark.png");
  $stamp2 = imagecreatefrompng("../../assets/rdt.png");

  $mr = 105;
  $mb = 80;
  $sx1 = imagesx($stamp1);
  $sy1 = imagesy($stamp1);

  $sx2 = imagesx($stamp2);
  $sy2 = imagesy($stamp2);

  $files = scandir("../../output/");

  $nbdir = sizeof($files);

  if ($nbdir < 10)
    $nbdir = "00" . strval($nbdir);
  else if($nbdir < 100)
    $nbdir = "0" . strval($nbdir);
  else
    $nbdir = strval($nbdir);

  mkdir("../../output/" . $nbdir);

  for($i = 0; $i < sizeof($lli); $i++)
  {
    $im = imagecreatefromjpeg("../../images/" . $lli[$i]);

    if(!imagecopy($im, $stamp1, imagesx($im) - $sx1 - $mr, imagesy($im) - $sy1 - $mb, 0, 0, $sx1, $sy1))
      error_log("probleme watermark");

    if(!imagecopy($im, $stamp2, $mr, imagesy($im) - $sy2 - $mb, 0, 0, $sx2, $sy2))
      error_log("probleme watermark 2");

    for($j = 0; $j < $qqi[$i]; $j++)
    {
      imagejpeg($im, "../../output/" . $nbdir . "/image_" . strval($i) . '-' . strval($j) . ".jpg");
    }

    imagedestroy($im);
    
  }

  copy($ps_printing_script, "../../output/" . $nbdir . "/Print.ps1");

  return json_encode(array(
    "dir_name" => $nbdir
  ));
}

function get_images($full_preview)
{
  $array = array();

  include "variables.php";
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
      array_push($array, $row['picture']);
    }
  }

  echo json_encode($array);
}

function make_thumbnails($name)
{
  include "variables.php";

  $img = imagecreatefromjpeg($dir_images . $name);
  
  $img_width = imagesx($img);
  $img_height = imagesy($img);

  $thumbnail_height = 0;
  $thumbnail_width = 0;

  if ($img_width > $img_height) {
    $thumbnail_width = 400;
    $thumbnail_height = (400 / $img_width) * $img_height;
  } else {
    $thumbnail_width = 400;
    $thumbnail_height = (400 / $img_width) * $img_height;
    $thumbnail_width = ($thumbnail_height / $img_height) * $img_width;

  }

  $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
  imagecopyresized($new_image, $img, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $img_width, $img_height);
  imagejpeg($new_image, $dir_thumbnails . "/thumbnail_" . $name);
}

function generate_thumbnails()
{
  include "variables.php";

  $mysqli = new mysqli($server, $username, $password, $database);

  $sql = "SELECT * FROM history ORDER BY id DESC LIMIT 1";

  if (!$result = $mysqli->query($sql))
  {
    echo "erreur pendant la requête <br/>";
    echo $mysqli->errno . " " . $mysqli->error . "<br/>";
  }

  $last_entry = "";
  $offset = 0;
  if ($result->num_rows === 1)
  {
    $row = $result->fetch_assoc();
    $last_entry = $row['last_value'];
    $offset = $row['offset'];
  }

  $files = scandir($dir_images);

  if($last_entry == "")
  {
    $i = 2;
  }
  else
  {
    $i = intval($offset);
  }

  $files = array_map('strtolower', $files);
  sort($files);

  $j = -1;

  for(; $i < sizeof($files); $i++)
  {
    if(!is_dir($dir_images."/" .$files[$i])){
      $startScan = microtime(true);
      make_thumbnails($files[$i]);
      $endScan = microtime(true);

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

  }


  $sql = "SELECT * FROM thumbnails ORDER BY id DESC LIMIT 1";

  if (!$result = $mysqli->query($sql))
  {
    echo "erreur pendant la requête <br/>" . $mysqli->errno . " " . $mysqli->error . "<br/>";
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
  }

  $result->free();
  $mysqli->close();


}

?>