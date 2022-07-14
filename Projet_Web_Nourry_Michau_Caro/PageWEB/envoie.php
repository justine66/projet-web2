<?php
include_once 'dbutils.php';
connect_db();

if($_GET['table']=='annotation') {
	$requete = "INSERT INTO projetweb.annotation VALUES ('".$_GET['id']."','".$_GET['mail']."','".$_GET['sens']."','".$_GET['name']."','".$_GET['gbio']."','".$_GET['tbio']."','".$_GET['sy']."','".$_GET['de']."','".$_GET['date']."','".$_GET['etat']."');";
	$q = pg_query($db_conn,$requete);

}else if ($_GET['table']=='attribution'){
	$requete = "INSERT INTO projetweb.attribution VALUES ('".$_GET['mail']."','".$_GET['id']."','".$_GET['attri']."');";
	$q = pg_query($db_conn,$requete);

}else if ($_GET['table']=='validation'){
	$requete = "INSERT INTO projetweb.validation VALUES ('".$_GET['mail']."','".$_GET['id']."','".$_GET['date']."','".$_GET['commentaire']."');";
	$q = pg_query($db_conn,$requete);

}else if ($_GET['table']=='cds'){
	$requete = "INSERT INTO projetweb.cds VALUES ('".$_GET['id']."','".$_GET['idg']."','".$_GET['chrm']."','".$_GET['coor']."','".$_GET['esp']."','".$_GET['souche']."','".$_GET['seqnuc']."','".$_GET['seqprot']."','".$_GET['etat']."');";
	$q = pg_query($db_conn,$requete);

}else if ($_GET['table']=='genome'){
	$requete = "INSERT INTO projetweb.genome VALUES ('".$_GET['id']."','".$_GET['name']."','".$_GET['esp']."','".$_GET['souche']."','".$_GET['chrm']."','".$_GET['coor']."','".$_GET['seqnuc']."','".$_GET['etat']."');";
	$q = pg_query($db_conn,$requete);

}else if ($_GET['table']=='utilisateur'){
	$requete = "INSERT INTO projetweb.utilisateur VALUES ('".$_GET['mail']."',md5('".$_GET['mdp']."'),'".$_GET['num']."','".$_GET['prenom']."','".$_GET['nom']."','".$_GET['role']."','".$_GET['etat']."','".$_GET['date']."');";
	$q = pg_query($db_conn,$requete);

}
disconnect_db();

?>