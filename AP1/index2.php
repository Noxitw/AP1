<?php
session_start(); // Démarre la session dès le début du fichier

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion élève</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST)) {
        echo "<p class='error'>Erreur : Formulaire vide.</p>";
    } else {
        // Connexion à la base de données
        $servername = "localhost";  
        $username = "u937355202_NolanFurtado";
        $bddpassword = "Nolan4765&";
        $dbname = "u937355202_NolanFurtadoBD";

        $connexion = mysqli_connect($servername, $username, $bddpassword, $dbname);

        if (!$connexion) {
            die('<p class="error">Erreur de connexion : ' . mysqli_connect_error() . '</p>');
        }

        if (isset($_POST['identifiant']) && isset($_POST['password'])) {
            $identifiant = mysqli_real_escape_string($connexion, $_POST['identifiant']);
            $password = $_POST['password'];

            $requete = "SELECT stagito_utilisateurs.*, stagito_roles.libellé_role 
                        FROM stagito_utilisateurs 
                        JOIN stagito_roles ON stagito_utilisateurs.id_role_fk = stagito_roles.id
                        WHERE stagito_utilisateurs.identifiant = '$identifiant'";

            $resultat = mysqli_query($connexion, $requete);

            if (mysqli_num_rows($resultat) == 1) {
                $utilisateur = mysqli_fetch_assoc($resultat);

                if (password_verify($password, $utilisateur['password'])) {
                    // Stockage en session
                    $_SESSION['login'] = $identifiant;
                    $_SESSION['role'] = $utilisateur['libellé_role'];
                    $_SESSION['nom'] = $utilisateur['nom'];
                    $_SESSION['prenom'] = $utilisateur['prenom'];
                    $_SESSION['email'] = $utilisateur['email'];

                    echo "<p class='success'>Connexion réussie !</p>";
                } else {
                    echo "<p class='error'>Erreur : Mot de passe incorrect.</p>";
                    exit;
                }
            } else {
                echo "<p class='error'>Erreur : Aucun utilisateur trouvé avec ce login.</p>";
                exit;
            }
        }
    }
}

// Affichage des infos utilisateur si connecté
if (isset($_SESSION['login'])) {
    echo "<p>Bienvenue, <strong>" . htmlspecialchars($_SESSION['nom']) . " " . htmlspecialchars($_SESSION['prenom']) . "</strong></p>";
    echo "<p>Vous êtes un <strong>" . htmlspecialchars($_SESSION['role']) . "</strong></p>";
    echo "<p>Login: <strong>" . htmlspecialchars($_SESSION['login']) . "</strong></p>";
    echo "<p>Email: <strong>" . htmlspecialchars($_SESSION['email']) . "</strong></p>";

    if ($_SESSION['role'] == 'Élève') {
        echo '<a href="Stagito-compterenduE.php"><button>Créer un compte rendu</button></a>';
        echo '<a href="voir_mes_comptes_rendusE.php"><button>Voir mes comptes rendus</button></a>';
    } elseif ($_SESSION['role'] == 'Professeur') {
        echo '<a href="Voir_compte_renduP.php"><button>Voir tous les comptes rendus</button></a>';
    }

    // Bouton de déconnexion
    echo '<div class="logout"><a href="index.html">Se déconnecter</a></div>';
} else {
    echo "<p>Veuillez vous connecter pour voir vos informations.</p>";
}
?>
</div>

</body>
</html>
