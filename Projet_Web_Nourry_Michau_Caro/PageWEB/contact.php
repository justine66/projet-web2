<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Contacts de SiteMania</title>
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
  <h1>Contact</h1>
  
  <div id="menu"></div>
  <div id="conteneur_left">
  
	<h2> Les administrateurs </h2>
		<p>
			CARO Hugo <br>
			NOURRY Justine <br>
			MICHAU Thomas-Sylvestre <br>
		</p>
</div>	
<div id="conteneur_right">
	<h2> Les moyens de nous contacter </h2>
		<h3> Adresse mail </h3>
			hugo.caro@universite-paris-saclay.fr<br>
			justine.nourry@universite-paris-saclay.fr <br>
			thomas.michau@universite-paris-saclay.fr <br>
</div>
</body>
</html>
