<?php 

require 'con_db.php';

$resultado = null;

if($_POST) {

 $ci_prof = $_POST['ci_prof'];
 $nombre_completo = $_POST['nombre_completo'];
 $apellido = $_POST['apellido'];
 $fecha_nac = $_POST['fecha_nac'];
 $edad = $_POST['edad'];
 $estado = $_POST['estado'];
 $lugar = $_POST['lugar'];
 $genero = $_POST['genero'];
 $telefono = $_POST['telefono'];
 $direccion = $_POST['direccion'];
 $fech_prof = date("d/m/y");  

 $sql= "INSERT INTO `profesores`(`ci_prof`, `nombre_completo`, `apellido`, `fecha_nac`, `edad`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_prof`)
 VALUES ('$ci_prof','$nombre_completo','$apellido','$fecha_nac','$edad','$estado','$lugar','$genero','$telefono','$direccion','$fech_prof')";
   
 $resultado = mysqli_query($conn,$sql);


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

				<?php if($resultado): ?>
					<h3>REGISTRO GUARDADO</h3>
			    <?php else : ?>
					<h3>ERROR AL GUARGAR</h3>
			    <?php endif ?>
 
 			  <a href="Profesores.php" class="btn btn-primary">Regresar</a>

			</div>			
		</div>
	</div>
</body>
</html>
