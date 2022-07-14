<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Accueil </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});
        }

    </script>
</head>
<body onload= init()>
  <h1>Accueil</h1>
  
  <div id="menu"></div>

	   <div class="conteneur">
        <h1>Bienvenue</h1>
		<p> Bienvenue sur notre site.</p>
        <p> Sur ce site vous avez la possibilité de faire des recherches de gènes, de séquences peptidiques et de génomes pour les visualiser</p>
        <p> Vous avez aussi la possibilité d'annoter, valider et attribuer des cds si vous avez le role adéquat</p>
	   </div>
   </p>
</body>
</html>
