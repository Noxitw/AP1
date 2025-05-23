<?php
session_start();

if (!isset($_SESSION['login'])) {
    echo "<p class='error'>Vous devez être connecté pour modifier un compte rendu.</p>";
    exit;
}

if (!isset($_GET['id'])) {
    echo "<p class='error'>Aucun ID fourni.</p>";
    exit;
}

$login = $_SESSION['login'];
$id_cr = intval($_GET['id']);

// Connexion à la base de données
$connexion = mysqli_connect("localhost", "u937355202_NolanFurtado", "Nolan4765&", "u937355202_NolanFurtadoBD");

if (!$connexion) {
    die("<p class='error'>Erreur de connexion à la base de données.</p>");
}

// Récupération de l'ID de l'utilisateur connecté
$requeteUser = "SELECT id FROM stagito_utilisateurs WHERE identifiant = '$login'";
$resultatUser = mysqli_query($connexion, $requeteUser);
$utilisateur = mysqli_fetch_assoc($resultatUser);
$id_utilisateur = $utilisateur['id'] ?? null;

if (!$id_utilisateur) {
    echo "<p class='error'>Utilisateur introuvable.</p>";
    exit;
}

// Vérifie que le CR appartient à l'utilisateur
$requete = "SELECT id, description FROM stagito_comptes_rendu WHERE id = $id_cr AND id_utilisateur_fk = $id_utilisateur";
$resultat = mysqli_query($connexion, $requete);

if (mysqli_num_rows($resultat) == 1) {
    $cr = mysqli_fetch_assoc($resultat);
    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier le compte rendu</title>
    </head>
    <body>
        <h2>Modifier le compte rendu</h2>
        <form method="POST" action="modif_traitement.php">
            <input type="hidden" name="id" value="<?= $cr['id'] ?>">
            <label>Description :</label><br>
            <textarea name="description" rows="6" cols="50"><?= htmlspecialchars($cr['description']) ?></textarea><br><br>
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </body>
    </html>

    <?php
} else {
    echo "<p class='error'>Compte rendu non trouvé ou accès interdit.</p>";
}
?>
