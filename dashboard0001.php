<?php
session_start();

$intervalo = $_POST["intervalo"] ;

$logged = $_SESSION['logged'];

$users_id = $_SESSION['users_id']; // de la variable de session sacamos el ID del usuario

$devices = $_SESSION['devices']; // variable de sesion que viene de devices.php con los datos de la vista Users_devices_pacientes

$dispo = $_GET['device']; //variable get que proviene del foreach de device.php y contiene el nro de serie del dispositivo correspondiente al dasboard del paciente

$GLOBALS['cant'] = 30;// cantidad de datos a mostrar 

//$GLOBALS['intervalo'] = 5000;// intervalo de tiempo para solicitar datos 

if(!$logged){
  echo "Ingreso no autorizado";
  die(); 
}

print_r($dispo);
//coenctamos a DB
$conn = mysqli_connect("localhost","admin_monitorIOT","FIuba123","admin_monitorIOT");



//RECUPERAMOS LOS DATOS DE ESTE USUARIO UTILIZANDO EL DATO DEL NRO DE SERIE DEL SIPOSITOVO GUARDADO EN $dispo
// de la vista que relaciona user con device  y datos del paciente me traigo los datos de un usuario en particular 
$result = $conn->query("SELECT * FROM `users_devices_data` WHERE `devices_serie` = '".$dispo."'");
$data = $result->fetch_all(MYSQLI_ASSOC);

//echo $dateArray[0];

//print_r($data[3]['data_fecha_unix']);
//print_r( date("d-m-Y | h:i:sa", $data[3]['data_fecha_unix'])); //le resto 3 horas = 10800000 mill
//print_r( date($data[3]['data_fecha_unix']));
//die();


//Recuperamos el nombre del paciente pero desde la vista users_device_paciente, ya que si tomamos el dato 
//del nombre desde la vista users_devices_data, si el paciente no envio datos no se recupera el nombre de la BD 
$resultPaciente = $conn->query("SELECT * FROM `users_devices_pacientes` WHERE `devices_serie` = '".$dispo."'");
$name = $resultPaciente->fetch_all(MYSQLI_ASSOC);
$nombre_paciente= $name[0]['pacientes_name'];


$dateArray = array ();   //array de datos
$dateUnixArray =array();
$spo2Array = array ();  
$bpmArray= array ();
$tempArray = array ();

// juntos los datos del paciente en un array para mostrar en la Tabla
foreach ($data as $data) {
  array_push ($dateArray,$data['data_fecha_unix']);
// array_push ($dateUnixArray, strtotime ($data['data_date'])); // datos en tiempo Unix 
  array_push ($spo2Array,$data['data_spo2']);
  array_push ($bpmArray,$data['data_bpm']);
  array_push ($tempArray,$data['data_temp']);
}




$usuario = $_GET['device'];// get que proviene del foreach de device.php y contiene el nro de serie del dispositivo correspondiente al dasboard del paciente

function grafico_spo2($usuario){

  $conn = mysqli_connect("localhost","admin_monitorIOT","FIuba123","admin_monitorIOT");
  $result = $conn->query("SELECT * FROM `users_devices_data` WHERE `devices_serie` = '".$usuario."'");
  $data = $result->fetch_all(MYSQLI_ASSOC);

  $i=0;

while ($i < $GLOBALS['cant']) {

  echo "[";
  echo ($data[$i]['data_fecha_unix']*1000); 
  echo ",";
  echo $data[$i]['data_spo2'];
   echo "],";
   $i++;
  }

  
}


