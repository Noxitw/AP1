<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    die("Erreur : Vous devez être connecté pour voir vos comptes rendus.");
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

// Récupère l'identifiant de l'utilisateur depuis la session
$identifiant = $_SESSION['login'];

// Récupère l'id de l'utilisateur via son identifiant
$requete_utilisateur = "SELECT id FROM stagito_utilisateurs WHERE identifiant = '$identifiant'";
$resultat_utilisateur = mysqli_query($connexion, $requete_utilisateur);

if (!$resultat_utilisateur || mysqli_num_rows($resultat_utilisateur) == 0) {
    die("Erreur : Utilisateur non trouvé.");
}

$utilisateur = mysqli_fetch_assoc($resultat_utilisateur);
$id_utilisateur = $utilisateur['id'];

// Requête pour récupérer les comptes rendus
$requete_cr = "SELECT stagito_comptes_rendu.title, stagito_comptes_rendu.id, stagito_comptes_rendu.description, stagito_comptes_rendu.date_creation, stagito_classes.libellé_classe 
               FROM stagito_comptes_rendu 
               JOIN stagito_classes ON stagito_comptes_rendu.id_classe_fk = stagito_classes.id
               WHERE stagito_comptes_rendu.id_utilisateur_fk = '$id_utilisateur'
               ORDER BY stagito_comptes_rendu.date_creation DESC";


$resultat_cr = mysqli_query($connexion, $requete_cr);



// Ferme la connexion à la base plus tard
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Comptes Rendus</title>
</head>
<body>
    <h2>Mes comptes rendus</h2>
    <button><a href="index2.php"> ACCEUIL </a></button>
    <?php
    echo "<hr>";
    if (mysqli_num_rows($resultat_cr) > 0) {
        while ($cr = mysqli_fetch_assoc($resultat_cr)) {
            echo "<div>";
            echo "<h3>" . $cr['title'] . " (" . $cr['libellé_classe'] . ")</h3>";
            echo "<p><strong>Date :</strong> " . $cr['date_creation'] . "</p>";
            echo "<p>" . nl2br($cr['description']) . "</p>";
            echo "<button><a href='modif.compterendu.php?id=" . $cr['id'] . "'>Modifier</a></button>";
            echo "<hr>";
            echo "</div>";


        }
    } else {
        echo "<p>Vous n'avez encore rédigé aucun compte rendu.</p>";
    }

    mysqli_close($connexion);
    ?>
</body>
</html>
