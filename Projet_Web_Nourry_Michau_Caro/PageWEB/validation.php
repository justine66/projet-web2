<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Validation</title>
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

        var x = document.getElementById("seqval").value;
        x = x.split(",")[0];
        $.ajax({ async: false,
          url: "visualisationGene.php",
                data: {CDSid :x,
                  seq:0
                }, success: function(result){
            $("#visu").html(result);
        }});

        $.ajax({ url: "commentaire.php?seqval="+x , success: function(result){
          $("#annotation").html(result);
              }, error : function(){console.log("erreur");}});

        document.getElementById("val").remove();
      }

      function envoie(){
        let id = document.getElementById("seqval").value;
        const id_da = id.split(",");
        var mail = document.getElementById("mail").value;
        var etat = document.querySelector('input[name="valide"]:checked').value;
        var com = document.getElementById("com").value;
        if (etat=='Valide'){
          $.ajax({url: "envoie.php",
              data:{table: "validation",
                mail: mail,
                id: id_da[0], 
                date: id_da[1], 
                commentaire: com },
              success: function(result){
                console.log("Validation envoyé");},
              error: function(){console.log("erreur envoie");}
            });
          $.ajax({url: "update.php",
              data:{table: "annotation",
              id: id_da[0],  
              date: id_da[1],
              etat: etat},
              success: function(result){
                console.log("update annotation");},
              error: function(){console.log("erreur update annotation");}
            });
          $.ajax({url: "update.php",
              data:{table: "cds",
              id: id_da[0]},
              success: function(result){
                console.log("update cds");},
              error: function(){console.log("erreur update cds");}
            });

        }else{
          $.ajax({url: "envoie.php",
                data:{table: "validation",
                  mail: mail,
                  id: id_da[0], 
                  date: id_da[1], 
                  com: com},
                success: function(result){
                  console.log("refus envoyé");},
                error: function(){console.log("erreur envoie");}
              });
          $.ajax({url: "update.php",
              data:{table: "annotation",
              id: id_da[0],  
              date: id_da[1],
              etat: etat},
              success: function(result){
                console.log("update annotation");},
              error: function(){console.log("erreur update annotation");}
            });
          $.ajax({url: "update.php",
              data:{table: "cds_enattente",
              id: id_da[0]},
              success: function(result){
                console.log("update cds to 'en attente'");},
              error: function(){console.log("erreur update cds");}
            });
        }
    
      }
    </script>
    
</head>
<body onload= init()>
  <h1>Validation des annotations</h1>
  
  <div id="menu"></div>


<?php

session_start ();
if ($_SESSION['role']=='lecteur' or $_SESSION['role']=='annotateur'){
    header ('location: accueil.php');
    exit();
}
  include_once 'dbutils.php';

  connect_db();

  $requete = "SELECT idcds, genename,dateversion FROM projetweb.annotation WHERE validation='En attente' ORDER BY dateversion DESC;";
  $result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

  if (pg_num_rows($result)==0){
    echo 'Pas d\'annotation à valider';

  }
  else{
    echo '<div class = conteneur>';
    echo  '<label for="seqval">Sélectionner une annotation à valider :</label>';
    echo '<select name="seqval" id="seqval" onchange=visualisation() >';
    echo '<option value="">--Choisir une séquence--</option>';



    while ($arr = pg_fetch_row($result)) {
      $str = $arr[0].','.$arr[1];
      $s = $arr[0].','.$arr[2];
      echo '<option value="'.$s.'">'.$str.'</option>';
    }
    
    echo "  </select><br> ";
      
    echo  "<div id='visu'></div>";
    
          
    
    echo  "<div id='annotation'></div>";
    
    echo "  <h2>   Validation de l'annotation : </h2>";     
    echo  '<div id="parent">
        <form class="form_val" action="Validation.php" method="post">
          
          Commentaire : <br>
          <textarea id="com" > </textarea> <br> 
          <input type="hidden" id="mail" value ="'.$_SESSION['id'].'" > <br>
          

          <input type="radio" id="Valide" name="valide" value="Valide">
          <label for="Valide">Valider l\'annotation</label>

          <input type="radio" id="refuse" name="valide" value="Refuse">
          <label for="refuse">Refuser l\'annotation</label>
          <br><br>

          <input type="submit" name="connexion" value="Valider/Refuser l\'annotation" onclick=envoie()>
        </form >
      
      ';
  echo '</div>';     
  }
  disconnect_db();
?>
</body>
</html>
