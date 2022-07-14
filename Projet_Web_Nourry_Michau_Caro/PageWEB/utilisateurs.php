<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Utilisateurs de SiteMania</title>
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
  <h1>Liste des utilisateurs</h1>
  
  <div id="menu"></div>

<div id="utilisateurs"></div>
<?php
session_start ();
if ($_SESSION['role']!='admin'){
    header ('location: accueil.php');
    exit();
}
include_once 'dbutils.php';

connect_db();

$requete = "select nom,prenom,mail,num,role,lastco from projetweb.utilisateur;";
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

if (pg_num_rows($result)==0){
    echo 'Pas d\'utilisateurs trouvé';
}else{
    echo ' <table>
            <tr> 
                <th> Nom </th>
                <th> Prénom </th>
                <th> Adresse mail </th>
                <th> Numéro de téléphone </th>
                <th> Rôle </th>
                <th> Dernière connexion </th>
            </tr>';
    while ($arr = pg_fetch_row($result)) {
        echo '<tr> <td>'.$arr[0].'</td>';
        echo '<td>'.$arr[1].'</td>';
        echo '<td>'.$arr[2].'</td>';
        echo '<td>'.$arr[3].'</td>';
        echo '<td>'.$arr[4].'</td>';
        echo '<td>'.$arr[5].'</td> </tr> ';
    }
    echo ' </table>';
    
}

       
?>

</body>
</html>
