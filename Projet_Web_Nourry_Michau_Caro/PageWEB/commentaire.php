<?php

	include_once 'dbutils.php';
	connect_db();

	if(empty($_GET['seqval'])) {

		$x = $_GET['seqAnnot'];
		$requete = "select a.*, v.commentaire FROM projetweb.Annotation AS a, projetweb.Validation as v WHERE a.idcds='".$x."' AND a.idcds = v.idcds AND a.dateversion = v.dateversion ORDER BY dateversion DESC;";
		
		$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

		if (pg_num_rows($result)===0){
			$annotation="";
		}else{
			$annotation="<h2> Annotations : </h2>";
			while ($arr = pg_fetch_row($result)) { 
				$annotation = $annotation."<div id='commentaire'> <b>idcds :</b> ". $arr[0]. "<br> <b>mail : </b>". $arr[1]. "<br> <b>sens :</b> ". $arr[2]. "<br> <b>genename : </b>". $arr[3]. "<br> <b>gene_biotype : </b>". $arr[4]. "<br><b> transcrit_biotype : </b>". $arr[5]. "<br> <b>gene_symbol : </b>". $arr[6]. "<br> <b>description : </b>". $arr[7]."<br> <b>date : </b>". $arr[8]. "<br> <b>description de la validation : </b>". $arr[9]. "<br> </div>";
				
			}
		}
	}else {
		$x = $_GET['seqval'];
		$requete = "select * FROM projetweb.Annotation WHERE idcds='".$x."' AND validation='En attente' ;";
		
		$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

		if (pg_num_rows($result)===0){
			$annotation="";
		}else{
			$annotation="<h2> Anciennes Annotation : </h2>";
			while ($arr = pg_fetch_row($result)) { 
				$annotation = $annotation."<div id='commentaire'> <b> idcds :  </b>". $arr[0]. "<br> <b> mail :  </b>". $arr[1]. "<br> <b> sens :  </b>". $arr[2]. "<br>  <b>genename :  </b>". $arr[3]. "<br> <b> gene_biotype :  </b>". $arr[4]. "<br> <b> transcrit_biotype :  </b>". $arr[5]. "<br> <b> gene_symbol :  </b>". $arr[6]. "<br> <b> description :  </b>". $arr[7]."<br> <b> date :  </b>". $arr[8]."<br> </div>";
				
			}
		}
	}
	disconnect_db();
	echo $annotation;

?>