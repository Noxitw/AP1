<?php 
    session_start();  // Démarre la session PHP pour pouvoir utiliser des variables de session (comme $_SESSION['login'])

    include 'Conf.php';  // Inclut le fichier 'Conf.php' qui contient probablement les informations de connexion à la base de données

    // Crée une connexion à la base de données avec les paramètres définis dans Conf.php
    $connexion = mysqli_connect($servername, $username, $bddpassword, $dbname);

    // Si la connexion échoue, un message d'erreur est affiché et l'exécution du script est arrêtée
    if (!$connexion) {
        die('Erreur. Échec de la connexion à la base de données : ' . mysqli_connect_error());
    }

    // Vérifie si le formulaire contenant le champ 'confirm' a été soumis
    if(isset($_POST['confirm'])) {      
        $_SESSION['login'] = $_POST['login'];  // Enregistre l'identifiant de l'utilisateur dans la session
        $identifiant = $_SESSION['login'];  // Récupère l'identifiant de l'utilisateur à partir de la session

        // Requête SQL pour vérifier si l'utilisateur existe dans la base de données
        $requete_user = "SELECT u.identifiant FROM stagito_utilisateurs u WHERE u.identifiant = '$identifiant'";

        // Exécute la requête pour vérifier si l'utilisateur existe
        $resultat_user = mysqli_query($connexion, $requete_user);

        // Vérifie si un utilisateur correspondant à l'identifiant existe dans la base de données
        if (mysqli_num_rows($resultat_user) == 1) { // Si l'utilisateur existe
            $verif_code = rand(10000, 99999);  // Génère un code de réinitialisation aléatoire

            // Requête SQL pour mettre à jour la base de données avec le code de réinitialisation
            $requete_set_code = "UPDATE stagito_utilisateurs u SET code_reset = $verif_code WHERE u.identifiant = '$identifiant'";

            // Exécute la requête pour enregistrer le code de réinitialisation dans la base de données
            mysqli_query($connexion, $requete_set_code);

            // Récupère l'email soumis par l'utilisateur
            $email = $_POST['email'];  

            // Envoie un email avec le code de réinitialisation à l'adresse fournie
            if(mail($email, 'Code de réinitialisation', "Votre code est : $verif_code")){  
                echo "Mail envoyé avec succès, vérifier vos spams";  // Message de succès
            } else {
                echo "Le mail a rencontré un problème lors de son envoi";  // Message d'erreur si l'envoi échoue
            }
            ?>
            <!-- Formulaire pour saisir le code de réinitialisation -->
            <form method="post">
                <input type="text" name="verif_code">
                <button name="confirm_verif">Vérifier</button>
            </form>
            <?php
        } else {
            echo "Aucun utilisateur possède ce login";  // Message d'erreur si l'utilisateur n'existe pas
        }
    } elseif (isset($_POST['confirm_verif'])) { // Vérifie si le formulaire de vérification du code a été soumis
        $identifiant = $_SESSION['login'];  // Récupère l'identifiant de l'utilisateur à partir de la session

        // Requête SQL pour récupérer le code de réinitialisation de l'utilisateur
        $requete_verif_code = "SELECT u.code_reset FROM stagito_utilisateurs u WHERE u.identifiant = '$identifiant'";

        // Exécute la requête pour obtenir le code de réinitialisation
        $verif_code = mysqli_query($connexion, $requete_verif_code);

        // Récupère le code de réinitialisation à partir du résultat de la requête
        $get_code = mysqli_fetch_assoc($verif_code);

        // Vérifie si le code soumis correspond au code de réinitialisation dans la base de données
        if ($get_code['code_reset'] = $_POST['verif_code']) {  // Problème ici, il faut utiliser '==' pour une comparaison et non '='
            ?>
            <!-- Formulaire pour entrer un nouveau mot de passe -->
            <form method="post">
                Nouveau mot de passe : <input name="password" type="password"><br>
                Confirmation mot de passe : <input name="confirm_password" type="password"><br>
                <button name="confirm_new_mdp">Valider</button>
            </form>
            <?php
        } else {
            echo "Code incorrect";  // Message d'erreur si le code ne correspond pas
        }
    } elseif (isset($_POST['confirm_new_mdp'])) {  // Vérifie si le formulaire de changement de mot de passe a été soumis
        // Vérifie si les deux mots de passe saisis sont identiques
        if ($_POST['password'] == $_POST['confirm_password']) {
            $identifiant = $_SESSION['login'];  // Récupère l'identifiant de l'utilisateur à partir de la session
            $password = $_POST['password'];  // Récupère le mot de passe soumis
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);  // Hash le mot de passe pour le sécuriser

            // Requête SQL pour mettre à jour le mot de passe de l'utilisateur dans la base de données
            $requete_change_mdp = "UPDATE stagito_utilisateurs SET code_reset = '', password = '$password_hashed' WHERE identifiant = '$identifiant'";

            // Exécute la requête pour mettre à jour le mot de passe
            if (mysqli_query($connexion, $requete_change_mdp)) {
                // Redirige vers la page d'accueil avec un message de succès
                header('Location: index.html?correct=Mot de passe changé avec succès');
                exit();
            }
        } else {
            // Si les mots de passe ne correspondent pas, on réaffiche le formulaire et on affiche un message d'erreur
            $_POST['confirm_verif'] = 1;
            echo "Mot de passe non identiques";
        }
    } else {  // Si aucun des autres formulaires n'a été soumis, on affiche le formulaire initial
        ?>
        <!-- Formulaire initial pour entrer l'email et le login -->
        <form method="post">
            <p>Entrer votre mail</p>
            <input type="email" name="email" required>
            <p>Entrer votre login</p>
            <input type="text" name="login" required>
            <button name="confirm">Confirmer</button>
        </form>
   <?php }
?>
