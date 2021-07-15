<?php
set_time_limit(3600); ////maximo 60 minutos de espera
//require('config.php');
include_once 'conexion.php';

$url_objetivo = '';

$tipo       = $_FILES['dataCliente']['type'];   //dataCliente
$tamanio    = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas     = file($archivotmp);


//////vvvv
function json_output($status = 200, $msg = 'OK', $data = null){
  header('Content-Type: aplication/json');
  echo json_encode([
    'status' => $status,
    'msg' => $msg,
    'data' => $data
  ]);
  die;
}
//////vvvv

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



/////INICIO
$match = array();
$i = 0;
$contador = 0;
foreach ($lineas as $linea) {
  $lat = '';
  $lng = '';
    $cantidad_registros = count($lineas);
    //$cantidad_regist_agregados =  ($cantidad_registros - 1); //menos cabecera

    if ($i != 0){   
        $datos = explode(";", $linea);       
        $url = !empty($datos[0])  ? ($datos[0]) : '';
       
        if( strlen($url) > 5 ){
          $url =substr($url, 0, strlen($url)-2);    ///error
          //echo $url[-3];  //obtener el caracter de la ultima posicion
          //------------------------1
          $check_duplicidad = ("SELECT enlace FROM zonas WHERE enlace=:enlace ");  //
          $sentencia1 = $pdo->prepare($check_duplicidad);     //
          $sentencia1->bindParam(':enlace', $url, PDO::PARAM_STR);   //
          $sentencia1->execute();
          $res = $sentencia1->fetchAll();
          $cant_duplicidad = count($res);
          //----------1
          if( $cant_duplicidad == 0 ){
            if( exist($url, 'goo.gl') == 1 ){ // 37   // if(strlen($url) == 37)
              $url_objetivo = get_url($url);
            }
            //obtener coordenadas
            if( exist($url, 'q=') == 1 ){  //if(strlen($url)>=45 && strlen($url)<=66)
                $result=preg_match('/q=(.*),(.*)/', $url, $match );
                if(count($match) > 2){
                  if(indexOf($match, '&') !== -1){
                    $lng = explode('&', $match[2])[0];   //////errrrrr
                  } else {
                    $lng = $match[2];   ///errrr
                  }
                  $lat = $match[1];
                } else { 
                  //------echo '<div>'.($n=$i+1). "). " . "Error al obtener coordenadas (q=) de : " .$url.'</div>';
                  //echo $url; 
                }
            } else {
              if( exist($url, 'query=') == 1 ){   //71  //if(strlen($url)>=66 && strlen($url)<=90)
                $result=preg_match('/query=(.*),(.*)/', $url, $match );  //alternativa a preg_match
                if(count($match)!=0){
                  $lat = $match[1];
                  $lng = $match[2];
                }
              }else{
                //$result=preg_match('/@(\-?[0-9]+\.[0-9]+),(\-?[0-9]+\.[0-9]+)/', $url_objetivo, $match );
                if(strlen($url) > 90 && strlen($url) < 310){
                  $url_objetivo = $url;
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
                        //-----echo '<div>'.($n=$i+1). "). " . "Error al obtener coordenadas (!3d) de : " .$url_objetivo.'</div>';
                      }
          
                    }
                } else {
                  //------echo '<div>'.($n=$i+1). "). " . "Error!! url desconocido : " .$url_objetivo.'</div>';
                  //echo $url_objetivo;
                }
              
                
              
                
              }
            }
            
            
            //
            if(count($match)==0){
              //echo '<div>'.($n=$i+1). "). " . "Error al obtener coordenadas22" .'</div>';//echo 'coordenadas vacias';  //SOLO PARA PRUEBAS
            }else{
              
                $insertar = "INSERT INTO zonas( enlace, lat, lng ) VALUES (:enlace, :lat, :lng)";
                $sentencia = $pdo->prepare($insertar); 
                $sentencia->bindParam(':enlace', $url, PDO::PARAM_STR);
                $sentencia->bindParam(':lat', $lat, PDO::PARAM_STR);
                $sentencia->bindParam(':lng', $lng, PDO::PARAM_STR); 
                $resultado = $sentencia -> execute();
                //mysqli_query($con, $insertar); 
              // echo '<div>'. $i. "). " .$linea.'</div>';
                $contador++;
          
            }
          }
          
        }           
    }
    $match = array();
    $i++;
}


//  echo '<p style="text-aling:center; color:#333;">Total Registros: '. $contador .' de: '. $cantidad_registros .'</p>';
//echo $contador .'  de:  '. $cantidad_registros;
if ($contador === 0) {
  json_output(400, 'Hubo un error al subir el archivo.');
}

json_output(200, 'Archivo subido con exito.', $contador);
?>
<!-- otro -->
<!-- <a href="index.php">Atras</a>    -->