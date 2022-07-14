<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Ajout de séquence</title>

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
	<h1>Entrée de nouveau génome</h1>
	<br>

	<div id="menu"></div>

	<br>
	<p>Afin de pouvoir s'intégrer  la base de données, les fichiers fournis doivent être sous les formes suivantes :</p>
	<p ><span STYLE="padding:0 0 0 20px;">- Fichier du génome au format FASTA, contenat tout le génome<br>
	<span STYLE="padding:0 0 0 20px;">- Fichier des CDS au format FASTA, contenant toutes les CDS<br>
	<span STYLE="padding:0 0 0 20px;">- Fichier des peptides au format FASTA, contenant touts les petides<br></p>
	
	De plus l'ordre des CDS doit correspondre à l'ordre des peptides dans les fichiers.</p><br>

<form enctype="multipart/form-data" action="testParse.php" method="post">
<!-- necessaire pour indiquer la taille max des fichiers à upload -->
  <input type="hidden" name="MAX_FILE_SIZE" value="5000000000" />
  <table>
  <tr>
  <td>Nom de l'espèce : </td><td>
	  
<?php

include 'dbutils.php';

connect_db(); 		//connexion à la db
global $db_conn;

$requete = "select * from projetweb.espece;";
$result = pg_query($db_conn, $requete) or console.log('Query failed with exception: ' . pg_last_error()); //renvoie une erreur si la requete echoue
echo "<select name=\"espece\" id=\"espece\">";
echo	"<option value=\"\">--Choisir une espèce--</option>";

if(pg_num_rows($result)>0){
	while($line=pg_fetch_row($result)){
		for($i=0; $i<count($line); $i++){
			echo "<option value=\"". $line[$i] ."\">".$line[$i]."</option>"; //récupère toute les especes comme options du menu déroulant
		}
	}
}
echo "</select>";

disconnect_db();

?>
  </td>

  </tr><tr>
  <td>Nom de la souche : </td><td><input type="text" name="souche" /></td>
  </tr><tr>
  <td>Fichier du génome :</td><td> <input name="fgenome" type="file" /></td>
  </tr><tr>
  <td>Fichier des cds :</td><td> <input name="fcds" type="file" /></td>
  </tr><tr>
  <td>Fichier des peptides :</td><td> <input name="fpep" type="file" /></td>
  </tr><tr>
  <td style="text-align:center" colspan="2"> <input type="submit" value="Envoyer les fichiers" /> </td>
  </tr>
  
  </table>
</form>
<br>

<br>

  <?php  

function parse($fi) {
	//fonction qui prend une array list correspondant à un fichier FASTA en argument
	//et qui renvoie une liste organisé en alternnant  entête et séquence
	
	$l=array();
	for ($i = 0; $i < count($fi); $i++) {
		if (strpos($fi[$i], '>')!== false){
			$seq="";
			$e=$fi[$i];
			$j=$i+1;
			while (strpos($fi[$j], '>')=== false && $j<count($fi)-1){
				$seq="$seq"."$fi[$j]"."\n"; //rassemble toutes les lignes en 1 chaine de cracteres
				$j+=1;				
			}
			array_push($l,$e,$seq); //liste : pair=entete / impair=sequence
		}	
	}
	return $l;
}

function fusparse($l1,$l2){
	//fonction qui prend deux array lists correspondant au fichier cds et peptide au format FASTA en argument
	//et qui renvoie une liste organisé en alternnant  entête et les séquence 
	
	$lf=array();
	for ($i = 1; $i < count($l1); $i+=2) {
		$j=$i-1;
				
		$lep=preg_split('/[\s,:]/',$l1[$j],-1,PREG_SPLIT_DELIM_CAPTURE);
		$lec=preg_split('/[\s,:]/',$l2[$j],-1,PREG_SPLIT_DELIM_CAPTURE);
		if ($lep[0] === $lec[0]){
		
			$lec[0]=substr($lec[0],1);

			array_push($lf,$l2[$j],$l1[$i],$l2[$i]); // (i+3) entete / (i+1+3) seq cds / (i+2+3) seq pep
		}
	}
	return $lf;
}
	
