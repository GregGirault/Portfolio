<?php
session_start();

require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["image"]) &&
        !empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_POST["image"])
    ) {
        $titre = strip_tags($_POST["titre"]);
        $description = strip_tags($_POST["description"]);
        $image = strip_tags($_POST["image"]);

        $sql = "INSERT INTO projet (titre, description, image) 
                VALUES (:titre, :description, :image)";
        $query = $db->prepare($sql);
        $query->bindValue(":titre", $titre, PDO::PARAM_STR);
        $query->bindValue(":description", $description, PDO::PARAM_STR);
        $query->bindValue(":image", $image, PDO::PARAM_STR);
        $query->execute();

        $_SESSION["toast_message"] = "Ajouté avec succès";
        $_SESSION["toast_type"] = "success";

        header("Location: historique.php");
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}


// pagination
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) $_GET['page'];
} else {
    $currentPage = 1;
}

$sql = 'SELECT COUNT(*) AS nb_articles FROM `projet`';
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetch();
$nbArticles = (int) $results['nb_articles'];

$parPage = 5;
$pages = ceil($nbArticles / $parPage);
$premier = ($currentPage - 1) * $parPage;

$sql = 'SELECT * FROM `projet` ORDER BY `titre` ASC LIMIT :premier, :parpage';
$query = $db->prepare($sql);
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
$query->execute();
$articles = $query->fetchAll(PDO::FETCH_ASSOC);

$successMsg = $_SESSION["toast_message"] ?? '';
$errorMsg = $_SESSION["toast_error"] ?? '';
unset($_SESSION["toast_message"]);
unset($_SESSION["toast_error"]);

require_once("close.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="historique.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Historique des projets</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


    <?php if (isset($_SESSION["toast_message"]) && isset($_SESSION["toast_type"])) : ?>

    <?php
        unset($_SESSION["toast_message"]);
        unset($_SESSION["toast_type"]);
    endif;
    ?>
</head>

<body>
    <h1>Historique des ajouts</h1>

    <table>
        <thead>
            <tr>
                <th class="text-left">Titre</th>
                <th class="text-left">Description</th>
                <th class="text-center">Image</th>
                <th class="text-center">Modifier / Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $produit) { ?>
                <tr>
                    <td class="text-left title">
                        <span><?= $produit['titre'] ?></span>
                    </td>
                    <td class="text-left description">
                        <span><?= $produit['description'] ?></span>
                    </td>
                    <td class="text-center">
                        <img src="image/<?= $produit['image'] ?>" alt="Product Image">
                    </td>
                    <td class="text-center">
                        <div class="button-wrapper">
                            <a class="modify-link btn-modif" href="modifier.php?id=<?= $produit['id'] ?>" onclick="confirmModif(event)">Modifier</a>
                        </div>

                        <a class="delete-link btn-suppr" href="supprimer.php?id=<?= $produit['id'] ?>" onclick="confirmDelete(event)">Supprimer</a>

                        <?php if ($produit['archived'] == FALSE) : ?>
                            <a class="archive-link btn-archiv" href="archiver.php?id=<?= $produit['id'] ?>" onclick="confirmArchive(event)">
                                <i class="fas fa-archive"></i>
                            </a>
                        <?php else : ?>
                            <a class="unarchive-link btn-désarchiv" href="desarchiver.php?id=<?= $produit['id'] ?>" onclick="confirmUnarchive(event)">
                                <i class="fas fa-box-open"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>


    <nav>
        <ul class="pagination">
            <li class="pagination-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage - 1; ?>" class="pagination-link">&laquo; Précédente</a>
            </li>

            <?php for ($page = 1; $page <= $pages; $page++) { ?>
                <li class="pagination-item">
                    <a href="./historique.php?page=<?php echo $page; ?>" class="pagination-link <?php if ($currentPage == $page) echo 'active'; ?>"><?php echo $page; ?></a>
                </li>
            <?php } ?>

            <li class="pagination-item <?php if ($currentPage == $pages) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage + 1; ?>" class="pagination-link">Suivante &raquo;</a>
            </li>
        </ul>
    </nav>

    <div class="bottom-links">
        <a class="button-add" href="ajout.php">Ajouter</a>
        <a class="button-logout" href="deconnexion.php">Déconnexion</a>
    </div>

    <br><br>
    <br>

    <?php require_once("close.php"); ?>
    <div id="toast" class="toast hidden" data-message="<?= $successMsg ?>" data-error="<?= $errorMsg ?>">
        <div id="toast-message"></div>
    </div>
    <div id="confirmation-toast" class="toast hidden">
        <div id="confirmation-toast-message"></div>
        <button id="confirmation-toast-confirm" class="confirm">Confirmer</button>
        <button id="confirmation-toast-cancel" class="cancel">Annuler</button>
    </div>

    <script src="historique.js"></script>
</body>

</html>