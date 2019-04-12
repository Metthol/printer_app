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
   <script src="js/exif.js"></script>

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

  <div id="last_exports" style="position: absolute; bottom: 10px; left: 0;"></div>

</body>

<script>

var darkModeEnabled = true;
var backgroundColor = "#FFFFFF";
var exportIncrement = 0;
var lastThreeExports = [];
var preview = false;
var img_overlay_rotate = 0;

$(document).keypress(function(e) {
  // r = 114 ; e = 101; c = 99; 27 = escape; h = 104; s = 115; d = 100; i = 105; t = 116
  switch(e.which){
    case 99: // 'c' key; cleans backet
      effacer_selection();
      break;

    case 101: // 'e' key; makes export
      exporter();
      break;

    case 114: // 'r' key; refreshes images list, or rotates photo (depending on mode in which user is)
      if(preview)
        rotatePreviewedImage();
      else
        refresh();
      break;

    /*case 27: // Escape from pict
      off();
      break;*/

    case 13: // 'Enter ' key
      if(preview)
        off();
      break;

    case 104: // 'h' key; hide all pictures from current view
      hideAllThumbnails();
      break;

    case 115: // 's' press; show all images
      showAllThumbnails();
      break;

    case 100: // 'd' key press; switch between light/dark mode
      switchDarkLightMode();
      break;

     case 105:
      defineIncrement(parseInt(prompt("Next export increment ?", exportIncrement)));
      break;

     case 116:
      if(preview)
        rotatePreviewedImage();
      break;

    default:
      break;
  }
});

// Switch to dark or light mode
function switchDarkLightMode(){
  darkModeEnabled = !darkModeEnabled;
  setLightMode();
}

// Simply applies mode colors
function setLightMode(){
  backgroundColor = darkModeEnabled ? "#635A5A" : "#FFFFFF";
  $(".catalogue").css("background-color", backgroundColor)
  $(".container_hover").css("background-color", backgroundColor)
}

function defineIncrement(incr){
  exportIncrement = parseInt(incr);
}

function rotatePreviewedImage()
{
  var img = document.getElementById('image_overlay');
  img_overlay_rotate -= 90;
  img.style.transform = 'rotate(' + img_overlay_rotate + 'deg)';
}

/*
function getExif() {
  var img1 = document.getElementById("image_overlay");
  EXIF.getData(img1, function() {
    console.log(EXIF.getAllTags(this));
  });
};
*/

// Simply adds current export qty to array
function addExportToLocalHistory(qty, dir_id)
{
  lastThreeExports.push({
    id: exportIncrement,
    qty: qty,
    dir_id: dir_id
  })
  if(lastThreeExports.length > 3){
    lastThreeExports = [
      lastThreeExports[lastThreeExports.length-1],
      lastThreeExports[lastThreeExports.length-2],
      lastThreeExports[lastThreeExports.length-3]
    ]
  }
  exportIncrement++;

  displayLocalExportHistory();
}

function displayLocalExportHistory()
{
  var last_exports = $("#last_exports");
  last_exports.html("");
  /*for(var i = 0; i < lastThreeExports.length; i++){
    var exportt = lastThreeExports[i];
    last_exports.append("<p>Export n°" + exportt.id + "(" + exportt.dir_id + ") : " + exportt.qty + " photos</p>");
  }*/
}

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
setLightMode();

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
    var items = document.getElementsByClassName("image_to_print");

    if(items.length == 0){ // No item to export
      toast.error("Aucune image sélectionnée pour l'exportation", "", true, "2800", true, true)
      return false;
    }

    var liste = [];
    var qte = [];
    var allQte = 0;

    for(var i = 0; i < items.length; i++)
    {
        console.log(items.item(i).getElementsByClassName("image_print_class")[0].getAttribute("nom_image"));
        console.log(items.item(i).getElementsByClassName("value_print_class")[0].getAttribute("value"));
        liste.push(items.item(i).getElementsByClassName("image_print_class")[0].getAttribute("nom_image"));
        // Get value even if it has been manually changed in field
        t_qte = parseInt(items.item(i).getElementsByClassName("value_print_class")[0].valueAsNumber);
        qte.push(t_qte);
        allQte += t_qte;
        // qte.push(items.item(i).getElementsByClassName("value_print_class")[0].getAttribute("value"));
    }
    console.log(liste);
    console.log(qte);
    toast.success("Export en cours", "L'export de " + allQte + " photos est en cours", false, "10000", true, true);

    var listeString = JSON.stringify(liste);
    var qteString = JSON.stringify(qte);

    $.ajax({
        type: "POST",
        url: 'php/reduction_image.php',
        data: {action:'export', liste_image:listeString, qte_image:qteString}, 
        cache: false,

        success: function(data){
            alert("Export terminé !\n\nRetrouvez les photos dans le dossier " + ("dir_name" in data ? data.dir_name : "output/xx"));
            console.log(data);
            toastr.remove() // Hard remove
            addExportToLocalHistory(allQte, data.dir_name);
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
            toast.success("Rafraîchi", "", true, "800", false)
            display_thumbnails(0);
        },
    });

}

