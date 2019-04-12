# Interface web d'application de filigranes

Dossiers:

src/* : contient les sources html, js, css du projet
images/* : contient les images du projet
assets/* : contient les différentes ressources du projet
bootstrap/* : contient les fichiers sources non modifiés de bootstrap
old_python/* : contient l'ancien projet en python

src/css/* : contient les feuilles de styles du projet, dont bootstrap
src/js/* : contient les différents scrips du projet, dont bootstrap
src/img/* : contient les images utilisées sur le site

## WARNING

Avant de commencer à utiliser l'application, vérifiez que vous pouvez exporter des photos en portrait; leur traitement est en effet plus gourmand en mémoire car il nécessite une rotation de l'image. Si l'export plante (vous n'êtes toujours pas notifié de sa fin après 15-20 secondes), augmentez la mémoire allouée à PHP.

## Instructions
### Première installation

- Créer un dossier `images` à la racine (même niveau que `src` et `output`), qui contiendra les photos capturées et à traiter.
- Importer le fichier `src/printer_app.sql` dans la base de données via phpMyAdmin ou Adminer.

### Réutilisation (nettoyage des fichiers & db)
Pour vider la base de données et nettoyer les dossier `output` et `thumbnails`, chargez la page `http://{PRINTER_APP--ROOT}/src/php/clean_db.php`

## Utilisation

Pour accéder à l'application, chargez la page `http://{PRINTER_APP--ROOT}/src/test.php`.

Lorsqu'un client souhaite imprimer des photos :
- Rafraîchir la liste de photos, avec le bouton ou le raccourci, pour avoir les dernières photos capturées
- Choisir les photos à imprimer, et la quantité, grâce aux boutons "+" et "-" en bas à gauche des photos, ou du champ de texte qui apparaît à droite de la photo, dans le panier
- Cliquer sur "Exporter" (ou raccourci clavier) pour lancer la copie des fichiers. Une pop-up vous avertira lorsque l'export sera terminé
- Une fois l'export terminé, il ne reste plus qu'à imprimer les photos : dans l'explorateur de fichiers, se rendre dans le dossier `PRINTER_APP--ROOT_DIR/output/xx/`, où `xx` est le nom de dossier qui vous a été donné dans la pop-up de confirmation de l'export (à priori, le dernier dossier créé, celui avec l'ID le plus grand).
- Sélectionner toutes les photos du dossier, et imprimez-les; les quantités seront bien respectées.

### Raccourcis clavier

- Rafraîchir la liste des photos capturées : `r` (refresh)
- Exporter les photos du panier : `e` (export)
- Vider le panier : `c` (clear)
- Afficher / masquer l'ensemble des photos capturées : `s` (show) / `h` (hide)
- Basculer entre mode sombre / lumineux : `d` (dark)