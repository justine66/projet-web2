<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Recherche</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});
        }

    </script>
    <script type="text/javascript">
    	function recherchecds(){
    		var id = document.getElementById('idcds').value;
    		var espece = document.getElementById('espececds').value;
    		var souche = document.getElementById('souchecds').value;
    		var chrm = document.getElementById('chrmcds').value;
    		var seq = document.getElementById('seqcds').value;
    		const rbs = document.querySelectorAll('input[name="type"]');
            let type;
            for (const rb of rbs) {
                if (rb.checked) {
                    type = rb.value;
                    break;
                }
            }
    		var gbio = document.getElementById('gbio').value;
    		var tbio = document.getElementById('tbio').value;
    		var symb = document.getElementById('symb').value;
			$.ajax({async: false,
				url: "resultat.php",
					    data:{table:'cds',
					    	id: id, 
					    	espece: espece,
					    	souche : souche,
					    	chrm: chrm, 
					    	gbio: gbio, 
					    	tbio: tbio, 
					    	sy: symb, 
					    	seq: seq, 
					    	type: type, 
					    	},
					    success: function(result){
					    	$("#resultat").html(result);},
					    error: function(){console.log("erreur envoie");}
					  });
			document.getElementById("recherche").remove();
    	}

    	function recherchegenome(){
    		var id = document.getElementById('id').value;
    		var espece = document.getElementById('espece').value;
    		var souche = document.getElementById('souche').value;
    		var chrm = document.getElementById('chrm').value;
    		var seq = document.getElementById('seq').value;
    		$.ajax({async: false,
    			url: "resultat.php",
					    data:{table: 'genome',
					    	id: id, 
					    	espece: espece,
					    	souche : souche,
					    	chrm: chrm, 
					    	seq: seq},
					    success: function(result){
					    	$("#resultat").html(result);},
					    error: function(){console.log("erreur envoie");}
					  });
    		document.getElementById("recherche").remove();
    	}
    </script>
</head>
<body onload= init()>
  <h1>Recherche</h1>
  
  <div id="menu"></div>
  <div class = conteneur>


	<?php
		include_once 'dbutils.php';
		connect_db();
		$conn=$db_conn;

			
		echo '<div id=\'recherche\'>';

		echo '<div id=\'conteneur_left\'>';

		echo "<h1>Recherche pour une CDS</h1>";
	
		echo "<label for=\"seqRech\">S??quence ?? rechercher :</label> <br>";
		$requete = "select avg(length(seqprot)) from projetweb.cds";
		$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
		$result = intval(pg_fetch_row($result)[0]);
		echo "<textarea id='seqcds' name=\"sequence\" minlength=\"3\" maxlength=\"".$result."\"></textarea> <br>";
			
	
		echo "<input type=\"radio\"  name='type' value=\"g??ne\">";
		echo	"<label for=\"g??ne/prot??ine\">G??ne</label>";

		echo "<input type=\"radio\" name='type' value=\"prot??ine\">";
		echo	"<label for=\"g??ne/prot??ine\">Prot??ine</label><br>";
			
		echo "<label for=\"esp??ce\">Esp??ces pr??sentes dans la base :</label>";
		$requete = "select distinct(espece) from projetweb.cds";
		$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
		echo "<select name=\"esp??ce\" id='espececds'>";
		echo	"<option value=\"\">--Choisir une esp??ce--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br><label for=\"souche\">Souches pr??sentes dans la base :</label>";
			$requete = "select distinct souche from projetweb.cds";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"souche\" id='souchecds'>";
			echo	"<option value=\"\">--Choisir une souche--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";
			

			echo "<br><label for=\"chrm\">Chromosomes pr??sents dans la base :</label>";
			$requete = "select distinct chrm from projetweb.cds";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"chrm\" id='chrmcds'>";
			echo	"<option value=\"\">--Choisir un chromosome--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br> Identifiant CDS : <br>";
			echo "<input type=\"text\" name=\"CDSid\" id='idcds'>";

			echo "<h2>Param??tres d'annotation : </h2>";

			echo "<br><label for=\"gene_biotype\">Gene_biotype pr??sents dans la base :</label>";
			$requete = "select distinct gene_biotype from projetweb.annotation";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"gene_biotype\" id='gbio'>";
			echo	"<option value=\"\">--Choisir un gene_biotype--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br><label for=\"transcrit_biotype\">Transcrit_biotype pr??sents dans la base :</label>";
			$requete = "select distinct transcrit_biotype from projetweb.annotation";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"transcrit_biotype\" id='tbio'>";
			echo	"<option value=\"\">--Choisir un transcrit_biotype--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br> Gene_symbol : <br>";
			echo "<input type=\"text\" name=\"gene_symbol\" id='symb'>";

			

			echo "<br><br>";
			echo "<input type=\"button\" value=\"Rechercher\" onclick=recherchecds()>";

			echo '</div>';



			echo '<div id=\'conteneur_rigth\'>';
			echo "<h1>Recherche pour un g??nome</h1>";

			echo "<label for=\"seqRech\">S??quence ?? rechercher :</label> <br>";
			$requete = "select avg(length(seqprot)) from projetweb.cds";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			$result = intval(pg_fetch_row($result)[0]);

				echo "<textarea id='seq' name=\"sequence\" minlength=\"3\" maxlength=\"".$result."\"></textarea> <br>";
			
			echo "<label for=\"esp??ce\">Esp??ces pr??sentes dans la base :</label>";
			$requete = "select * from projetweb.espece";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"esp??ce\" id='espece'>";
			echo	"<option value=\"\">--Choisir une esp??ce--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br><label for=\"souche\">Souches pr??sentes dans la base :</label>";
			$requete = "select distinct souche from projetweb.genome";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"souche\" id='souche'>";
			echo	"<option value=\"\">--Choisir une souche--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";
			

			echo "<br><label for='chrm'>Chromosomes pr??sents dans la base :</label>";
			$requete = "select distinct chrm from projetweb.genome";
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			echo "<select name=\"chrm\" id=\"chrm\">";
			echo	"<option value=\"\">--Choisir un chromosome--</option>";
			if(pg_num_rows($result)>0){
				while($line=pg_fetch_row($result)){
					for($i=0; $i<count($line); $i++){
						echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>";
					}
				}
			}
			echo "</select>";

			echo "<br> Identifiant du g??nome : <br>";
			echo "<input type=\"text\" name=\"CDSid\" id='id' >";

			echo "<br><br>";
			echo "<input type=\"button\" value=\"Rechercher\" onclick=recherchegenome()>";
			echo "</div>";
			echo '</div>';

			echo '<div id=\'resultat\'></div>';

		?>
 </div>
	    
</body>
</html>