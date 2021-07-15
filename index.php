
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="text/css" rel="shortcut icon" href="img/logo-mywebsite-urian-viera.svg"/>
  <title>Mapa :: Obbso</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/cargando.css">
  <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
<!-- jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-dark fixed-top" style="background-color: #231fe6 !important;">
    <ul class="navbar-nav mr-auto collapse navbar-collapse">
      <li class="nav-item active">
        <a href="index.php"> 
          <img src="img/logo-mywebsite-urian-viera.svg" alt="Web Developer" width="120">
        </a>
      </li>
    </ul>
    <div class="my-2 my-lg-0">
      <h5 class="navbar-brand">Web</h5>
    </div>
</nav>


<div class="container">
<br>
<hr>
<br>

 <div class="row">
    <div class="col-md-5">
      <!-- <form action="upload_excel.php" method="POST" enctype="multipart/form-data">
        <div class="file-input text-center">
            <input  type="file" name="dataCliente" id="file-input" class="file-input__input"/>
            <label class="file-input__label" for="file-input">
              <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
              <span>Elegir Archivo Excel</span></label
            >
          </div>
        <div class="text-center">
            <input type="submit" name="subir" class="btn-enviar" value="Subir Excel"/>
        </div>
      </form> -->
      <!-- otro -->
      <!-- <form action="#" enctype="multipart/form-data" id ="filesForm">
        <div class="file-input text-center">
            <input  type="file" name="dataCliente2" id="file-input" class="file-input__input"/>
            <label class="file-input__label" for="file-input">
              <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
              <span>Elegir Archivo Excel</span></label
            >
          </div>
          <div class="text-center">
            <button type="button" onClick="uploadFile()" class="btn btn-primary mx-sm-3 mb-2">Subir excel</button>
          </div>
        <br> 
        <div id="respuesta">Resultado de carga: </div>       
      </form> -->
      <!-- otro -->
      <!-- otro -->
      <div class="card">
        <div class="card-header">Sube tu archivo</div>
        <div class="card-body">
          <form class="upload_file">
            <div class="form-group">
              <label for="file">Archivo a subir</label>
              <input type="file" class="form-control form-control-file" name="dataCliente" id="dataCliente" required>
            </div>

            <button class="btn btn-success" type="submit">Subir archivo</button>

            <div class="wrapper mt-5" style="display: none;">
              <div class="progress progress_wrapper">
                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar" role="progressbar" style="width: 0%;">0%</div>
              </div>
            </div>
          </form>
          
        </div>
      </div>
      <!-- otro -->
      <hr>
      <form action="insertar.php" method="POST">
        <div class="form-group mx-sm-3 mb-2">
          <label for="inputPassword2" class="sr-only">Password</label>
          <input type="text" name="enlace" class="form-control" id="enlace-input" placeholder="https://goo.gl...">
        </div>
        <button type="submit" class="btn btn-primary mx-sm-3 mb-2">Registrar ubicación</button>
      </form>
      <!-- 2da Columna -->
  <!--  </div>

    <div class="col-md-3">   -->
      <!-- 2da Columna -->
  <?php
  $coordinates = array();
  $enlaces = array();
 	$latitudes = array();
 	$longitudes = array();
 	$cantidad = array();  ////

  $poligonos = array(
    "NE" => array(  //..
      "vertices_x" => array(-17.78332322, -17.65297037, -17.65297037, -17.78296777),
      "vertices_y" => array(-63.18212634, -63.18202015, -63.04366110, -63.04348945)
    ),
    "SE" => array(
      "vertices_x" => array(-17.78332322, -17.78301180, -17.89085126, -17.89011184),
      "vertices_y" => array(-63.18212634, -63.05997169, -63.05834076, -63.18261363)
    ),
    "NO" => array(
      "vertices_x" => array(-17.78332322, -17.78275123, -17.69822267, -17.69855191),
      "vertices_y" => array(-63.18212634, -63.25076318, -63.25093484, -63.18205449)
    ),
    "SO" => array(
      "vertices_x" => array(-17.78332322, -17.87803874, -17.87802027, -17.78254027),
      "vertices_y" => array(-63.18212634, -63.18256119, -63.27582316, -63.27592817)
    )
  );

  //header("Content-Type: text/html;charset=utf-8");
  //include('config.php');
  include_once 'conexion.php';
  $sqlZonas = ("SELECT * FROM zonas");
  //$queryData   = mysqli_query($con, $sqlZonas);
  $sentencia1 = $pdo->prepare($sqlZonas);
  $sentencia1->execute();
  $queryData = $sentencia1->fetchAll();
  //$total_zonas = mysqli_num_rows($queryData); 
  $total_zonas = count($queryData); 
  $paginas = ceil($total_zonas/10);
  //echo $paginas;
  $cantNE = $cantSE = $cantNO = $cantSO = 0;  ///////
  //while ($row = $sentencia1->fetchAll()) {
  foreach($queryData as $row){
    $enlaces[] = $row['enlace'];
    $latitudes[] = $row['lat'];
		$longitudes[] = $row['lng'];
    //$coordinates[] = 'new google.maps.LatLng(' . $row['lat'] .','. $row['lng'] .'),';
    //
    $points_polygon = count($poligonos["NE"]["vertices_x"]);
    if (is_in_polygon($points_polygon, $poligonos["NE"]["vertices_x"], $poligonos["NE"]["vertices_y"], $row['lat'], $row['lng'])){
      $cantNE++; 
    }
    if (is_in_polygon($points_polygon, $poligonos["SE"]["vertices_x"], $poligonos["SE"]["vertices_y"], $row['lat'], $row['lng'])){
      $cantSE++; 
    }
    if (is_in_polygon($points_polygon, $poligonos["NO"]["vertices_x"], $poligonos["NO"]["vertices_y"], $row['lat'], $row['lng'])){
      $cantNO++; 
    }
    if (is_in_polygon($points_polygon, $poligonos["SO"]["vertices_x"], $poligonos["SO"]["vertices_y"], $row['lat'], $row['lng'])){
      $cantSO++; 
    }
    //
  }
    // Verifica si un punto esta dentro o fuera de un poligono
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
            $c = !$c;
      }
      return $c;
    }
  ?>

      <h6 class="text-center">
        Lista total de  Entregas <strong>(<?php echo $total_zonas; ?>)</strong>
      </h6>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Zona</th>
              <th>N° Entregas</th>
            </tr>
          </thead>
          <tbody>
           <tr>
              <td>1</td>
              <td>Zona(A) NorOeste</td>
              <td><?php echo $cantNO; ?></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Zona(B) NorEste</td>
              <td><?php echo $cantNE; ?></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Zona(C) SurOeste</td>
              <td><?php echo $cantSO; ?></td>
            </tr>
            <tr>
              <td>4</td>
              <td>Zona(D) SurEste</td>
              <td><?php echo $cantSE; ?></td>
            </tr>
          </tbody>
        </table>

    </div>

    <div class="col-md-7">
        <?php 
          if($paginas != 0){
            if(!$_GET){
              header('Location:index.php?pagina=1');
            }
            
            if($_GET['pagina']>$paginas || $_GET['pagina'] <=0 ){
              header('Location:index.php?pagina=1');
            }
          }
          

          include_once 'conexion.php';
          if($paginas == 0){
            $zonas_x_pagina = 10;
            $iniciar = 1;
            $pagina = 1;
          } else {
            $pagina = ($_GET['pagina']-1);
          }
          $zonas_x_pagina = 10;
          $iniciar = $pagina*$zonas_x_pagina;
          //echo $iniciar;
          //$sql_zonas = 'SELECT * FROM zonas LIMIT 0,10';
          $sql_zonas = 'SELECT * FROM zonas LIMIT :iniciar,:nzonas';
          $sentencia_zonas = $pdo->prepare($sql_zonas);
          $sentencia_zonas->bindParam(':iniciar', $iniciar, PDO::PARAM_INT);
          $sentencia_zonas->bindParam(':nzonas', $zonas_x_pagina, PDO::PARAM_INT);
          $sentencia_zonas->execute();

          $resultado_zonas = $sentencia_zonas->fetchAll();
          //var_dump($resultado_zonas);
        ?>
        <!-- Caja de búsqueda -->
          <input id="searchTerm" placeholder="Buscar" type="text" onkeyup="doSearch()" />
        <table id="regTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>URL</th>
              <th>Latitud</th>
              <th>Longitud</th>
            </tr>
          </thead>
          <tbody> 
            <?php foreach($resultado_zonas as $zona): ?>
            <tr>
              <th ><?php echo $zona['id']; ?></th>
              <td style="max-width: 350px; overflow: hidden;"><?php echo $zona['enlace']; ?></td>
              <td><?php echo $zona['lat']; ?></td>
              <td><?php echo $zona['lng']; ?></td>
              <td action="delete.php" method="DELETE" >
                  <a class='btn btn-link' href="delete.php?id=<?php echo $zona['id'] ?>"> Eliminar </a>
              </td>
            </tr>
            <?php endforeach ?>
            
          </tbody>
        </table>

            <?php
            //PAGINACION calculamos la primera y última página a mostrar
            $url = "index.php";
            if($paginas == 0){
              $pagina = 1;
            }else{
              $pagina = $_GET['pagina'];
            }
            //$pagina = $_GET['pagina'];
            $total_paginas = $paginas;
            $primera = $pagina - ($pagina % 10) + 1;
            if ($primera > $pagina) { $primera = $primera - 10; }
            $ultima = $primera + 9 > $total_paginas ? $total_paginas : $primera + 9; 
            ?>  
        <!-- Script de PAGINACION -->
        <nav aria-label="Page navigation exxample" class="text-center">
            <ul class="pagination">
              <?php
              if ($total_paginas > 1) {
                  // comprobamos $primera en lugar de $pagina
                  if ($primera != 1)
                      echo '<li class="page-item"><a class="page-link" href="'.$url.'?pagina='.($primera-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';

                  // mostramos de la primera a la última
                  for ($i = $primera; $i <=$ultima; $i++){
                      if ($pagina == $i)
                          echo '<li class="page-item active"><a class="page-link" href="#">'.$pagina.'</a></li>';
                      else
                          echo '<li class="page-item"><a class="page-link" href="'.$url.'?pagina='.$i.'">'.$i.'</a></li>';
                  }

                  if ($i <= $total_paginas)
                      echo '<li class="page-item"><a class="page-link" href="'.$url.'?pagina='.($i).'"><span aria-hidden="true">&raquo;</span></a></li>';
              }
              ?>
            </ul>
        </nav>
    </div>
  </div>

  <hr>
  

<!-- Script de mapas -->
  <div class="row">
    <div class="col-md-12">
      
      <div id="mapa" style="width: 100%; height: 800px;"></div>
    </div>
    
  </div>

</div>

<!-- Script de cargar excel -->
<script type="text/javascript">
  function uploadFile() {
    var form_data = new FormData();
    var files = $("#file-input")[0].files[0];
    form_data.append('dataCliente', files);
      $.ajax({
        url: "upload_excel.php",
        type: "post",
        data: form_data,
        processData: false,
        contentType: false,
      })
      .done(function(res) {
        $('#respuesta').html(res)
        alert('Registros Agregados: ' + res);
      });   
  }
</script>

<!-- Script de búsqueda -->
<script>
  // Busco todos los controles de búsqueda.
  function doSearch() {
      var tableReg = document.getElementById('regTable');
      var searchText = document.getElementById('searchTerm').value.toLowerCase();
      for (var i = 1; i < tableReg.rows.length; i++) {
          var cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
          var found = false;
          for (var j = 0; j < cellsOfRow.length && !found; j++) {
              var compareWith = cellsOfRow[j].innerHTML.toLowerCase();
              if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)) {
                  found = true;
              }
          }
          if (found) {
              tableReg.rows[i].style.display = '';
          } else {
              tableReg.rows[i].style.display = 'none';
          }
      }
  }
