<?php


if(isset($_POST['connexion'])) { // si le bouton "Connexion" est appuyé
    // on vérifie que le champ "Pseudo" n'est pas vide
    // empty vérifie à la fois si le champ est vide et si le champ existe belle et bien (is set)

    if(empty($_POST['identifiant'])) {
        echo "Le champ Pseudo est vide.";
    } else {
        // on vérifie maintenant si le champ "Mot de passe" n'est pas vide"
        if(empty($_POST['mdp'])) {
            echo "Le champ Mot de passe est vide.";
        } else {

            // les champs sont bien posté et pas vide, on sécurise les données entrées par le membre:
            
            $id = htmlentities($_POST['identifiant'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
            $mdp = htmlentities($_POST['mdp'], ENT_QUOTES, "ISO-8859-1");
            //on se connecte à la base de données:
            include_once 'dbutils.php';
			connect_db();
			
            //verification de l'utilisateur et recupération de son role
            $requete = "select mdp from projetweb.utilisateur where mail = '". $id ."'";
            $result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
            $res =pg_fetch_array($result, 0, PGSQL_NUM);
            var_dump(MD5($res[0]));
            
            
            $requete = "select role, etat from projetweb.utilisateur where mail = '". $id ."' and mdp = MD5( '". $mdp ."');";
  			$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
            
			if (pg_num_rows($result) == 1) {
                
                $res =pg_fetch_array($result, 0, PGSQL_NUM);
                
                if ($res[1]=='Valide'){
    			 	session_start(); 
                	$_SESSION['role'] = $res[0];
                	$_SESSION['id'] = $id;
                    
                    $date = date('Y-m-d h:i:s');
                    
                    $requete = "UPDATE projetweb.utilisateur SET lastco= '". $date ."' WHERE mail = '". $id ."';";
                    $q = pg_query($db_conn,$requete);

                	header ('location: accueil.php');
                    exit();
    			}else{
                    
                    header ('location: connexion.php');
                    exit();
                }
			}else{
				
				header ('location: connexion.php');
                exit();
			}
            
        }
    }
}

?>
