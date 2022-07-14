<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="projet4.css">
  <title>Validation des utilisateurs</title>

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
	<h1>Utilisateurs à valider</h1>
	<br>

	<div id="menu"></div>

    <?php 
session_start ();
if ($_SESSION['role']!='admin'){
    header ('location: accueil.php');
    exit();
}
include 'dbutils.php';

connect_db();

$requete = "SELECT mail FROM projetweb.utilisateur WHERE etat='En attente';";
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

while ($arr = pg_fetch_row($result)) {
    
    $ur=$arr[0];
    
    if(!empty($_GET)){

        $ur=str_replace('.','_',$ur);
        $a=$_GET["$ur"];

        if ($a){
            $update=array('mail'=>$arr[0]);
            if ($a=="v"){
                $data=array('etat'=>'Valide');
                pg_update($db_conn,'projetweb.utilisateur',$data,$update,PGSQL_DML_EXEC);
            }
            else if ($a=="r"){
                pg_delete($db_conn,'projetweb.utilisateur',$update,PGSQL_DML_EXEC);
            }
            
        }
    }
}
?>

    <div id="utilisateurs"></div>

<?php


$requete = "select nom,prenom,mail,num,role from projetweb.utilisateur where etat='En attente';";
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());

if (pg_num_rows($result)==0){
    echo 'Pas d\'utilisateurs trouvé';
}else{
    echo '<form action="validUser.php" methode="get"><table>
            <tr> 
                <th> Nom </th>
                <th> Prénom </th>
                <th> Adresse mail </th>
                <th> Numéro de téléphone </th>
                <th> Rôle souhaité</th>
                <th colspan="2"> Candidature </th>
            </tr>';
    while ($arr = pg_fetch_row($result)) {
        echo '<tr> <td>'.$arr[0].'</td>';
        echo '<td>'.$arr[1].'</td>';
        echo '<td>'.$arr[2].'</td>';
        echo '<td>'.$arr[3].'</td>';
        echo '<td>'.$arr[4].'</td>';
        echo '<td><input type="radio" name="'.$mail.'" value="v"><label for="'.$mail.'">Validé</label></td>
            <td><input type="radio" name="'.$mail.'" value="r"><label for="'.$mail.'">Refusé</label></td></tr></td></tr> ';
    }
    echo ' <tr><td style="text-align:center" colspan="8"> <input type="submit" value="Validation des candidatures"/> </td>
    </tr></table>    
    </form>';
    
}
?>



</body>
</html>
