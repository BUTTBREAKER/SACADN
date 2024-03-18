<?php 

require 'con_db.php';

$resultado = null;

if(isset($_POST['guardar'])){

 $ci_repr = $_POST['ci_repr'];
 $nombre_completo = $_POST['nombre_completo'];
 $apellido = $_POST['apellido'];
 $fecha_nac = $_POST['fecha_nac'];
 $edad = $_POST['edad'];
 $estado = $_POST['estado'];
 $lugar = $_POST['lugar'];
 $genero = $_POST['genero'];
 $telefono = $_POST['telefono'];
 $direccion = $_POST['direccion'];
 $fech_repr = date("d/m/y");  

 $sql= "INSERT INTO `representantes`(`ci_repr`, `nombre_completo`, `apellido`, `fecha_nac`, `edad`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_repr`)
 VALUES ('$ci_repr','$nombre_completo','$apellido','$fecha_nac','$edad','$estado','$lugar','$genero','$telefono','$direccion','$fech_repr')";
   
 $resultado = mysqli_query($conn,$sql);


  mysqli_close($conn);

 }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro Representante</title>  
    <link rel="stylesheet" type="text/css" href="">
</head>
<body>
	<div class="contenedor">
		<div class="row">
			<div class="row" style="text-align: center;">

				<?php if($resultado) { ?>
				<h3>REGISTRO GUARDADO</h3>
			   
			      <?php}else { ?>
				<h3>ERROR AL GUARGAR</h3>
			     <?php } ?>
 
 			  <a href="tabla_registro.php" class="btn btn-primary">Regresar</a>

			</div>			
		</div>
	</div>
</body>
</html>