function grafico_bpm($usuario){

  $conn = mysqli_connect("localhost","admin_monitorIOT","FIuba123","admin_monitorIOT");
  $result = $conn->query("SELECT * FROM `users_devices_data` WHERE `devices_serie` = '".$usuario ."'");
  $data = $result->fetch_all(MYSQLI_ASSOC);

  $i=0;

while ($i < $GLOBALS['cant']) {

  echo "[";
  //echo strtotime($data[$i]['data_date'])*1000 - 10800000; //le resto 3 horas = 10800000 mill
    //echo (date('d/m/Y H:M:S',$data[$i]['data_fecha_unix'])*1000); 
  echo (($data[$i]['data_fecha_unix'])*1000); 
  echo ",";
  echo $data[$i]['data_bpm'];
   echo "],";
   $i++;
  }
}


function grafico_temp($usuario){

  $conn = mysqli_connect("localhost","admin_monitorIOT","FIuba123","admin_monitorIOT");
  $result = $conn->query("SELECT * FROM `users_devices_data` WHERE `devices_serie` = '".$usuario ."'");
  $data = $result->fetch_all(MYSQLI_ASSOC);

  $i=0;

while ($i < $GLOBALS['cant']) {

  echo "[";
  echo ($data[$i]['data_fecha_unix'])*1000; 
  echo ",";
  echo $data[$i]['data_temp'];
   echo "],";
   $i++;
  }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>


  <meta http-equiv="refresh" content="60" > 
  <meta charset="utf-8" />
  <title>Monitoreo IoT </title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"> </script>
  <script type="text/javascript" src="jquery.toCSV.min.js"> </script>

  <script>
		function exportar(){
			$("table").toCSV();
		}
	</script>

  

</head>



<body>



  <div class="app" id="app">

    <!-- ############ LAYOUT START-->

    <!-- BARRA IZQUIERDA -->
    <!-- aside -->
    <div id="aside" class="app-aside modal nav-dropdown" >
      <!-- fluid app aside -->
      <div class="left navside dark dk" data-layout="column">
        <div class="navbar no-radius">
          <!-- brand -->
          <a class="navbar-brand">
            <div ui-include="'assets/images/logo.svg'"></div>
            <img src="assets/images/logo.png" alt="." class="hide">
            <span class="hidden-folded inline">Monitor IoT</span>
          </a>
          <!-- / brand -->
        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-dark">

            <ul class="nav" ui-nav>
              <li class="nav-header hidden-folded">
                <small class="text-muted"> Paciente: <?php  echo $nombre_paciente ?> </small> <br>

                <small class="text-muted">Dipositivo Nro.: <?php echo $dispo?>   </small>

                
                
               <form action="dashboard<?=$dispo?>.php?device=<?=$dispo?>"   method="post" > 

               
              
               Intervalo de tiempo:<input type="number"  step="any" name="intervalo" value="intervalo"  > </small> <br> 

                 <input  type="submit" id="intervalo_tiempo" onclick="process_intervalo()"  value="Enviar"  style="padding: 1px; /*espacio alrededor texto*/ background-color: #2e518b; /*color botón*/ color: #ffffff; /*color texto*/ " > </small> <br>
                 
                 
                 Intervalo (en min.):  <?php isset($intervalo) ? print $intervalo : ""; ?><br>
                </form>
                
              
                 
                 
              </li>
            <!-- 
              <li>
                <a href="dashboard.php" >
                  <span class="nav-icon">
                    <i class="fa fa-building-o"></i>
                  </span>
                  <span class="nav-text">Principal</span>
                </a>
              </li>

              <li>
                <a href="devices.php" >
                  <span class="nav-icon">
                    <i class="fa fa-cogs"></i>
                  </span>
                  <span class="nav-text">Dispositivos</span>
                </a>
              </li>
               -->
               
             
            <!-- SWItCH1  -->
                
      <div class="row">
            <div class="col-sm-9">
            <div class="box p-a" style="position: relative; right: -10px;  width: 90% ;  ">
                <div class="form-group row">
            
                  <div class="col-sm-10">
              
                  <input type="button"  id="input_led1" onclick="process_consulta1()" style="padding: 10px; /*espacio alrededor texto*/ background-color: #2e518b; /*color botón*/ color: #ffffff; /*color texto*/ text-decoration: none; /*decoración texto*/ text-transform: uppercase;" rel="noopener noreferrer nofollow" type="checkbox" value= "Consulta">
                  
                  </label>
                  </div>
                </div> 
                

                         
 <!--      <div class="row">
            <div class="col-xs-6 col-sm-9">
              <div class="box p-a">
                <div class="form-group row">
                  <label class="col-sm-2 form-control-label"></label>
                  <div class="col-sm-10">
                    <label class="ui-switch ui-switch-md info m-t-xs">
                      <input id="input_led1" onchange="process_consulta1()"  type="checkbox" style="padding: 10px; /*espacio alrededor texto*/ background-color: #2e518b; /*color botón*/ color: #ffffff; /*color texto*/ text-decoration: none; /*decoración texto*/ text-transform: uppercase;" rel="noopener noreferrer nofollow" type="checkbox">
                      <i></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>    -->
              

             <!-- valores instantaneos   -->
           
              <div class="col-sm-30">
              
                <div class="clear">
                  <h6 class="m-0 text-lg _300"><b id="display2_spo2">-- </b> </h4>
                  <small class="text-muted">SpO2(%)   </small>
                </div>

                <div class="clear">
                  <h6 class="m-0 text-lg _300"><b id="display2_bpm">-- </b></h4>
                  <small class="text-muted">Pulso(bpm) </small>
                </div>

                <div class="clear">
                  <h6 class="m-0 text-lg _300"><b id="display2_temp">-- </b></h4>
                  <small class="text-muted">Temp(°C)</small>
                </div>
              
              </div>
            </div>
            </div>
            </div>
             <!-- FIN valores instantaneos   -->
          

            </ul>
          </nav>
        </div>
        <div class="b-t">
          <div class="nav-fold">
          <!--  <a href="profile.html"> -->
              <span class="pull-left">
              <!--  <img src="assets/images/a0.jpg" alt="..." class="w-40 img-circle"> -->
              </span>
              <span class="clear hidden-folded p-x">
                <span class="block _500">Oscar Cejas</span>
                <small class="block text-muted"><i class="fa fa-circle text-success m-r-sm"></i>online</small>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- / -->

    <!-- content -->
    <div id="content" class="app-content box-shadow-z0" role="main"> <!-- ojo es principla esto  -->
      <div class="app-header white box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>
          <!-- / -->

          <!-- Page title - Bind to $state's title -->
          <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

          <!-- navbar collapse -->
          <div class="collapse navbar-collapse" id="collapse">
            <!-- link and dropdown -->
            <ul class="nav navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a class="nav-link" href data-toggle="dropdown">
                  <i class="fa fa-fw fa-plus text-muted"></i>
                  <span>New</span>
                </a>
                <div ui-include="'views/blocks/dropdown.new.html'"></div>

                
              </li>
            </ul>

            <div ui-include="'views/blocks/navbar.form.html'"></div> 
            <!-- / -->
          </div>
          <!-- / navbar collapse -->

                     <!-- BARRA DE LA DERECHA -->


     <!--  <ul class="nav navbar-nav ml-auto flex-row">
            <li class="nav-item dropdown pos-stc-xs">
              <a class="nav-link mr-2" href data-toggle="dropdown">
                <i class="material-icons">&#xe7f5;</i>
                <span class="label label-sm up warn">3</span>
              </a>
            <div ui-include="'views/blocks/dropdown.notification.html'"></div> 
            </li>
            <li class="nav-item dropdown">
        <a class="nav-link p-0 clear" href="#" data-toggle="dropdown">
                <span class="avatar w-32">
                  <img src="assets/images/a0.jpg" alt="...">
                  <i class="on b-white bottom"></i>
                </span>
              </a> 
              <div ui-include="'views/blocks/dropdown.user.html'"></div>
            </li>
            <li class="nav-item hidden-md-up">
              <a class="nav-link pl-2" data-toggle="collapse" data-target="#collapse">
                <i class="material-icons">&#xe5d4;</i>
              </a>
            </li>
          </ul> -->
          <!-- / navbar right -->

         
        </div>
      </div>


      <!-- PIE DE PAGINA -->
      <!-- <div class="app-footer">
        <div class="p-2 text-xs">
          <div class="pull-right text-muted py-1">
            &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span>
            <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
          </div>
          <div class="nav">
            <a class="nav-link" href="">About</a>
          </div>
        </div>
      </div> --> 

      <div ui-view class="app-body" id="view">


        <!-- SECCION CENTRAL -->
        <div class="padding">

          <!-- VALORES EN TIEMPO REAL -->
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded  accent">
                  <i class="material-icons md-24"></i>
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_spo2">-- </b><span class="text-sm"> %</span></h4>
                  <small class="text-muted">SpO2 </small>
                </div>
              </div>
            </div>

            <div class="col-xs-6 col-sm-4">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded danger">
                    <img src="https://cdn-icons-png.flaticon.com/512/2088/2088818.png" width="35" height="35" alt="ritmo cardiaco  icono gratis" title="ritmo cardiaco icono gratis">
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_bpm">-- </b><span class="text-sm"> BPM</span></h4>
                  <small class="text-muted">Pulso Cardiaco </small>
                </div>
              </div>
            </div>

            <div class="col-xs-6 col-sm-4">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded primary">
                    <img src="https://cdn-icons-png.flaticon.com/128/2316/2316581.png" data-src="https://cdn-icons-png.flaticon.com/128/2316/2316581.png" alt="calor icono gratis" title="calor icono gratis" width="40" height="40" class="lzy lazyload--done" srcset="https://cdn-icons-png.flaticon.com/128/2316/2316581.png 4x">
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_temp">-- </b><span class="text-sm"> °C</span></h4>
                  <small class="text-muted">Temp Corporal</small>
                </div>
              </div>
            </div>
          </div>
          


   
            <!-- LLAMADO A LOS GRAFICOS ESCRITO EN JS AL FINAL  -->
           <div class="row">
       
 
             <div id="container1"  class="box p-a" style="position: relative; width: 95% ; height: 300px; right: -10px;  padding: 10px; background-color: #fff; ">
             </div>
             
             <div id="container2"  class="box p-a" style="position: relative; width: 95% ; height: 300px; right: -10px;   padding: 10px; background-color: #fff; ">
             </div>

             <div id="container3"  class="box p-a" style="position: relative; width: 95% ; height: 300px; right: -10px;   padding: 10px; background-color: #fff; ">
             </div>

        
            </div>
        
        
      


         <!-- Tabla con datos Historicos -->

               <div class="col-sm-9">
                 <div  class="box p-a">
               
                    <div class="box-header">
                      <h2>Datos historicos del paciente:  <?php echo $nombre_paciente ?></h2>
                
                    </div>
                    <table  id="data_table" class="table table-striped b-t">
                    <button onclick="exportar()" download="your-foo.csv" class=" dark pace-done" ui-class="dark"; >Exportar CSV</button>
	
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Spo2 (%)</th>
                          <th>pulsos (BPM)</th>
                          <th>Temp (°C)</th>

                        </tr>
                      </thead>
                      <tbody>
                      
                         <?php for ($id=0 ; $id< $GLOBALS['cant']; $id++ ) {?>
                          
                          <tr>
                            <td><?php echo strftime("%d/%m/%Y %H:%M:%S",$dateArray[$id]) ?></td>
                            <td><?php echo $spo2Array[$id] ?></td>
                            <td><?php echo $bpmArray[$id] ?></td>
                            <td><?php echo $tempArray[$id] ?></td>
                            
                          </tr>
                         <?php  } ?>

                      </tbody>
                        
                    </table>
                 </div>
                </div>
              

          <!-- SWItCH1  -->
          
          <!--
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <div class="box p-a">
                <div class="form-group row">
                  <label class="col-sm-2 form-control-label">LED1</label>
                  <div class="col-sm-10">
                    <label class="ui-switch ui-switch-md info m-t-xs">
                      <input id="input_led1" onchange="process_led1()"  type="checkbox" >
                      <i></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          -->
          
          
        </div>

        <!-- ############ PAGE END-->

      </div>

    </div>
    <!-- / -->

    <!-- SELECTOR DE TEMAS -->
    <div id="switcher">
      <div class="switcher box-color dark-white text-color" id="sw-theme">
        <a href ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
          <i class="fa fa-gear"></i>
        </a>
        <div class="box-header">
          <a href="https://themeforest.net/item/flatkit-app-ui-kit/13231484?ref=flatfull" class="btn btn-xs rounded danger pull-right">BUY</a>
          <h2>Theme Switcher</h2>
        </div>
        <div class="box-divider"></div>
        <div class="box-body">
          <p class="hidden-md-down">
            <label class="md-check m-y-xs"  data-target="folded">
              <input type="checkbox">
              <i class="green"></i>
              <span class="hidden-folded">Folded Aside</span>
            </label>
            <label class="md-check m-y-xs" data-target="boxed">
              <input type="checkbox">
              <i class="green"></i>
              <span class="hidden-folded">Boxed Layout</span>
            </label>
            <label class="m-y-xs pointer" ui-fullscreen>
              <span class="fa fa-expand fa-fw m-r-xs"></span>
              <span>Fullscreen Mode</span>
            </label>
          </p>
          <p>Colors:</p>
          <p data-target="themeID">
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'primary', accent:'accent', warn:'warn'}">
              <input type="radio" name="color" value="1">
              <i class="primary"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'accent', accent:'cyan', warn:'warn'}">
              <input type="radio" name="color" value="2">
              <i class="accent"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warn', accent:'light-blue', warn:'warning'}">
              <input type="radio" name="color" value="3">
              <i class="warn"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'success', accent:'teal', warn:'lime'}">
              <input type="radio" name="color" value="4">
              <i class="success"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'info', accent:'light-blue', warn:'success'}">
              <input type="radio" name="color" value="5">
              <i class="info"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'blue', accent:'indigo', warn:'primary'}">
              <input type="radio" name="color" value="6">
              <i class="blue"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warning', accent:'grey-100', warn:'success'}">
              <input type="radio" name="color" value="7">
              <i class="warning"></i>
            </label>
            <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'danger', accent:'grey-100', warn:'grey-300'}">
              <input type="radio" name="color" value="8">
              <i class="danger"></i>
            </label>
          </p>
          <p>Themes:</p>
          <div data-target="bg" class="row no-gutter text-u-c text-center _600 clearfix">
            <label class="p-a col-sm-6 light pointer m-0">
              <input type="radio" name="theme" value="" hidden>
              Light
            </label>
            <label class="p-a col-sm-6 grey pointer m-0">
              <input type="radio" name="theme" value="grey" hidden>
              Grey
            </label>
            <label class="p-a col-sm-6 dark pointer m-0">
              <input type="radio" name="theme" value="dark" hidden>
              Dark
            </label>
            <label class="p-a col-sm-6 black pointer m-0">
              <input type="radio" name="theme" value="black" hidden>
              Black
            </label>
          </div>
        </div>
      </div>
     </div>
   </div>
