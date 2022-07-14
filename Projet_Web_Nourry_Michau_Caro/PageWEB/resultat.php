<?php
include_once 'dbutils.php';
connect_db();

		$requete = "";
		$conn=$db_conn;

		if(!empty($_GET)){
			if ($_GET['table']=='cds'){
				if(empty($_GET['type'])){
					$type = 'gene';

				}else{
					$type = $_GET['type'];
				}

				if(!empty($_GET['seq'])){
					$sequence = htmlentities($_GET['seq'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les  manque quelque chose";

					if($type=='gene'){
						$requete = "cds.seqnuc like '%". $sequence ."%'";
					}else{
						$requete = "cds.seqprot like '%". $sequence ."%'";
					}
				}	
				if($type=='gene'){
						$seq=0;
					}else{
						$seq=1;
					}

				if(!empty($_GET['espece'])){
					$espece = htmlentities($_GET['espece'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "cds.espece = '". $espece ."'";
					} else {
						$requete = " " . $requete . " and cds.espece = '". $espece ."'";
					}
					
				} 
				if(!empty($_GET['souche'])){
					$souche = htmlentities($_GET['souche'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "cds.souche = '". $souche ."'";
					} else {
						$requete = " " . $requete . " and cds.souche = '". $souche ."'";
					}
				} if(!empty($_GET['chrm'])){
					$chromosome = htmlentities($_GET['chrm'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "cds.chrm = '". $chromosome ."'";
					} else {
						$requete = " " . $requete . " and cds.chrm = '". $chromosome ."'";
					}
				} if(!empty($_GET['id'])){
					$CDSid = htmlentities($_GET['id'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "cds.id = '". $CDSid ."'";
					} else {
						$requete = " " . $requete . " and cds.id = '". $CDSid ."'";
					}
				} 
				if(!empty($_GET['gbio'])){
					$gbio = htmlentities($_GET['gbio'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "a.gene_biotype = '". $gbio ."'";
					} else {
						$requete = " " . $requete . " and a.gene_biotype = '". $gbio ."'";
					}
				} 
				if(!empty($_GET['tbio'])){
					$tbio = htmlentities($_GET['tbio'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "a.transcrit_biotype = '". $tbio ."'";
					} else {
						$requete = " " . $requete . " and a.transcrit_biotype = '". $tbio ."'";
					}
				} 
				if(!empty($_GET['sy'])){
					$sy = htmlentities($_GET['sy'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "a.gene_symbol = '". $sy ."'";
					} else {
						$requete = " " . $requete . " and a.gene_symbol = '". $sy ."'";
					}
				} 
				
				
				$requete = "select cds.id, cds.chrm, cds.espece, cds.souche  from projetweb.cds as cds, projetweb.annotation as a where  cds.id=a.idcds and " . $requete ;
				$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
				if(pg_num_rows($result)>0){
						echo "<table><tr><th>ID du CDS</th><th>Chromosome</th><th>Espèce</th><th>Souche</th></tr>";
						while($line=pg_fetch_row($result)){
							echo "<tr>";
							echo "<td><a href=\"visualisationGene.php?CDSid=$line[0]&seq=$seq\" title=visualisation\">$line[0]</a></td>";
							for($i=1; $i<count($line); $i++){
								echo "<td>$line[$i] </td>";
							}
							echo "</tr>";
						}
						echo "</table>";
					} else {
						echo "Rien n'a été sélectionné";
					}
				
		}else{
			if(!empty($_GET['seq'])){
				$sequence = htmlentities($_GET['seq'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les  manque quelque chose";
				$requete = "seqnuc like '%". $sequence ."%'";
			}

			if(!empty($_GET['espece'])){
					$espece = htmlentities($_GET['espece'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "espece = '". $espece ."'";
					} else {
						$requete = " " . $requete . " and espece = '". $espece ."'";
					}
					
			} 
			if(!empty($_GET['souche'])){
					$souche = htmlentities($_GET['souche'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "souche = '". $souche ."'";
					} else {
						$requete = " " . $requete . " and souche = '". $souche ."'";
					}
			} if(!empty($_GET['chrm'])){
					$chromosome = htmlentities($_GET['chrm'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "chrm = '". $chromosome ."'";
					} else {
						$requete = " " . $requete . " and chrm = '". $chromosome ."'";
					}
			} if(!empty($_GET['id'])){
					$CDSid = htmlentities($_GET['id'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
					if($requete == ""){
						$requete = "id = '". $CDSid ."'";
					} else {
						$requete = " " . $requete . " and id = '". $CDSid ."'";
					}
			} 

			$requete = "select id, nomgenome, espece, souche, chrm from projetweb.genome where " . $requete ;
			$result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
			if(pg_num_rows($result)>0){
				echo "<table><tr><th>ID du génome</th><th>Nom du génome</th><th>Espèce</th><th>Souche</th><th>Chromosome</th></tr>";
				while($line=pg_fetch_row($result)){
					echo "<tr>";
					echo "<td><a href=\"visualisation.php?id=$line[0]\" title=visualisation\">$line[0]</a></td>";
					for($i=1; $i<count($line); $i++){
						echo "<td>$line[$i] </td>";
					}
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "Rien n'a été sélectionné";
			}
		}
	}
?>
