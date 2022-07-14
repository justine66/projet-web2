<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Extraction de données</title>
  
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
	<h1>Extraction de données</h1>

  <br>

  <div id="menu"></div>

  <br>

<?php 
$id=$_GET['id'];            #id genome/cds
$exttype=$_GET['exttype'];  #genome/cds

#echo $exttype;

if ($exttype=="genome"){

echo '<form action="extract.php" methode="post">
<!--Permet de transmettre les valeurs-->
<input type="hidden" name="id" value="'."$id".'" />
<input type="hidden" name="exttype" value="'."$exttype".'" />

<table class="small">
<tr><td rowspan=3>Type de données à télécharger</td>
<td><input type="checkbox" name="gc" value="gc"><label for="gc">Génome complet</td></tr>
<tr><td><input type="checkbox" name="gcds" value="gcds"><label for="gcds">Toutes les séquences des CDS du génome</label></td></tr>
<tr><td><input type="checkbox" name="gpep" value="gpep"><label for="gpep">Toutes les séquences des peptides du génome</label></td></tr>

<tr><td rowspan=3>Type d\'annotation à télécharger, si disponible</td>
<td><input type="radio" name="anot" value="af"><label for="anot">Annotation validée</label></td></tr>
<tr><td><input type="radio" name="anot" value="ap"><label for="anot">Dernière annotation, non validé</label></td></tr>
<tr><td><input type="radio" name="anot" value="an"><label for="anot">Aucune annotation</label></td></tr>

<tr><td style="text-align:center" colspan="2"> <input type="submit" value="Envoyer les fichiers" /> </td>
</table>
</form>';
}

else if($exttype=="cds"){
  
  echo '<form action="extract.php" methode="post">

  <input type="hidden" name="id" value="'."$id".'" />
  <input type="hidden" name="exttype" value="'."$exttype".'" />

  <table class="small">
  <tr><td rowspan=2>Type de données à télécharger</td>
  <td><input type="checkbox" name="ucds" value="ucds"><label for="ucds">Séquence de la CDS</label></td></tr>
  <tr><td><input type="checkbox" name="upep" value="upep"><label for="upep">Séquence du peptide</label></td></tr>

  <tr><td rowspan=3>Type d\'annotation à télécharger, si disponible</td>
  <td><input type="radio" name="anot" value="av"><label for="anot">Annotation validée</label></td></tr>
  <tr><td><input type="radio" name="anot" value="anv"><label for="anot">Dernière annotation, non validé</label></td></tr>
  <tr><td><input type="radio" name="anot" value="an"><label for="anot">Aucune annotation</label></td></tr>

  <tr><td style="text-align:center" colspan="2"> <input type="submit" value="Envoyer les fichiers" /> </td>
  </table>
  </form>';

}
?>

<?php

function reqfile($r,$type){
  //fonction qui prend une requete et un type (cds ou pep) et qui ecrit le resultat de la requete dans un fichier texte
  global $db_conn; 	//connexion
  $q = pg_query($db_conn, $r) or die('Query failed with exception: ' . pg_last_error());
  $res=(pg_fetch_array($q, null, PGSQL_ASSOC));
  $rp=realpath("./filedwl/"); //fichier à créer
  fopen("$rp/".$type.".fa", "w"); //reset le fichier
  #echo "fichier reset<br>";

  if ($type=='pep'){$t='prot';}
  else{$t='nuc';}

  $myfile = fopen("$rp/".$type.".fa", "a") or die("Unable to open file!"); //ajout au fichier

  while($res){
    

    if (array_key_exists('gene_symbol',$res)===true){
      $txt='>'.$res['id'].' cds chromosome:'.$res['chrm'].':Chromosome:'.$res['coor']
      .":".$res['sens']." gene:".$res['genename']
      ." gene_biotype:".$res['gene_biotype']." transcrit_biotype:".$res['transcrit_biotype']
      ." gene_symbol:".$res['gene_symbol']
      ." description:".$res['description']."\n".$res['seq'."$t"]."\n";
    }
    else if (array_key_exists('genename',$res)===true){
      $txt='>'.$res['id'].' cds chromosome:'.$res['chrm'].':Chromosome:'.$res['coor']
      .":".$res['sens']." gene:".$res['genename']
      ." gene_biotype:".$res['gene_biotype']." transcrit_biotype:".$res['transcrit_biotype']
      ." description:".$res['description']."\n".$res['seq'."$t"]."\n";
    }
    else {
    $txt='>'.$res['id'].' cds chromosome:'.$res['chrm'].':Chromosome:'.$res['coor']."\n".$res['seq'."$t"]."\n";
    }
    fwrite($myfile, $txt);
    $res=(pg_fetch_array($q, null, PGSQL_ASSOC));
  }
  fclose($myfile);

  if (filesize("$rp/".$type.".fa")>0){
  echo '<tr><td><a href="./filedwl/'.$type.'.fa" download="'.$type.'.fa">Fichier '.$type.' FASTA</a></td></tr>'; //créer un lien de téléchargement pour le fichier créer
  }else {
    echo '<tr><td>L\'annotations souhaité n\'est pas disponible, essayez avec une autre option</td></tr>';
  }

}
function genfile($r){
  //fonction qui prend une requete de genome en parametre et qui renvoie un lien de téléchargement du génome
  global $db_conn;
  $q = pg_query($db_conn, $r) or die('Query failed with exception: ' . pg_last_error());
  $res=(pg_fetch_array($q, null, PGSQL_ASSOC));
  $rp=realpath("./filedwl/");
  $myfile = fopen("$rp/genome.fa", "w") or die("Unable to open file!"); //réécrit le fichier
  $txt='>Chromosome dna:chromosome chromosome:'.$res['chrm'].':Chromosome:'.$res['coor'].":1 REF\n".$res['seqnuc']."\n";
  fwrite($myfile, $txt);
  fclose($myfile);
  echo '<tr><td><a href="./filedwl/genome.fa" download="'.$res['nomgenome'].'.fa">Fichier du génome</a></td></tr>'; //lien de téléchargement
  

}
$anot=$_GET['anot']; // av :Valide, anv: En attente, an: pas d'annotation