</script>

<script>


  //var coordenadas = { lat: -17.773916, lng: -63.166213 };
  function iniciarMapa() {
    var mapOptions = {
      zoom: 13,
      center: { lat: -17.783352, lng: -63.18213 },
      mapTypeId: google.maps.MapTypeId.SATELITE
    };

    mapa = new google.maps.Map(document.getElementById("mapa"), mapOptions);

    points = {<?php echo'lat:'. json_encode($latitudes) .', lng:'. json_encode($longitudes) ;?>};
    
    //console.log(points);
    //console.log(points.lat.length);
    //console.log(points.lng[1]);
    //console.log(markers.lat);
    for (var i = 0; i < points.lat.length; i++) {
      var marker = new google.maps.Marker({
        position: {lat: parseFloat(points.lat[i]), lng: parseFloat(points.lng[i])},
        map: mapa,
      });
    }
    //////Dibujar poligono
      var verticesPoligonoNO = [   //A
        { lat: -17.78332322, lng: -63.18212634 },   //-17.78332322575316, -63.18212634728461
        { lat: -17.78275123, lng: -63.25076318 },  //-17.782751238817152, -63.25076318298971
        { lat: -17.69822267, lng: -63.25093484 },  //-17.698222677585658, -63.25093484324312
        { lat: -17.69855191, lng: -63.18205449 }   //-17.698551913301227, -63.18205449176916
      ];
      var verticesPoligonoNE = [   //B .....
        { lat: -17.78332322, lng: -63.18212634 },
        { lat: -17.65297037, lng: -63.18202015 }, // -17.652970378379557, -63.18202015029238 //-17.653152936229088, -63.181889825548865
        { lat: -17.65297037, lng: -63.04366110 }, //-17.652970378573457, -63.04366110881027 //-17.652921602184744, -63.033074392795264
        { lat: -17.78296777, lng: -63.04348945 }   // -17.782967771342207, -63.043489450258754, -63.04194449908418////-17.77618042036549, -63.03307439510065
      ];
      var verticesPoligonoSO = [   //C
        { lat: -17.78332322, lng: -63.18212634 },
        { lat: -17.87803874, lng: -63.18256119 }, //-17.878038740719923, -63.18256119779767
        { lat: -17.87802027, lng: -63.27582316 },  //-17.87802027708098, -63.275823168863695
        { lat: -17.78254027, lng: -63.27592817 }   //-17.78254027640437, -63.27592817629468
      ];
      var verticesPoligonoSE = [   //D
        { lat: -17.78332322, lng: -63.18212634 },
        { lat: -17.78301180, lng: -63.05997169 }, //-17.78301180601427, -63.0599716906885
        { lat: -17.89085126, lng: -63.05834076 },  //-17.89085126542893, -63.05834076611401
        { lat: -17.89011184, lng: -63.18261363 }   //-17.890111845785835, -63.182613637667146
      ];

      var poligonoNO = new google.maps.Polygon({
        path: verticesPoligonoNO,
        map: mapa,
        strokeColor: 'rgb(255, 0, 0)',
        fillColor: 'rgb(225, 225, 171)',
        strokeWeight: 2,
      });

      var poligonoNE = new google.maps.Polygon({
        path: verticesPoligonoNE,
        map: mapa,
        strokeColor: 'rgb(255, 0, 0)',
        strokeWeight: 2,
      });

      var poligonoSO = new google.maps.Polygon({
        path: verticesPoligonoSO,
        map: mapa,
        strokeColor: 'rgb(255, 0, 0)',
        strokeWeight: 2,
      });

      var poligonoSE = new google.maps.Polygon({
        path: verticesPoligonoSE,
        map: mapa,
        strokeColor: 'rgb(255, 0, 0)',
        fillColor: 'rgb(225, 225, 171)',
        strokeWeight: 2,
      });
      /////// ETIQUETAS EN POLIGONOS ////////
      var popup = new google.maps.InfoWindow();
      poligonoNO.addListener('click', function (e) {
        popup.setContent('Zona(A) Nor-Oeste');
        popup.setPosition(e.latLng);
        popup.open(mapa);
      });
      var popup = new google.maps.InfoWindow();
      poligonoSO.addListener('click', function (e) {
        popup.setContent('Zona(C) Sur-Oeste');
        popup.setPosition(e.latLng);
        popup.open(mapa);
      });
      var popup = new google.maps.InfoWindow();
      poligonoNE.addListener('click', function (e) {
        popup.setContent('Zona(B) Nor-Este');
        popup.setPosition(e.latLng);
        popup.open(mapa);
      });
      var popup = new google.maps.InfoWindow();
      poligonoSE.addListener('click', function (e) {
        popup.setContent('Zona(D) Sur-Este');
        popup.setPosition(e.latLng);
        popup.open(mapa);
      });
  }
</script>

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdeM32-K4Y9CYn39EHxQjLHbsNokIUhfc&callback=iniciarMapa"
  async>
</script>


<script src="js/jquery.min.js"></script>
<script src="'js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="js/main.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(window).load(function() {
            $(".cargando").fadeOut(1000);
        });      
});
</script>

</body>
</html>