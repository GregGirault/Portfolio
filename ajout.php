<?php
session_start();

require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_POST) {
    if (
        isset($_POST["titre"]) && isset($_POST["description"]) &&
        isset($_FILES["image"])
    ) {
        $titre = strip_tags($_POST["titre"]);
        $description = strip_tags($_POST["description"]);

        // Gérer le téléchargement de l'image
        $image = $_FILES["image"]["name"]; // Nom du fichier téléchargé
        $image_temp = $_FILES["image"]["tmp_name"]; // Chemin temporaire du fichier téléchargé
        $image_destination = './image/' . $image; // Chemin de destination du fichier

        if (move_uploaded_file($image_temp, $image_destination)) {
            // Image téléchargée avec succès

            $sql = "INSERT INTO projet (titre, description, image)
            VALUES (:titre, :description, :image)";
            $query = $db->prepare($sql);
            $query->bindValue(":titre", $titre, PDO::PARAM_STR);
            $query->bindValue(":description", $description, PDO::PARAM_STR);
            $query->bindValue(":image", $image, PDO::PARAM_STR);
            $success = $query->execute();

            if ($success) {
                $_SESSION["successMsg"] = "Ajout du projet réussi!";
                header("Location: " . $_SERVER['REQUEST_URI']); // Redirige pour éviter le rechargement du formulaire
                exit();
            } else {
                $error = "Erreur lors de l'ajout du projet : " . $query->errorInfo()[2];
            }
        } else {
            $error = "Une erreur est survenue lors du téléchargement de l'image.";
        }
    } else {
        header("Location: login.php");
        exit();
    }
}

$successMsg = $_SESSION["successMsg"] ?? '';
unset($_SESSION["successMsg"]); // Supprime le message de succès de la session après qu'il ait été récupéré

require_once("close.php");
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ajout.css">
    <title>Ajout de projets</title>
</head>

<body>
    <h1>Ajout de projets</h1>
    <?php if (isset($error)) : ?>
        <div><?= $error ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" id="myForm">
        <div>
            <label for="titre" id="label1">Titre</label>
            <input type="text" name="titre" id="titre" required>
        </div>
        <div>
            <label for="description" id="label2">Description</label>
            <textarea name="description" required id="textarea"></textarea>
        </div>


        <?php
        if (isset($_POST['envoyer'])) {
            $dossierTempo = $_FILES['image']['tmp_name'];
            $dossierSite = './image/' . $_FILES['image']['name'];

            $tailleMax = 3 * 1024 * 1024;


            if ($_FILES['image']['size'] > $tailleMax) {
                echo 'La taille du fichier dépasse la limite autorisée.';
                // Arrêtez l'exécution du script ou effectuez une autre action appropriée.
            }

            $mime = mime_content_type($_FILES['image']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($mime, $allowedTypes)) {
                echo 'Type de fichier non autorisé.';
            }

            $deplacer = move_uploaded_file($dossierTempo, $dossierSite);
            if ($deplacer) {
                chmod($dossierSite, 0777);

                echo 'Image envoyée avec succès';
            } else {
                echo 'Une erreur est survenue lors du téléchargement du fichier.';
            }
        }
        ?>


        <div>
            <label for="upload" id="label3">Envoyer image</label>
            <input type="file" name="image" id="upload">

        </div>

        <div>
            <input type="submit" value="Ajouter" id="submitButton">
        </div>
        <div>
            <!-- <a href="historique.php" class="text-black-500 mr-8 hover:underline bg-gray-200 rounded px-2 py-1 hover:bg-gray-600 hover:text-white">Historique</a>
            <a href="modifier.php?id=<?= $produit_id ?>" class="text-blue-500 mr-8 hover:underline bg-blue-200 rounded px-2 py-1 hover:bg-blue-400 hover:text-white">Modifier</a>
            <a href="supprimer.php?id=<?= $produit_id ?>" class="text-red-500 mr-8 hover:underline bg-red-200 rounded px-2 py-1 hover:bg-red-400 hover:text-white">Supprimer</a> -->
            <a href="#" onclick="history.back()" id="retour" class="animated-link">Retour</a>





        </div>
    </form>
    <div id="toast" class="toast hidden" data-message="<?= $successMsg ?? '' ?>">
        <div id="toast-message"></div>
    </div>
    <script src="ajout.js"></script>
</body>

</html>