<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro Representante</title>  
    <link rel="stylesheet" type="text/css" href="">
</head>
<style type="text/css">
  .btn btn-primary
</style>

 <body>
 	<div class="contenedor">
    <form class="table" method="post">
<div class="row">
  <h2 style="text-align: center;">Lista Representantes</h2>
</div>
<div class="row">
  <a href="nuevo.php" class="btn btn-primary">Nuevo Registro</a>
</div>

  <table>
        <div>
          <tr>
            <td>ID</td>
            <td>Cédula</td>
            <td>Nombres</td>
            <td>Apellidos</td>
            <td>Fecha de nacimiento</td>
            <td>Edad</td>
            <td>Estado de nacimiento</td>
            <td>Lugar de nacimiento</td>
            <td>Genero</td>
            <td>Teléfono</td>
            <td>Dirección</td>
            <td>Fecha</td>
          </tr>
                   <tr>
            <td><?php echo $mostrar['0'] ?></td>
            <td><?php echo $mostrar['1'] ?></td>
            <td><?php echo $mostrar['2'] ?></td>
            <td><?php echo $mostrar['3'] ?></td>
            <td><?php echo $mostrar['4'] ?></td>
            <td><?php echo $mostrar['5'] ?></td>
            <td><?php echo $mostrar['6'] ?></td>
            <td><?php echo $mostrar['7'] ?></td>
            <td><?php echo $mostrar['8'] ?></td>
            <td><?php echo $mostrar['9'] ?></td>
            <td><?php echo $mostrar['10'] ?></td>
            <td><?php echo $mostrar['11'] ?></td>
          </tr>
          
        </div>
  </table>
</form>
</div>

