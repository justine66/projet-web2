<?php
include_once 'dbutils.php';
connect_db();

if($_GET['table']=='annotation') {
	$requete = "UPDATE projetweb.annotation SET validation='".$_GET['etat']."' WHERE idcds='".$_GET['id']."' AND validation='En attente';";
	$q = pg_query($db_conn,$requete);
}else if ($_GET['table']=='cds'){
	$requete = "UPDATE projetweb.cds SET etat='Valide' WHERE id='".$_GET['id']."' AND etat='En cours';";
	$q = pg_query($db_conn,$requete);
}else if ($_GET['table']=='cds_encours'){
	$requete = "UPDATE projetweb.cds SET etat='En cours' WHERE id='".$_GET['id']."' AND etat='En attente';";
	$q = pg_query($db_conn,$requete);
}else if ($_GET['table']=='cds_enattente'){
	$requete = "UPDATE projetweb.cds SET etat='En attente' WHERE id='".$_GET['id'].";";
	$q = pg_query($db_conn,$requete);
}



disconnect_db();

?>