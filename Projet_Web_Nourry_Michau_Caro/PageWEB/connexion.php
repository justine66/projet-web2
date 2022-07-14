<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Connexion</title>
</head>
<body>
  <h1>Bienvenue sur notre site</h1>
  

		
		<div id="parent">
			<form class="form_login" action="id_psql.php" method="post">
				Identifiant : <br>
				<input type="text" name="identifiant" placeholder="adresse mail"> <br>
				Mot de passe : <br>
				<input type="password" id="mdp" name="mdp" >
				<br><br>
				
				<input type="submit" name="connexion" value="Se connecter" >
			</form >
		
			<form class="form_login" action=inscription.html method="post">
				<input type="submit" name= "inscription" value="S'inscrire" >
			</form>
		
    </div>
</body>
</html>
  
