<?php 
include_once 'conexion.php';

$id = $_REQUEST['id'];
try {
  $consulta = "DELETE FROM zonas WHERE id=:id";
  $sentencia = $pdo->prepare($consulta);
  $sentencia-> bindParam(':id', $id, PDO::PARAM_INT);
  $sentencia -> execute();
} catch (PDOException $e) {
  print 'ERROR: '. $e->getMessage();
  print '<br/>Data Not Deleted';
}
if($sentencia){
  echo 'Dato eliminado';
}

?>

<hr>
<a href="index.php">Atras</a>