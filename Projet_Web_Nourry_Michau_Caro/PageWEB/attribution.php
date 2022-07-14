<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Attribution</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});
        }

    </script>
    <script>
        function envoie(){
            var mail = document.getElementById("annotateur").value;
            var id = document.getElementsByName("cds");
            var date = new Date();
            date = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
            console.log(id);
            for (i = 0; i < id.length; i++) {
                if (id[i].checked) {
                    $.ajax({url: "envoie.php",
                        data:{table: "attribution",
                          mail: mail,
                          id: id[i].value, 
                          attri: date},
                        success: function(result){
                          console.log("attribution des cds");},
                        error: function(){console.log("erreur envoie");}
                      });
                }
            }
        }
    </script>
</head>
<body onload= init()>
  <h1>Attribution des CDS</h1>
  
  <div id="menu"></div>


<?php
session_start ();
if ($_SESSION['role']=='lecteur' or $_SESSION['role']=='annotateur'){
    header ('location: accueil.php');
    exit();
}
  include_once 'dbutils.php';

connect_db();

// Récupération des annotateurs
    $requete = "SELECT prenom, nom, mail FROM projetweb.utilisateur as ut WHERE ut.role!='lecteur';";
    $result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
    if (pg_num_rows($result)==0){
      console.log("pb avec requete annotateurs");
    }else{
        $annotateur = array();
        while ($arr = pg_fetch_row($result)) {
            array_push($annotateur,$arr);
        }
    }

// Récupération des cds non attribuées
    $requete = "SELECT cds.id,cds.espece, cds.souche FROM projetweb.cds as cds, projetweb.genome as genome WHERE cds.etat='En attente' AND cds.id NOT IN (SELECT idcds FROM projetweb.attribution) AND cds.idgenome=genome.id;";
    $result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());


    if (pg_num_rows($result)==0){
      echo 'Pas de séquence à attribuer';
    }else{
    // Affichage de la page 
    echo '<div class = conteneur>';
        // Selection de l'annotateur
    echo '<form  action="attribution.php" > ';
        echo    '     <label for="annotateur">Sélectionner un annotateur :</label>';

        echo '          <select name="annotateur" id="annotateur" >';
        echo '          <option value="">--Choisir un annotateur--</option>';

        $i=0;
        for ($i; $i<count($annotateur); $i++) {
            $str = $annotateur[$i][0].','.$annotateur[$i][1];
            
            echo '<option value="'.$annotateur[$i][2].'">'.$str.'</option>';
        }
        echo "  </select><br>   ";

        //selection des cds

        echo '<h2>CDS à attribuer</h2>';
        echo '<div class = checkbox>';
        $i=0;
        while ($arr = pg_fetch_row($result) and $i<100) {

            $str = $arr[0].','.$arr[1].','.$arr[2];
            echo '<input type="checkbox" id="'.$arr[0].'" name="cds" value="'.$arr[0].'">
                <label for="'.$arr[0].'"> '.$str.'</label><br>';
            $i++;
        }

        echo '</div>';
        echo '<input type="submit" name="connexion" value="attribuer les cds" onclick=envoie()>';
        echo '</form>';


    echo '</div>';
    }

?>
</body>
</html>
