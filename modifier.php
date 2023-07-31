<?php
session_start();
require_once("connect.php");

// Récupération des informations du produit
$id = "";
if (isset($_GET["id"]) && !empty($_GET['id'])) {
    $id = strip_tags($_GET['id']);

    // Récupérer les informations du produit et de sa catégorie depuis les tables produits et categorie
    $sql = "SELECT * FROM projet WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch();

    if ($_POST) {
        if (
            isset($_POST["titre"]) &&
            isset($_POST["description"])
        ) {

            $titre = strip_tags($_POST["titre"]);
            $description = strip_tags($_POST["description"]);

            // Vérifier si une nouvelle image est téléchargée
            $image = $produit['image']; // Utiliser l'image existante par défaut
            if (!empty($_FILES["image"]["name"])) {
                $image = $_FILES["image"]["name"]; // Nom du fichier téléchargé
                $image_temp = $_FILES["image"]["tmp_name"]; // Chemin temporaire du fichier téléchargé
                $image_destination = './image/' . $image; // Chemin de destination du fichier

                if (move_uploaded_file($image_temp, $image_destination)) {
                    // Image téléchargée avec succès
                    $produit['image'] = $image; // Mettre à jour le nom de l'image dans les données du produit
                } else {
                    echo 'Une erreur est survenue lors du téléchargement du fichier.';
                }
            }

            // Mise à jour du produit dans la table "projet"
            $sql = "UPDATE projet SET titre=:titre, description=:description, image=:image WHERE id = :id";
            $query = $db->prepare($sql);
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->bindValue(":titre", $titre, PDO::PARAM_STR);
            $query->bindValue(":description", $description, PDO::PARAM_STR);
            $query->bindValue(":image", $image, PDO::PARAM_STR);
            $query->execute();

            $_SESSION["toast_message"] = "Projet $id modifié avec succès";
            $_SESSION["toast_type"] = "success";

            header("Location: historique.php");
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit();
}

require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="modifier.css">
    <title>Modification de projet</title>
</head>

<body>
    <div class="container">
        <h1>Modification du projet n° <?= $id ?></h1>
        <form method="post" enctype="multipart/form-data">
            <div class="label-container">
                <label for="titre">Titre</label>
                <div class="input-container">
                    <input type="text" name="titre" id="titre" required value="<?= $produit['titre'] ?>">
                </div>
            </div>
            <div class="label-container">
                <label for="description">Description</label>
                <div class="input-container">
                    <textarea name="description" required><?= $produit['description'] ?></textarea>
                </div>
            </div>
            <div>
                <label for="image">Image</label>
                <img src="image/<?= $produit['image'] ?>" alt="Product Image">
            </div>
            <div>
                <label for="image_upload">Télécharger une nouvelle image</label>
                <input type="file" name="image" id="image_upload">
            </div>
            <input type="hidden" value="<?= $produit["id"] ?>" name="id">
            <div>
                <input type="submit" class="submit-button" value="Enregistrer">
                <a href="historique.php" id="retour">Retour</a>
            </div>
        </form>
    </div>

</body>

</html>