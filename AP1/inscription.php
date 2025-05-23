<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <form action="inscription2.php" method="post">
            
            <label for="nom">nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="Prenom">Prenom :</label>
            <input type="text" id="prenom" name="prenom" required>
            
            <label for="identifiant">identifiant :</label>
            <input type="text" id="identifiant" name="identifiant" required>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Répéter le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            
            
            <label for="eleve">Élève</label>
            <input type="radio" id="eleve" name="id_role_fk" value="1" required>

            
            <label for="prof">Professeur</label>
            <input type="radio" id="prof" name="id_role_fk" value="2">

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
