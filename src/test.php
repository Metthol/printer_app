<!DOCTYPE html>
<html>

<head>
  <title>Printer App Ephemere</title>
  <link href="css/bs/bootstrap.css" rel="stylesheet" />
  <link href="css/bs/bootstrap-theme.css" rel="stylesheet" />
  <link href="css/bs/bootstrap-grid.css" rel="stylesheet" />
  <script src="js/bs/bootstrap.min.js"></script>
   <script src="js/bs/jquery-3.3.1.min.js"></script>
   <script src="js/bs/require.js"></script>

  <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>

  <div class="row selection">
      <div class="col-sm-8 catalogue" id="choix">
      <?php

include $_SERVER['DOCUMENT_ROOT'] . "/src/php/init_session.php";
include $_SERVER['DOCUMENT_ROOT'] . "/src/php/reduction_image.php";

init_session();

generate_thumbnails();

?>
    <div id="test_ajax"></div>

      </div>

      <div class="col-sm-4">
        <div class="row panier">
        </div>
        <div class="row actions">
          <div class="col-sm-4"><button type="button" class="btn btn-primary" id="rafraichir_bouton">Rafraichir</button></div>
          <div class="col-sm-4"><button type="button" class="btn btn-success">e</button></div>
          <div class="col-sm-4"><button type="button" class="btn btn-danger">Effacer</button></div>
        </div>
      </div>

  </div>

</body>

<script>

$('#rafraichir_bouton').on('click', function(event) {
  event.preventDefault(); // To prevent following the link (optional)
  refresh();
});

display_thumbnails(1);

var catalogue = document.getElementById("choix");

function refresh()
{
    console.log("refresh");

    $.ajax({ url: 'php/reduction_image.php',
         data: {action:'generate'},
         type: 'post',
         success: function(output) {
                      console.log("super");
                  },
        complete: function(output) {
            display_thumbnails(0);
        },
});


}

function display_thumbnails(full_preview)
{
        $.ajax({
        type: 'POST',
        url: 'php/reduction_image.php',
        data: {action:'get_images', full:full_preview},
        success: function(result) {
            $('#test_ajax').html(result);
            console.log(result);
            result = $.parseJSON(result);
            for(var i = 0; i < result.length; i++)
            {
                var img = document.createElement("img");
                img.src="../thumbnails/" + result[i];
                console.log(result[i]);
                catalogue.appendChild(img);

            }
              
        },
    });
}

</script>

</html>