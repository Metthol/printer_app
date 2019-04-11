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

  <link rel="stylesheet" href="css/toastr.scss">
  <script src="js/bs/toastr.js"></script>

  <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>

 <div id="overlay" onclick="off()"><img src="" id="image_overlay"/></div> 

  <div class="row selection">
      <div class="col-sm-8 catalogue" id="choix">
      <?php

include "php/init_session.php";
include "php/reduction_image.php";

init_session();

generate_thumbnails();

?>

            <div class="row" id="display"></div>
      </div>

      <div class="col-sm-4">
        <div class="row" id="panier">
        </div>
        <div class="row actions">
          <div class="col-sm-4"><button type="button" class="btn btn-primary" id="rafraichir_bouton">Rafraichir</button></div>
          <div class="col-sm-4"><button type="button" class="btn btn-success" id="exporter_bouton">Exporter</button></div>
          <div class="col-sm-4"><button type="button" class="btn btn-danger" id="effacer">Effacer</button></div>
        </div>
      </div>

  </div>

</body>

<script>

$(document).keypress(function(e) {
  // r = 114 ; e = 101; c = 99
  switch(e.which){
    case 99: // 'c' key
      effacer_selection();
      break;

    case 101: // 'e' key
      exporter();
      break;

    case 114:
      refresh();
      break;

    default:
      break;
  }
});

$('#rafraichir_bouton').on('click', function(event) {
  event.preventDefault(); // To prevent following the link (optional)
  refresh();
});

$('#exporter_bouton').on('click', function(event)
{
    event.preventDefault();
    exporter();
}
);

$('#effacer').on('click', function(event)
{
    event.preventDefault();
    effacer_selection();
}
);

display_thumbnails(1);

var catalogue = document.getElementById("display");
var panier = document.getElementById("panier");

var nb_pictures = 0;

function effacer_selection()
{
    while (panier.firstChild) {
        panier.removeChild(panier.firstChild);
    }
}

function exporter()
{
    // Pop-up
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "10000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    toastr.remove() // Hard remove
    // toastr.clear() // Soft remove (with animation)
    toastr["success"]("L'export est en cours", "Export en cours")


    var items = document.getElementsByClassName("image_to_print");
    if(items.length == 0){ // No item to export
      toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "2800",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      toastr.remove() // Hard remove
      // toastr.clear() // Soft remove (with animation)
      toastr["error"]("", "Aucune image sélectionnée pour l'exportation")
      return false;
    }

    var liste = [];
    var qte = [];

    for(var i = 0; i < items.length; i++)
    {
        console.log(items.item(i).getElementsByClassName("image_print_class")[0].getAttribute("nom_image"));
        console.log(items.item(i).getElementsByClassName("value_print_class")[0].getAttribute("value"));
        liste.push(items.item(i).getElementsByClassName("image_print_class")[0].getAttribute("nom_image"));
        qte.push(items.item(i).getElementsByClassName("value_print_class")[0].getAttribute("value"));
    }
    console.log(liste);
    console.log(qte);

    var listeString = JSON.stringify(liste);
    var qteString = JSON.stringify(qte);

    $.ajax({
        type: "POST",
        url: 'php/reduction_image.php',
        data: {action:'export', liste_image:listeString, qte_image:qteString}, 
        cache: false,

        success: function(){
            alert("OK");
            toastr.remove() // Hard remove
        }
    });
}

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
            toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "800",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            toastr.remove() // Hard remove
            // toastr.clear() // Soft remove (with animation)
            toastr["success"]("", "Rafraîchi")
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
            result = $.parseJSON(result);
            for(var i = 0; i < result.length; i++)
            {
                var my_div = document.createElement("div");
                my_div.className = "col-sm-4 container_hover";
                var img = document.createElement("img");
                img.className = "image_hover";
                img.setAttribute("onclick", "on(\"" + result[i] +"\")");
                img.src="../thumbnails/thumbnail_" + result[i];
                img.id = "image_catalogue-" + nb_pictures.toString();



                var div_selection = document.createElement("div");
                div_selection.className = "input-group";
                div_selection.innerHTML = '<button type="button" class="btn btn-danger" onclick="change_qty(-1, ' + nb_pictures.toString() + ', \'' + result[i] +'\')">-</button><button type="button" class="btn btn-success" onclick="change_qty(1, ' + nb_pictures.toString() + ', \'' + result[i] +'\')">+</button>';

                my_div.appendChild(img);
                my_div.appendChild(div_selection);
                catalogue.appendChild(my_div);

                nb_pictures = nb_pictures + 1;

            }
              
        },
    });
}

function on(chemin) {
  document.getElementById("overlay").style.display = "block";
  var img = document.getElementById("image_overlay");
  img.src="../images/" + chemin;
}

function off() {
  document.getElementById("overlay").style.display = "none";
}

function change_qty(qte, id, value)
{
    var img_ori = document.getElementById("image_catalogue-" + id.toString());

    var img_qte = document.getElementById("image_qte_" + id.toString());
    var img = document.getElementById("image_" + id.toString());

    if(img_qte === null)
    {
        if(qte != "-1")
        {
            var my_div = document.createElement("div");
            my_div.className = "col-sm-12 image_to_print";
            my_div.id = "item_image" + id.toString();
            var new_img = document.createElement("img");
            new_img.src = img_ori.src;
            new_img.id=('image_' + id);
            new_img.setAttribute("nom_image", value);
            new_img.setAttribute("class", "image_print_class");

            img_qte = document.createElement("input");
            img_qte.id = "image_qte_" + id.toString();
            img_qte.setAttribute("type", "number");
            img_qte.setAttribute("value", "1");
            img_qte.setAttribute("class", "value_print_class")

            my_div.appendChild(new_img);
            my_div.appendChild(img_qte);
            panier.appendChild(my_div);
        }
    }
    else
    {
        var new_qte = img_qte.getAttribute("value");
        new_qte = parseInt(new_qte);
        new_qte = new_qte + parseInt(qte);
        img_qte.setAttribute("value", new_qte.toString());

        if(new_qte === 0)
        {
            console.log("better to delete");
            document.getElementById("item_image" + id.toString()).remove();
        }

        
    }
}


</script>


</html>