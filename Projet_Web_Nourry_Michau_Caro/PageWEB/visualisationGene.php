<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Page de visualisation du gène</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        
        function init(){

            $.ajax({url: "menu-admin.php", success: function(result){
                   $("#menu").html(result);
               }});

        }        
        function annot(seq){
            var x = document.getElementById("seqval").value;
            if (seq == 'seqval'){
                $.ajax({ url: "commentaire.php?seqval="+x , success: function(result){
                        $("#annotation").html(result);
                            }, error : function(){console.log("erreur");}});
            }else {
                $.ajax({ url: "commentaire.php?seqAnnot="+x , success: function(result){
                        $("#annotation").html(result);
                            }, error : function(){console.log("erreur");}});
            }
            document.getElementById("button").style.visibility = "hidden";  
        }

    </script>
    </head>
    <body onload= init()>  
        <h1>Visualisation du gène</h1>

        <div id="menu"></div>
        <div class = conteneur >

            <br>

            <?php
                include_once 'dbutils.php';
                connect_db();

                
                $conn = $db_conn;
                $id = $_GET['CDSid'];
                $seq = $_GET['seq'];

                echo "<h2> Nom du gène : </h2>"; 
                echo "<input id='seqval' type= hidden value='$id'>";
                echo "<p >$id</p>";


                $id = htmlentities($id, ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL

                $requete =  "select annot.validation from projetweb.annotation as annot where annot.idcds='". $id . "'" ;
                $result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
                
                if (pg_num_rows($result)==0) {
                    if($seq == "0"){
                    $requete = "select cds.chrm, cds.coor, cds.espece, cds.souche, cds.seqnuc from projetweb.cds as cds where cds.id='" . $id  ."'" ;
                    } else {
                        $requete = "select cds.chrm, cds.coor, cds.espece, cds.souche, cds.seqprot from projetweb.cds as cds, where cds.id='" . $id ."'" ;
                    }
    
                    $result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
                    $line=pg_fetch_row($result);
                   
                    echo "<h2> Chromosome : </h2>"; 
                    echo "<p>$line[0]</p>";


                    echo "<h2> Coordonnées : </h2>"; 
                    echo "<p>$line[1]</p>";

                    echo "<h2> Espèce : </h2>"; 
                    echo "<p>$line[2]</p>";

                    echo "<h2> Souche : </h2>"; 
                    echo "<p>$line[3]</p>";

                    if($seq == "0"){
                        echo "<h2> Séquence nucléique : </h2>"; 
                        echo "<p>$line[4]</p>";
                    } else {
                        echo "<h2> Séquence protéique : </h2>"; 
                        echo "<p>$line[4]</p>";
                    }

                
                }else{

                    if($seq == "0"){
                        $requete = "select cds.chrm, annot.sens, cds.coor, cds.espece, cds.souche, cds.seqnuc, annot.description, annot.validation from projetweb.cds as cds, projetweb.annotation as annot where cds.id='" . $id ."' and annot.idcds='". $id . "'" ;
                    } else {
                        $requete = "select cds.chrm, annot.sens, cds.coor, cds.espece, cds.souche, cds.seqprot, annot.description, annot.validation from projetweb.cds as cds, projetweb.annotation as annot where cds.id='" . $id ."' and annot.idcds='". $id . "'" ;
                    }
                
                    $result = pg_query($conn, $requete) or console.log('Query failed with exception: ' . pg_last_error());
                    $line=pg_fetch_row($result);
                    
                    echo "<h2> Chromosome : </h2>"; 
                    echo "<p>$line[0]</p>";

                    echo "<h2> Sens : </h2>"; 
                    echo "<p>$line[1]</p>";

                    echo "<h2> Coordonnées : </h2>"; 
                    echo "<p>$line[2]</p>";

                    echo "<h2> Espèce : </h2>"; 
                    echo "<p>$line[3]</p>";

                    echo "<h2> Souche : </h2>"; 
                    echo "<p>$line[4]</p>";

                    if($seq == "0"){
                        echo "<h2> Séquence nucléique : </h2>"; 
                        echo "<p>$line[5]</p>";
                    } else {
                        echo "<h2> Séquence protéique : </h2>"; 
                        echo "<p>$line[5]</p>";
                    }



                    
                    echo    "<div id='annotation'></div>";
                    if($line[7]=="Valide"){
                        echo ' <input type="button" id=\'button\' value="afficher l\'annotation" onclick=annot(\'seqAnnot\')>';
                        
                    } else if ($line[7]=="En cours" or $line[7]=="En attente") {
                        echo "<p id='val'>!!! Cette annotation n'a pas été validé !!! </p>";
                        echo ' <input type="button" id=\'button\' value="afficher l\'annotation" onclick=annot(\'seqval\')>';
                    }  
                }

                
                echo "<br>";

                echo "<a class='extract' href=\"extract.php?exttype=cds&id=$id\"><button class='extract'>Extraction</button></a>";
                $co = explode(":", $line[2]);
                
                echo "<br>";
                echo "<a  class='blast' target=\"_blank\" href=\"https://blast.ncbi.nlm.nih.gov/blast/Blast.cgi?PROGRAM=blastn&PAGE_TYPE=BlastSearch&BLAST_SPEC=&LINK_LOC=blasttab&LAST_PAGE=blastp&QUERY=$line[5]&QUERY_FROM=$co[0]&QUERY_TO=$co[1]&EQ_MENU=$line[3]\"><button>BLASTn</button></a>";
            ?>
        </div>