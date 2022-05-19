
<?php

$intervalo = $_POST["intervalo"] ;

?>


<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Consulta MySQL- MonitorIOT</title>
</head>
<body>



<form action="prueba.php" method="post" >

Nombre: <input type="number" name="intervalo"><br>

        <input type="submit" value="Enviar" onclick="process_intervalo()" >
        

 
</form>

Intervalo (en min.):  <?php isset($intervalo) ? print $intervalo : ""; ?><br>
Hola:  <?php  print $intervalo  ; ?><br>


<script type="text/javascript">

function process_intervalo(){ 
  
    console.log("Encendido  <?php  $intervalo  ; ?>");
    console.log( 'Mensaje "<?php echo $intervalo ?>"  intervalo enviado!!! ');
  
}

</script>


<?php echo $nombre ?>
</body> 
</html>