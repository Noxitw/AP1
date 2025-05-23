<?php
    // Inclut le fichier de configuration qui contient les informations de connexion à la base de données
    include 'Conf.php';

    // Récupère les données soumises via le formulaire
    $identifiant = $_POST['identifiant'];  // Récupère l'identifiant de l'utilisateur
    $password = $_POST['password'];  // Récupère le mot de passe de l'utilisateur
    $password_hash = password_hash($password, PASSWORD_DEFAULT);  // Crée un hash sécurisé du mot de passe
    $nom = $_POST['nom'];  // Récupère le nom de l'utilisateur
    $prenom = $_POST['prenom'];  // Récupère le prénom de l'utilisateur
    $email = $_POST['email'];  // Récupère l'email de l'utilisateur

    // Récupère l'ID de rôle, avec une valeur par défaut de 1 si non spécifiée,Cette partie vérifie si la variable $_POST['id_role_fk'] est définie et si elle n'est pas NULL. $_POST['id_role_fk'] est une valeur envoyée via un formulaire HTML avec la méthode POST.
    $id_role_fk = isset($_POST['id_role_fk']) ? (int) $_POST['id_role_fk'] : 1;

    // Établit une connexion à la base de données en utilisant les informations de configuration
    $connexion = mysqli_connect($servername, $username, $bddpassword, $dbname);

    // Vérifie si la connexion à la base de données est réussie
    if (!$connexion) {
        // Si la connexion échoue, arrête l'exécution et affiche un message d'erreur
        die('Erreur de connexion : ' . mysqli_connect_error());
    }

    // Si la connexion réussit, affiche un message de confirmation
    echo 'Connexion réussie à la base de données.<br>';

    // Crée une requête SQL d'insertion des données dans la table 'stagito_utilisateurs'
    $requete = "INSERT INTO stagito_utilisateurs (id, nom, prenom, identifiant, password, email, id_role_fk) 
    VALUES (0, '$nom', '$prenom', '$identifiant', '$password_hash','$email','$id_role_fk')";

    // Exécute la requête d'insertion
    if (!mysqli_query($connexion, $requete)) { 
        // Si l'insertion échoue, affiche un message d'erreur
        echo 'Erreur lors de l\'insertion : ' . mysqli_error($connexion) . '<br>';
    } else {
        // Si l'insertion réussit, redirige l'utilisateur vers la page 'index.html' après l'exécution
        header('Location: index.html');
        exit;  // Arrête l'exécution du script pour s'assurer que la redirection se produit
    }

    // Ferme la connexion à la base de données
    mysqli_close($connexion);
?>
