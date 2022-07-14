<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="projet4.css">
    <title>Visualisation</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/ajax-infinity-scroll/src/infinityScroll.js"></script>

    <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});
        }

        

    </script>
    
</head>
<body onload= init()>
  <h1>Visualisation</h1>
  
  <div id="menu"></div>
  <div class = conteneur>


    <?php
        include_once 'dbutils.php';
        connect_db();

        // Récupération des infos du génome à partir de l'id 
        $requete = "SELECT id,nomgenome,espece,souche,chrm, coor FROM projetweb.genome WHERE id='". $_GET['id'] ."';";
        $result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
        $genome = pg_fetch_row($result);

        echo '<input id=genome  type= hidden value='.$genome[0].'>';
        echo '<h2> Nom du génome : </h2>'.$genome[1];
        echo '<br><h2> Espece : </h2>'.$genome[2];
        echo '<br><h2> Souche : </h2>'.$genome[3];
        echo '<br><h2> Chromosome : </h2>'.$genome[4];
        echo '<br><h2> Coordonnées : </h2>'.$genome[5];
        echo "<br><br><a class='extract' href=\"extract.php?exttype=genome&id=$genome[0]\"><button class='extract'>Extraction</button></a>";
        echo '<br><div id=\'visualisation\' >';
        echo '<br><h2> Séquence : </h2><br>';

       echo '<div id=\'test\'></div>';
        

    echo ' </div>'
    ?>

    <script type="text/javascript">
        var genome = document.getElementById('genome').value

        $.ajax({url: "loadsequence.php?id="+genome,
                async :false,
              success: function(result){
                console.log("load du genome ok");},
              error: function(){console.log("erreur load genome");}
            });

        

        

        var option = {type :"html",
            data :"test.html",
            loadListClass :"load",
            renderInit : 10,
            scrollEndMessage :"fin de la séquence"}

        $('#test').infinityScroll(option)


   


    </script>
            
 </div>
        


    
</body>
</html>
