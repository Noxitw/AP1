<?php
session_start();

if (!isset($_SESSION['login'])) {
    echo "Vous devez être connecté.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $description = htmlspecialchars($_POST['description']);

    // Connexion BDD
    $connexion = mysqli_connect("localhost", "u937355202_NolanFurtado", "Nolan4765&", "u937355202_NolanFurtadoBD");

    if (!$connexion) {
        die("Erreur de connexion.");
    }

    // Sécurisation : on vérifie que l'utilisateur est bien propriétaire du CR
    $login = $_SESSION['login'];
    $res = mysqli_query($connexion, "SELECT id FROM stagito_utilisateurs WHERE identifiant = '$login'");
    $user = mysqli_fetch_assoc($res);
    $id_user = $user['id'];

    $verif = mysqli_query($connexion, "SELECT * FROM stagito_comptes_rendu WHERE id = $id AND id_utilisateur_fk = $id_user");
    if (mysqli_num_rows($verif) == 0) {
        echo "Accès interdit.";
        exit;
    }

    // Mise à jour
    $update = "UPDATE stagito_comptes_rendu SET description = '$description' WHERE id = $id";
    if (mysqli_query($connexion, $update)) {
        echo "Compte rendu mis à jour avec succès. <a href='voir_mes_comptes_rendusE.php'>Retour</a>";
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
