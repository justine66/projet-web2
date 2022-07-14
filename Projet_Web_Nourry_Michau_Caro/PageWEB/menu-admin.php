<?php
session_start ();
    
if ($_SESSION['role']=='admin'){


    echo "<div class='menu' id ='menue'>";
    echo  "<nav> <ul>";
          
    echo"<li class= 'admin'><a href='recherche.php'>Recherche de séquences</a></li>
            <li class= 'admin'><a href='annotation.php'>Annotation</a></li>
            <li class= 'admin'><a href='validation.php'>Validation</a></li>
            <li class= 'admin'><a href='attribution.php'>Attribution</a></li>
            <li class= 'admin'><a href='utilisateurs.php'>Utilisateurs</a></li>
            <li class= 'admin'><a href='validUser.php'>Utilisateurs en attente</a></li>
            <li class= 'admin'><a href='parse.php'>Insertion</a></li>
            <li class= 'admin'><a href='contact.php'>Contacts</a></li>";
    echo"      </ul>
       </nav>";

}
else if ($_SESSION['role']=='validateur'){


    echo "<div class='menu' id ='menue'>";
    echo  "<nav> <ul>";
          
    echo"<li class= 'val'><a href='recherche.php'>Recherche de séquences</a></li>
            <li class= 'val'><a href='annotation.php'>Annotation</a></li>
            <li class= 'val'><a href='validation.php'>Validation</a></li>
            <li class= 'val'><a href='attribution.php'>Attribution</a></li>
            <li class= 'val'><a href='contact.php'>Contacts</a></li>";
    echo"      </ul>
       </nav>";

}
else if ($_SESSION['role']=='annotateur'){


    echo "<div class='menu' id ='menue'>";
    echo  "<nav> <ul>";
          
    echo"<li class= 'an'><a href='recherche.php'>Recherche de séquences</a></li>
            <li class= 'an'><a href='annotation.php'>Annotation</a></li>
            <li class= 'an'><a href='contact.php'>Contacts</a></li>";
    echo"      </ul>
       </nav>";

}
else{


    echo "<div class='menu' id ='menue'>";
    echo  "<nav> <ul>";
          
    echo"<li class= 'l'><a href='recherche.php'>Recherche de séquences</a></li>
            <li class= 'l'><a href='contact.php'>Contacts</a></li>";
    echo"      </ul>
       </nav>";

}
  echo "</div>";
?>
