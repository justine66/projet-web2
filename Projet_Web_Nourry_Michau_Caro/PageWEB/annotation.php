<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Annotation</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});
        }


    </script>
    <script>
    	function visualisation(){

    		var x = document.getElementById("seqAnnot").value;
    		$.ajax({ async: false,
    			url: "visualisationGene.php",
    						data: {CDSid :x,
    							seq:0
    						}, success: function(result){
    				$("#visu").html(result);
    		}});

    		$.ajax({ async: false,
    			url: "commentaire.php?seqAnnot="+x , 
    			success: function(result){
						$("#annotation").html(result);
    			}, 
    			error : function(){console.log("erreur");}});

    		var b =document.getElementsByClassName("extract");
    		var i;
    		for(i=0; i<b.length; i++){
    			b[i].remove();
			  }
			  var b =document.getElementsByClassName("blast");
				var i;
	    	for(i=0; i<b.length; i++){
				  	b[i].style.visibility='visible';
				 }

    	}

    	function envoie(){
    		var id = document.getElementById("seqAnnot").value;
    		var mail = document.getElementById("mail").value;
    		var sens = document.getElementById("sens").value;
    		var name = document.getElementById("genename").value;
    		var gbio = document.getElementById("gene_biotype").value;
    		var tbio = document.getElementById("transcrit_biotype").value;
    		var symbole = document.getElementById("gene_symbol").value;
    		var description = document.getElementById("description").value;
    		var date = new Date();
    		date = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
    		$.ajax({url: "envoie.php",
					    data:{table: "annotation",
					    	id: id, 
					    	mail: mail,
					    	sens : sens,
					    	name: name, 
					    	gbio: gbio, 
					    	tbio: tbio, 
					    	sy: symbole, 
					    	de: description, 
					    	date: date, 
					    	etat: "En attente"},
					    success: function(result){
					    	alert("Ajout de l'annotation");},
					    error: function(){console.log("erreur envoie");}
					  });
    		$.ajax({url: "update.php",
              data:{table: "cds_encours",
              id: id},
              success: function(result){
                console.log("update cds to 'en cours'");},
              error: function(){console.log("erreur update cds");}
            });

		
    		}
    </script>
    
</head>
<body onload= init()>
  <h1>Annotation</h1>
  
  <div id="menu"></div>


<?php


session_start ();
if ($_SESSION['role']=='lecteur'){
	header ('location: accueil.php');
	exit();
}
include_once 'dbutils.php';
connect_db();

$requete = "select idcds,espece,souche from projetweb.attribution as at, projetweb.cds as cds where annot = '". $_SESSION['id'] ."' and cds.id = at.idcds and cds.etat='En attente'; ";
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

if (pg_num_rows($result)==0){
	echo 'Pas de séquences attribuées';

}
else{
	echo '<div class = conteneur>';

	
	echo	'	  <label for="seqAnnot">Sélectionner une séquence à annoter :</label>';

	echo '			<select name="seqAnnot" id="seqAnnot" onchange=visualisation() >';
	echo '		    <option value="">--Choisir une séquence--</option>';



	while ($arr = pg_fetch_row($result)) {
		$str = $arr[0].','.$arr[1].','.$arr[2];
		echo '<option value="'.$arr[0].'">'.$str.'</option>';
	}
	
	echo "	</select><br>	";
		
	echo 	"<div id='visu'></div>";
			  
		  
	echo "	<h2>	 Annoter la cds : </h2>";
			 
	echo  '<div id="parent">
			<form class="form_annot" action="annotation.php" method="post">
				sens du gène : <br>
				<input type="number" id="sens" min="-1" max="1" step="2"> <br>
				genename : <br>
				<input type="text" id="genename" > <br>
				gene_biotype : <br>
				<input type="text" id="gene_biotype" > <br>
				transcrit_biotype : <br>
				<input type="text" id="transcrit_biotype" > <br>
				gene_symbol : <br>
				<input type="text" id="gene_symbol" > <br>
				description : <br>
				<textarea id="description" > </textarea> <br>
				
				<input type="hidden" id="mail" value ="'.$_SESSION['id'].'" > 
				
				
				<input type="submit" name="connexion" value="envoyer l\'annotation" onclick=envoie()>
			</form >

		
		';
	echo '</div>';
}
disconnect_db();
?>
</body>
</html>