<!-- / -->

<!-- ############ LAYOUT END-->

</div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
<script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="libs/jquery/underscore/underscore-min.js"></script>
<script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="libs/jquery/PACE/pace.min.js"></script>

<script src="html/scripts/config.lazyload.js"></script>

<script src="html/scripts/palette.js"></script>
<script src="html/scripts/ui-load.js"></script>
<script src="html/scripts/ui-jp.js"></script>
<script src="html/scripts/ui-include.js"></script>
<script src="html/scripts/ui-device.js"></script>
<script src="html/scripts/ui-form.js"></script>
<script src="html/scripts/ui-nav.js"></script>
<script src="html/scripts/ui-screenfull.js"></script>
<script src="html/scripts/ui-scroll-to.js"></script>
<script src="html/scripts/ui-toggle-class.js"></script>

<script src="html/scripts/app.js"></script>



<!-- ajax -->
<script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="html/scripts/ajax.js"></script>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<!-- Highcharts -->

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/parallel-coordinates.js"></script>



  <!-- Grafico Oximetria -->
  <div class="col-xs-6">
    <div class="box">
                
     <script> 

     $(function () {
     $('#container1').highcharts({
        chart: {
            type: 'spline',
            zoomType: 'xy',
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Oximetría'
        },
        xAxis: {
             type: 'datetime',
             
        },
        yAxis: {
            title: {
                text: '%'
            }
        },

        
        series: [{
            name: 'Spo2',
            data: [<?php 
                grafico_spo2($usuario);
                ?>     
            ]}, 
      
           ],
       });
     });
    </script>

   </div>
  </div>

  <!-- Grafico Pulsimetria-->
  <div class="col-xs-6"> 
   <div class="box">
                 
     <script> 

     $(function () {
     $('#container2').highcharts({
        chart: {
            type: 'spline',
            zoomType: 'xy'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Pulsimetría'
        },
        xAxis: {
             type: 'datetime',
             
        },
        yAxis: {
            title: {
                text: 'bpm'
            }
        },

        
        series: [{
            name: 'Pulsimetría',
            data: [<?php 
                
                grafico_bpm($usuario);
                ?>     
            ]}, 
      
           ],
       });
    });
  </script>

 </div>
