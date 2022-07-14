<?php

include_once 'dbutils.php';
connect_db();
fopen("test.html", "w"); //reset le fichier
$myfile = fopen("test.html", "a") or die("Unable to open file!"); //ajout au fichier

$requete = "SELECT seqnuc FROM projetweb.genome WHERE id=". $_GET['id'].";" ;
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
$genome = pg_fetch_row($result);

$requete = "SELECT cds.id, cds.coor FROM projetweb.cds as cds WHERE cds.idgenome='".$_GET['id'] ."';";
$result = pg_query($db_conn, $requete) or die('Query failed with exception: ' . pg_last_error());
$cds = array();
                
while ($arr = pg_fetch_row($result)) {

    $str = explode(':', $arr[1]);
    $a = array('id' =>$arr[0] , 'debut' =>$str[0],'fin' =>$str[1]);
    array_push($cds, $a);
                    
}

array_multisort($cds);
   
fwrite($myfile, '<span class="load">');
$str =wordwrap(substr($genome[0], 0,$cds[0]['debut']), 100, "\n", true);
fwrite($myfile,$str);
fwrite($myfile,'</span>
    ');
      
        
for ($i=0; $i <count($cds) ; $i++) { 
    $str = '<span class=load data-toggle="tooltip" title=\''.$cds[$i]['id'].','.$cds[$i]['debut'].':'.$cds[$i]['fin'].'\'>';

    fwrite($myfile,$str);
    $g =substr($genome[0], $cds[$i]['debut'],$cds[$i]['fin']-$cds[$i]['debut']);
    $word =wordwrap($g, 100, "\n", true);
    
    fwrite($myfile, $word);
    fwrite($myfile,'</span>
        <span class="load">');
    $taille =$cds[$i+1]['debut']-$cds[$i]['fin'];
    if ($taille<0) {
        $cds[$i+1]['debut']=$cds[$i]['fin'];
        fwrite($myfile, '</span>
            ');
    }else{
        $g = substr($genome[0], $cds[$i]['fin'],$taille);
        $word =wordwrap($g, 100, "\n", true);
        fwrite($myfile, $word);
        fwrite($myfile, '</span>
            ');
    }
    
    
    
}

fwrite($myfile, '<span class="load">');
$taille = strlen($genome[0])-$cds[count($cds)-1]['fin'];
$str = substr($genome[0], $cds[count($cds)]['fin'],$taille);
$str = wordwrap($str, 100, "\n", true);
fwrite($myfile,$str);
fwrite($myfile,'</span>
    ');
fclose($myfile);

?>
