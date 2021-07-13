<?php 

  try {
    $pdo = NEW PDO('mysql:host=localhost; dbname=obbso_db', 'will', 'willcm1995');
    //echo 'conectado';
  } catch (PDOException $e) {
    print "¡Error:" . $e->getMessage() . "<br/>";
    die();
  };
  
?>