$nf=$_FILES['fgenome']['name'];
$nfc=$_FILES['fcds']['name'];
$nfp=$_FILES['fpep']['name']; 	//recupère les path des fichiers temporaire correspondant au fichiers upload
#echo "$nf $nfc $nfp".'<br>';
$e=$_POST['espece'];
$s=$_POST['souche'];		//récupère souche et espece fournis par l'utilisateur

if (!$nf || !$nfc || !$nfp){echo "Tous les fichiers n'ont pas été fournis...<br>";}
else if ($e==""){echo "L'espèce n'a pas été sélectionnée...<br>";}
else if ($s==""){echo "La souche n'a pas été saisie...<br>";}

else{

	$g =$_FILES['fgenome']['tmp_name'];
	$g2=file_get_contents($g, FALSE, NULL, 0, 1000000000);

	$c =$_FILES['fcds']['tmp_name'];
	$c2=file_get_contents($c, FALSE, NULL, 0, 1000000000);

	$p =$_FILES['fpep']['tmp_name'];
	$p2=file_get_contents($p, FALSE, NULL, 0, 1000000000);

	$sp=preg_split('/\n/',$p2,-1,PREG_SPLIT_DELIM_CAPTURE);
	$sc=preg_split('/\n/',$c2,-1,PREG_SPLIT_DELIM_CAPTURE); 
	$sg=preg_split('/\n/',$g2,-1,PREG_SPLIT_DELIM_CAPTURE); //coupure sur les fin de lignes

	$lc=parse($sc);
	if($lc){
	echo "cds chargées...<br>";
	}$lp=parse($sp);
	if($lp){
	echo "peptides chargés...<br>";
	}$lg=parse($sg);
	if($lg){
	echo "génome chargé...<br>";
	$leg=preg_split('/[\s,:]/',$lg[0],-1,PREG_SPLIT_DELIM_CAPTURE); //coupe l'entete sur les espaces et les :
	}

	$lf=fusparse($lc,$lp);

	global $db_conn;
	connect_db();

	$coor="$leg[6]:$leg[7]";
	$r = array('nomgenome' => $nf,
		'espece' => $e,
		'souche' => $s,
		'chrm' => $leg[4],
		'coor' => $coor,
		'seqnuc' => $lg[1]); //requete sous fourme d'array, une clef correspond à une colonne

	$insert = pg_insert($db_conn, 'projetweb.genome', $r) or die('Query failed with exception: ' . pg_last_error());

	echo "Génome inséré dans la base de donnée<br>";

	$q= 'select max(id) from projetweb.genome;';
	$res = pg_query($db_conn, $q) or die('Query failed with exception: ' . pg_last_error());
	$idg=(pg_fetch_array($res, null, PGSQL_ASSOC))['max']; //récupère l'id du génome car clef auto incrémentée

	$coor="$leg[6]:$leg[7]";
	$idg=intval($idg);

	for ($i = 2; $i < count($lf); $i+=3) { //les élément similaire sont à 3 d'écart

		$le=preg_split('/[\s,:]/',$lf[$i-2],-1,PREG_SPLIT_DELIM_CAPTURE); //coupe l'entete sur les espaces et les :
		$le[0]=substr($le[0],1);
		$coor="$le[5]:$le[6]";

		$requete = array( 'id' => $le[0],
		'idgenome' => $idg,
		'chrm' => $le[3],
		'coor' => $coor,
		'espece' => $e,
		'souche' => $s,
		'seqnuc' => $lf[$i-1],
		'seqprot' => $lf[$i],
		'etat' => 'En attente'); //requete sous fourme d'array, une clef correspond à une colonne

		$insert = pg_insert($db_conn, 'projetweb.cds', $requete) or die('Query failed with exception: ' . pg_last_error());
	}

	echo "CDS insérées dans la base de données<br>";

	disconnect_db();
}
  ?>
  
</body>
</html>
