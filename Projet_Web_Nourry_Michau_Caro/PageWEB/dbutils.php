<?php
	
	// Variable globale de connexion base de données pour simplifier
	$db_conn = null;
	// Fonction de connexion à la base de données postgres
	function connect_db ()
	{
		// on utilisera la variable globale $db_conn
		global $db_conn;
		// Parsing du fichier ini qui intègre les infos de connexion
		$db_info = parse_ini_file(".tp_bd_postgres_info.ini");
		
		// Connexion à la base de données 
		
		$db_conn = pg_connect("port=" . $db_info['port'] 
							. " dbname=" . $db_info['dbname']
							. " user=" . $db_info['user'] 
							. " password=". $db_info['password']) or die("Connexion impossible : " . pg_last_error());
	}

	// Fonction de déconnexion de la base de données postgres	
	function disconnect_db ()
	{
		global $db_conn;
		// Ferme la connexion
		pg_close($db_conn);
	}
	
?>