<?php 
include_once 'conexion.php';

$url = $_POST['enlace'];
$url_objetivo = '';

///Devuelve 1 si existe el parametro $word buscado
function indexOf($array, $word) {
    foreach($array as $value){
      if(substr_count($value, $word) > 0 ) return 1;
    } 
    return -1;
}

///Devuelve 1 si existe el parametro $word buscado
function exist($string, $word) {
    if(substr_count($string, $word) > 0 ) return 1;
    return -1;
}

function get_url($url)
{
    $ch = curl_init($url);
    //echo $ch;
    curl_setopt($ch,CURLOPT_HEADER,true); // Get header information
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);
    $header = curl_exec($ch);
//echo $header;
    $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header)); // Parse information

    for($i=0;$i<count($fields);$i++)
    {
        if(strpos($fields[$i],'location') !== false)
        {
            $url = str_replace("location: ","",$fields[$i]);
        }

        if(strpos($fields[$i],'Location') !== false)
        {
            $url = str_replace("Location: ","",$fields[$i]);
        }

    }
    return $url;
}

//$url_objetivo = get_url($enlace);
/////////INICIO
$match = array();
if(strlen($url) > 5){
  //$url =substr($url, 0, strlen($url)-2); quitar caracteres
  //echo $url;
  //echo $url[-3];  //obtener el caracter de la ultima posicion
  //echo  strlen($url);
  if( exist($url, 'goo.gl') == 1 ){ // 37  //if(strlen($url) == 37)
    $url_objetivo = get_url($url);
    //echo $url_objetivo;
  }
  //obtener coordenadas
  if( exist($url, 'q=') == 1 ){  //if(strlen($url)>=45 && strlen($url)<=55)
    $result=preg_match('/q=(.*),(.*)/', $url, $match );
      if(count($match) > 2){
        if(indexOf($match, '&') !== -1){
          $lng = explode('&', $match[2])[0];   //////errrrrr
        } else {
          $lng = $match[2];   ///errrr
        }
        $lat = $match[1];
      } else { 
        echo '<div>'.(1). "). " . "Error al obtener coordenadas (q=) de :" .'</div>';
        echo $url; 
      }
  } else {
    if( exist($url, 'query=') == 1 ){   //71   // if(strlen($url)>=65 && strlen($url)<=90)
      $result=preg_match('/query=(.*),(.*)/', $url, $match );
      if(count($match)!=0){
        $lat = $match[1];
        $lng = $match[2];
      }
    }else{
      //$result=preg_match('/@(\-?[0-9]+\.[0-9]+),(\-?[0-9]+\.[0-9]+)/', $url_objetivo, $match );
      if(strlen($url) > 90 && strlen($url) < 310){
        $url_objetivo = $url;
      }
      
      if($url_objetivo == ''){
        echo 'ERROR DE DATO!';
        echo '<hr>';
        echo '<a href="index.php">Volver</a>';
        exit;
      }
      if (exist($url_objetivo, '!3d') == 1 && exist($url_objetivo, '!4d') == 1){
          if(strlen($url_objetivo) > 320){   // ERROR   // if(strlen($url_objetivo) > 320)
              $splitUrl = explode('!3d', $url_objetivo);
              $match = explode('!4d',$splitUrl[count($splitUrl)-1]);
              $lng;
              if(indexOf($match, '!') !== -1){
                $lng = explode('!', $match[1])[0];
              } else {
                $lng = $match[1];
              }
              $lat = $match[0];
          }else{
            $splitUrl = explode('!3d', $url_objetivo);   //ERROR
            $match = explode('!4d',$splitUrl[count($splitUrl)-1]);
            $lng;
            
            if(count($match) > 1){
              if(indexOf($match, '?') !== -1){
                $lng = explode('?', $match[1])[0];   //////errrrrr
              } else {
                $lng = $match[1];   ///errrr
              }
              $lat = $match[0];
            } else {
              echo '<div>'.(1). "). " . "Error al obtener coordenadas (!3d) de :" .'</div>';
              echo $url_objetivo;
              //var_dump($match);
            }

          }
      } else {
        echo '<div>'.(1). "). " . "Error!! url desconocido :" .'</div>';
        echo $url_objetivo;
      }

    }
  
    
  }
  
  //
  if(count($match)==0){
    echo '<div>'.(1). "). " . "Coordenada vacia" .'</div>';
  }else{
    $check_duplicidad = ("SELECT enlace FROM zonas WHERE enlace=:enlace ");
    $sentencia1 = $pdo->prepare($check_duplicidad);
    $sentencia1->bindParam(':enlace', $url, PDO::PARAM_STR);
    $sentencia1->execute();
    $res = $sentencia1->fetchAll();
    $cant_duplicidad = count($res);
    //($resultado);
    //
    
    if($cant_duplicidad == 0){ //no existe Duplicados
      $insertar = "INSERT INTO zonas( enlace, lat, lng ) VALUES (:enlace, :lat, :lng)";
      $sentencia = $pdo->prepare($insertar); 
      $sentencia->bindParam(':enlace', $url, PDO::PARAM_STR);
      $sentencia->bindParam(':lat', $lat, PDO::PARAM_STR);
      $sentencia->bindParam(':lng', $lng, PDO::PARAM_STR);
      $resultado = $sentencia -> execute();
       echo '<div>' .$url.'</div>';
       echo '<br>';
       echo $resultado;
      //$contador++;
      if($resultado){
        echo 'Dato insertado con exito';
      }
    } else {
      echo 'ERROR! YA EXISTE DATO!';
      echo '<hr>';
      echo '<a href="index.php">Volver</a>';
      exit;
    }
    
  }
  
}
// INSERTAR
/*
try {
  //$sql_insertar = "INSERT INTO zonas (enlace) VALUES ('MTS')";
  $sql_insertar = "INSERT INTO zonas (enlace, lat, lng) VALUES (:enlace, :lat, :lng)";
  $sentencia = $pdo->prepare($sql_insertar);
  $sentencia-> bindParam(':enlace', $enlace, PDO::PARAM_STR);
  $sentencia-> bindParam(':lat', $lat, PDO::PARAM_STR);
  $sentencia-> bindParam(':lng', $lng, PDO::PARAM_STR);
  $resultado = $sentencia -> execute();
} catch (PDOException $e) {
  print 'ERROR: '. $e->getMessage();
  print '<br/>Data Not Inserted';
}
if($resultado){
  echo 'Dato insertado';
}
*/
?>

<hr>
<a href="index.php">Atras</a>