</div>

 <!-- Grafico Temperatura -->
<div class="col-xs-6"> 
   <div class="box">
                 
     <script> 

     $(function () {
     $('#container3').highcharts({
        chart: {
            type: 'spline',
            zoomType: 'xy'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Temperatura',
        },
        xAxis: {
             type: 'datetime',
             
        },
        yAxis: {
            title: {
                text: '°C'
            }
        },

        
        series: [{
            name: 'Tempertura',
            data: [<?php 
                grafico_temp($usuario);
                ?>     
            ]}, 
      
           ],
       });
    });
  </script>

 </div>
</div>


<script type="text/javascript">
jQuery.fn.toCSV = function() {
  var data = $(this).first(); //Only one table
  var csvData = [];
  var tmpArr = [];
  var tmpStr = '';
  data.find("tr").each(function() {
      var th=$(this).find("th");
      if(th.length) {
          th.each(function() {
            tmpStr = $(this).text().replace(/"/g, '""');
            tmpArr.push('"' + tmpStr + '"');
          });
          csvData.push(tmpArr);
      } else {
          tmpArr = [];
             $(this).find("td").each(function() {
                  if($(this).text().match(/^-{0,1}\d*\.{0,1}\d+$/)) {
                      tmpArr.push(parseFloat($(this).text()));
                  } else {
                      tmpStr = $(this).text().replace(/"/g, '""');
                      tmpArr.push('"' + tmpStr + '"');
                  }
             });
          csvData.push(tmpArr.join(';'));
      }
  });
  var output = csvData.join('\n');
  var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(output);
  var downloadLink = document.createElement("a");
  downloadLink.href = uri;
  downloadLink.download = document.title ? document.title.replace(/ /g, " <?php  echo $nombre_paciente ?> ") + ".csv" : "data.csv";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}


</script>

<script type="text/javascript">



 

function update_values(spo2, bpm, temp){ // funcion que actualiza valores en display1 
  $("#display_spo2").html(spo2);
  $("#display_bpm").html(bpm);
  $("#display_temp").html(temp);
 
}

function update_values2(spo2, bpm, temp){ // funcion que actualiza valores en display2
  $("#display2_spo2").html(spo2);
  $("#display2_bpm").html(bpm);
  $("#display2_temp").html(temp);
 
}
//update_values("77","99","123");

function process_msg(topic, message){ //funcion que procesa un msje recibido de un topico lo separa en diferentes variables
  // ej: "10,11,12"

  if (topic == "<?php echo $usuario ?>/values"){ // Harcodeo rancio, tiene q venir de la variable $dispo
    
    var msg = message.toString();
    var sp = msg.split(",");    // separa el string en 3 variables usando
    var spo2 = sp[0];
    var bpm  = sp[1];
    var temp = sp[2];
    var nro_serie= sp[3];
    var fecha_unix= sp[4];
    update_values(spo2,bpm,temp);
  } 

 else if (topic == "<?php echo $usuario ?>/consulta"){ // Harcodeo rancio, tiene q venir de la variable $dispo
    
    var msg = message.toString();
    var sp = msg.split(",");    // separa el string en 3 variables usando
    var spo2 = sp[0];
    var bpm  = sp[1];
    var temp = sp[2];
    var nro_serie= sp[3];
    var fecha_unix= sp[4];
    update_values2(spo2,bpm,temp);
  } 
  
  else{
    console.log( 'mensaje no procesado ')
    } 
}


//process_msg("values","30,45,70"); // test de mensaje enviado


// var interval =  setInterval('process_consulta1()', 60); segundos o usar php con la variable $intervalo

<?php echo $intervalo ?>

function process_consulta1(){
  {
    if ($('#input_led1')){
    console.log("Encendido");
    client.publish('<?php echo $usuario ?>/led1', 'on', (error) => {  // o harcodeo rancio del 0001
    console.log(error || 'Mensaje consulta enviado!!!');
    })
  }
  }
}

function process_intervalo(){ 
  {
    
    if ($('#intervalo_tiempo')){
    console.log("Encendido");
    client.publish('<?php echo $usuario ?>/intervalo', '<?php echo $intervalo ?>', (error) => {  // o harcodeo rancio del 0001
    // mult x 1000 par aenviarlo como milisegundos
      console.log(error || 'Mensaje "<?php echo $intervalo ?>"  intervalo enviado!!! ');
    })
  }
  }
}


/*
function process_consulta2(){
  if ($('#input_led1').is(":checked")){
    console.log("Encendido");
    client.publish('0001/led1', 'on', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    })
  }else{
    console.log("Apagado");
    client.publish('0001/led1', 'off', (error) => { //ENTRA POR ACA
      console.log(error || 'Mensaje enviado!!!');
    })
  }
}
*/





//******************************
//****** CONEXION  *************



// connect options
const options = {
      connectTimeout: 4000,

      // Authentication
      clientId: 'iotmc',
      username: 'web_client',
      password: '121212',

      keepalive: 60,
      clean: true,
}

var connected = false;

// WebSocket connect url
const WebSocket_URL = 'wss://vmscloud.ga:8094/mqtt'


const client = mqtt.connect(WebSocket_URL, options)


client.on('connect', () => {
    console.log('Mqtt conectado por WS! Exito!')

    client.subscribe('0001/values', { qos: 0 }, (error) => {
      if (!error) {
        console.log('Suscripción1 exitosa!!!');
      }else{
        console.log('Suscripción fallida!');
      }
    })

  

    client.subscribe('0001/consulta', { qos: 0 }, (error) => {
      if (!error) {
        console.log('Suscripción2 exitosa!');
      }else{
        console.log('Suscripción2 fallida!');
      }
    })
     // publica un msje bajo un topico
    // publish(topic, payload, options/callback)
    //client.publish('values', 'esto es un verdadero éxito', (error) => {
     // console.log(error || 'Mensaje enviado!!!')
    //})
})

client.on('message', (topic, message) => {
  console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString());
  process_msg(topic, message);
})

client.on('reconnect', (error) => {
    console.log('Error al reconectar', error);
})

client.on('error', (error) => {
    console.log('Error de conexión:', error);
})


</script>

<!-- endbuild -->
</body>
</html>
