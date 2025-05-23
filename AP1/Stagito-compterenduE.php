<?php
session_start();

// Vérifie si l'utilisateur est connecté //if (!isset si ya quelque chose on affiche cette chose 
if (!isset($_SESSION['login'])) { 
    die("Erreur : Vous devez être connecté pour créer un compte rendu.");
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

// Récupère l'id de l'utilisateur dans la base via l'identifiant
$requete_utilisateur = "SELECT id FROM stagito_utilisateurs WHERE identifiant = '$identifiant'";
$resultat_utilisateur = mysqli_query($connexion, $requete_utilisateur);

if (!$resultat_utilisateur || mysqli_num_rows($resultat_utilisateur) == 0) {
    die("Erreur : Utilisateur non trouvé.");
}

$utilisateur = mysqli_fetch_assoc($resultat_utilisateur);
$id_utilisateur = $utilisateur['id'];

 
// Si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des champs du formulaire
    $title = isset($_POST['title']) ? mysqli_real_escape_string($connexion, $_POST['title']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($connexion, $_POST['description']) : '';
    $id_classe = isset($_POST['id_classe']) ? (int)$_POST['id_classe'] : 0;

    if (empty($title) || empty($description) || $id_classe == 0) {
        echo "<p style='color:red;'>Erreur : Tous les champs doivent être remplis.</p>";
    } else {
        // Requête d'insertion
        $requete_insertion = "INSERT INTO 
        stagito_comptes_rendu (id_utilisateur_fk, title, description, id_classe_fk, date_creation)
                            VALUES ('$id_utilisateur', '$title', '$description', '$id_classe', NOW())";

        if (mysqli_query($connexion, $requete_insertion)) {
            echo "<p style='color:green;'>Compte rendu créé avec succès.</p>";
        } else {
            echo "<p style='color:red;'>Erreur lors de la création du compte rendu : " . mysqli_error($connexion) . "</p>";
        }
    }
}



// Ferme la connexion
mysqli_close($connexion);
?>

<!-- Formulaire HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Compte Rendu</title>
</head>
<body>
    <h2>Créer un compte rendu</h2>
    <form method="POST" action="">
        <label for="title">Titre :</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Description :</label><br>
        <textarea name="description" id="description" rows="4" required></textarea><br><br>

        <label for="id_classe">ID de la classe :</label><br>
        <input type="text" name="id_classe" id="id_classe" required><br><br>
    
        <button type="submit">Créer</button>
    </form>
</body>
</html>
