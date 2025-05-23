<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    die("Erreur : Vous devez être connecté pour voir les comptes rendus.");
}

if (isset($utilisateur['libellé_role']) && $utilisateur['libellé_role'] == 'Professeur') {
    die("Erreur : Vous devez être connecté pour voir les comptes rendus.");
}

// Connexion à la base de données
$servername = "localhost";
$username = "u937355202_NolanFurtado";
$bddpassword = "Nolan4765&";
$dbname = "u937355202_NolanFurtadoBD";
$connexion = mysqli_connect($servername, $username, $bddpassword, $dbname);

if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Requête pour récupérer tous les comptes rendus avec les informations de l'utilisateur et de la classe
$requete_cr = "SELECT stagito_comptes_rendu.title, stagito_comptes_rendu.description, stagito_comptes_rendu.date_creation, 
                      stagito_classes.libellé_classe, stagito_utilisateurs.identifiant, stagito_utilisateurs.nom, stagito_utilisateurs.prenom
               FROM stagito_comptes_rendu
               JOIN stagito_classes ON stagito_comptes_rendu.id_classe_fk = stagito_classes.id
               JOIN stagito_utilisateurs ON stagito_comptes_rendu.id_utilisateur_fk = stagito_utilisateurs.id
               ORDER BY stagito_comptes_rendu.date_creation DESC";

$resultat_cr = mysqli_query($connexion, $requete_cr);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les Comptes Rendus</title>
    <button><a href="index2.php"> ACCEUIL </a></button>
</head>
<body>
    <h2>Comptes rendus des élèves</h2>

    <?php
    // Vérifie s'il y a des comptes rendus dans les résultats
    if (mysqli_num_rows($resultat_cr) > 0) {
        // Affiche chaque compte rendu
        while ($cr = mysqli_fetch_assoc($resultat_cr)) {
            echo "<div>";
            echo "<h3>" . $cr['title'] . " (" . $cr['libellé_classe'] . ")</h3>";
            echo "<p><strong>Elève :</strong> " . $cr['prenom'] . " " . $cr['nom'] . "</p>";
            echo "<p><strong>Date :</strong> " . $cr['date_creation'] . "</p>";
            echo "<p>" . nl2br($cr['description']) . "</p>";
            echo "<hr>";
            echo "</div>";
        }
    } else {
        // Affiche un message si aucun compte rendu n'a été trouvé
        echo "<p>Aucun compte rendu trouvé.</p>";
    }

    // Ferme la connexion à la base de données
    mysqli_close($connexion);
    ?>
</body>
</html>