function hideThumbnail(pict_id){
  var picts = document.getElementsByClassName("container_hover")
  $(picts[pict_id]).hide()
}

function hideAllThumbnails(){
  $(".container_hover").hide();
}

function showAllThumbnails(){
  $(".container_hover").show();
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
                img.setAttribute("onclick", "on(\"" + result[i].url +"\", " + result[i].rotate + ")");
                my_div.setAttribute("css", "background-color: " + backgroundColor);
                img.src="../thumbnails/thumbnail_" + result[i].url;
                img.id = "image_catalogue-" + nb_pictures.toString();



                var div_selection = document.createElement("div");
                div_selection.className = "input-group";
                div_selection.innerHTML = '<button type="button" class="btn btn-danger" onclick="change_qty(-1, ' + nb_pictures.toString() + ', \'' + result[i].url +'\')">-</button><button type="button" class="btn btn-success" onclick="change_qty(1, ' + nb_pictures.toString() + ', \'' + result[i].url +'\')">+</button><button style="position: absolute; right: 0;" type="button" class="btn btn-danger" onclick="hideThumbnail(' + nb_pictures + ')"><img style="max-width: 16px;" src="img/trash.png"></button>';

                my_div.appendChild(img);
                my_div.appendChild(div_selection);
                catalogue.appendChild(my_div);

                nb_pictures = nb_pictures + 1;

            }
            setLightMode();
              
        },
    });
}

function on(chemin, rotate) {
  preview = true;
  document.getElementById("overlay").style.display = "block";
  var img = document.getElementById("image_overlay");
  img.src="../images/" + chemin;
  img_overlay_rotate = -parseInt(rotate);
  img.style.transform = 'rotate(' + img_overlay_rotate + 'deg)';
}

function off() {
  preview = false;
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

function getOrientation(file, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {

        var view = new DataView(e.target.result);
        if (view.getUint16(0, false) != 0xFFD8)
        {
            return callback(-2);
        }
        var length = view.byteLength, offset = 2;
        while (offset < length) 
        {
            if (view.getUint16(offset+2, false) <= 8) return callback(-1);
            var marker = view.getUint16(offset, false);
            offset += 2;
            if (marker == 0xFFE1) 
            {
                if (view.getUint32(offset += 2, false) != 0x45786966) 
                {
                    return callback(-1);
                }

                var little = view.getUint16(offset += 6, false) == 0x4949;
                offset += view.getUint32(offset + 4, little);
                var tags = view.getUint16(offset, little);
                offset += 2;
                for (var i = 0; i < tags; i++)
                {
                    if (view.getUint16(offset + (i * 12), little) == 0x0112)
                    {
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
                    }
                }
            }
            else if ((marker & 0xFF00) != 0xFF00)
            {
                break;
            }
            else
            { 
                offset += view.getUint16(offset, false);
            }
        }
        return callback(-1);
    };
    reader.readAsArrayBuffer(file);
}

  var toast = {
    error : function(title, message, progressBar=true, duration="1500", clean=false, forceClean=true){
      toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": progressBar,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": duration,
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      if(clean)
        if(forceClean)
          toastr.remove()
        else
          toastr.clear()
      toastr["error"](message, title)
    },
    success : function(title, message, progressBar=true, duration="1500", clean=false, forceClean=true){
      toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": progressBar,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": duration,
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      if(clean)
        if(forceClean)
          toastr.remove()
        else
          toastr.clear()
      toastr["success"](message, title)

    }
  }


</script>


</html>