//Option pour séquence unique
$ucds=$_GET['ucds'];
$upep=$_GET['upep']; //cds ou pep unique

//Option pour plusieurs séquences
$gc=$_GET['gc']; // tout le genome
$gcds=$_GET['gcds']; //toutes les cds du genome
$gpep=$_GET['gpep']; //tous les pep du genome


include 'dbutils.php';
connect_db();

$req1=array();

if ($ucds||$upep){      //Si extraction unique

  $from=' FROM projetweb.cds as c'; //table cds            
  $req2=" where id='$id'";

  if ($ucds){
    $selec='SELECT c.id,c.chrm,c.coor,c.seqnuc'; 
    if ($anot!=='an'){
      $selec=$selec.',a.sens,a.genename,a.gene_biotype,a.transcrit_biotype,a.gene_symbol,a.description'; //si annotation souhaité
    }
    #echo $selec."<br>";
    array_push($req1,$selec);
  }
  if ($upep){
    $selec='SELECT c.id,c.chrm,c.coor,c.seqprot';
    if ($anot!=='an'){
      $selec=$selec.',a.sens,a.genename,a.gene_biotype,a.transcrit_biotype,a.gene_symbol,a.description'; //si annotation souhaité
    }

    array_push($req1,$selec);
  }

  if ($anot!="an"){$req2=" ,projetweb.annotation as a ".$req2." AND c.id=a.idcds";} //si annotation souhaité
  if ($anot=="av"){$req2=$req2." AND validation='Valide'";}  //derniere annotation validée
  if ($anot=="anv"){$req2=$req2." AND validation!='Refuse'";} //derniere anotation
  if ($anot!="an"){$req2=$req2.' Order BY a.dateversion DESC LIMIT 1';} //ne récupère qu'un resultat
}

echo '<table class="small">
<tr><td>Fichiers à télécharger</td></tr>';

if ($gc||$gcds||$gpep){ //Si extraction deuis génome
  if ($gc){
    $rg="SELECT * FROM projetweb.genome WHERE id =$id;";
    genfile($rg);

  }

  if ($gcds||$gpep){
    $from=' FROM projetweb.cds as c, projetweb.genome as g'; //table cds            
    $req2=" where g.id=$id AND g.id=c.idgenome";
    if ($gcds){
      $selec="SELECT c.id,c.chrm,c.coor,c.seqnuc";
      if ($anot!=='an'){$selec=$selec.",a.sens,a.genename,a.gene_biotype,a.transcrit_biotype,a.gene_symbol,a.description,max(a.dateversion) as dateversion";} 
      //si annotation souhaité, ne récupère que la derniere en date
      array_push($req1,$selec);      
    }

    if ($gpep){
      $selec="SELECT c.id,c.chrm,c.coor,c.seqprot";
      if ($anot!=="an"){$selec=$selec.",a.sens,a.genename,a.gene_biotype,a.transcrit_biotype,a.gene_symbol,a.description,max(a.dateversion) as dateversion";}
      //si annotation souhaité, ne récupère que la derniere en date
      array_push($req1,$selec);      
    }

  }

  if ($anot!="an"){$req2=" ,projetweb.annotation as a ".$req2." AND c.id=a.idcds";}
  if ($anot=="av"){$req2=$req2." AND validation='Valide'";}
  if ($anot=="anv"){$req2=$req2." AND validation!='Refuse'";}
  $req2=$req2.' Group BY c.id,c.chrm,c.coor,c.seqprot,c.seqnuc';

  if ($anot!="an"){$req2=$req2.",a.sens,a.genename,a.gene_biotype,a.transcrit_biotype,a.gene_symbol,a.description";}

}
$req2=$req2.';';

echo "<br>";

foreach($req1 as $r1){
  $req=$r1.$from.$req2;
  #echo $req."<br>";
  
  if(substr_count($req,'SELECT c.id,c.chrm,c.coor,c.seqprot')==1){
    #echo 'pep';
    reqfile($req,'pep');
  }

  else{
    #echo 'cds';
    reqfile($req,'cds');
  }
}

echo "</table>";

?>


</body>

</html>
