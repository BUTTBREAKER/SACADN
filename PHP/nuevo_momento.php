<?php

require __DIR__ . '/../vendor/autoload.php';
// Incluir el archivo de conexión a la base de datos
/** @var mysqli */
$db = require_once __DIR__ . '/conexion_be.php';
include __DIR__ . '/partials/header.php';


$sql = <<<SQL
  SELECT id, anio_inicio AS periodo FROM periodos
SQL;

$result = $db->query($sql);

?>

<style type="text/css">
	.contenedor {
		display: flex;
		justify-content: center;
		align-items: center;
		min-height: 0;
		padding: 10px 5px 10px 5px;
		width: 700px;
		box-shadow: 10px 10px 10px 10px rgba(0, 0, 0, 0.1);
		border: 1px solid;
		margin: 0 auto;

	}

	.pagina-content {
		padding-top: 50px;
	}

	.title {
		justify-content: center;
		width: 100%;
		height: 1px;
		padding: 0px 1px 25px 1px;
		position: relative;
		color: black;
		font-size: 20px;
	}

	.title1 {
		display: flex;
		margin-block-start: none;
		margin-block-end: 11px;
		margin-inline-start: 10px;
		margin-inline-end: 10px;
		font-weight: none;
	}

	.formulario {
		width: 540px;
		margin: 0 auto;
		padding: 20px 10px 5px 10px;
		background-color: white;
		border: 4px solid;
		border-color: #2DA0FB;
		border-radius: 8px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 16px
	}

	.formulario .input-contenedor {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 14px;
		justify-content: center;
	}

	.input-contenedor .contenedor-input:nth-last-child(1) {
		grid-column: 1/3;
	}

	.contenedor-label {
		display: flex;
		position: relative;
		top: 0.5rem;
		margin: 0 0 0 7px;
		padding: 0 3px;
	}

	.contenedor-input label.texto,
	.icono {
		color: #818CF8;
		background: white;

	}

	.contenedor-input label.texto {
		font-size: 14px;
		font-weight: 700;
		width: fit-content;
	}

	.icono {
		width: 13px;
		height: 13px;
	}

	.contenedor-input input,
	.contenedor-input select,
	.contenedor-input textarea {
		padding: 11px 10px;
		font-size: 16px;
		border: 2px #818CF8 solid;
		border-radius: 5px;
		background: white;
		width: 100%;
	}

	.textAreaDireccion {
		max-width: 475px;
		max-height: 70px;
	}

	.contenedor-input input:focus,
	.contenedor-input select {
		outline: none;
	}

	.formulario input[type="submit"]:hover {
		background-color: #72D6EE;
		border: 1px solid #2DA0FA;
		border-radius: 10px;
	}

	.contenedor1 select {
		width: 100%;
		padding: 5px;
		border: 1px solid #ccc;
		border-radius: 10px;
	}

	.container-botones {
		display: flex;
		width: 100%;
		justify-content: space-evenly;
	}

	.cancelar:hover {
		background-color: #da190b;
	}

	.boton-registrar,
	.boton-cancelar {
		padding: 0.7em 1.7em;
		font-size: 18px;
		border-radius: 0.5em;
		cursor: pointer;
		border: 1px solid #e8e8e8;
		transition: all 0.3s;
		box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
		color: white;
		font-weight: 700;
	}

	.boton-registrar {
		background: rgb(0, 255, 0, .7);

	}

	.boton-registrar:hover {
		border: 1px solid rgb(0, 255, 0);
		;
	}

	.boton-cancelar {
		background: rgb(244, 67, 54, .8);

	}

	.boton-cancelar:hover {
		border: 1px solid rgb(244, 67, 54);
		;
	}
</style>

<div id="test"></div>
<div class="pagina-content">
	<div class="contenedor">
		<form class="formulario" method="post" action="./guardar_momento.php" autocomplete="off">

			<div>
				<h3 style="text-align: center;">Nuevo Registro de moemento</h3>
			</div>


			<div class="input-contenedor">

				<div class="contenedor-input">
					<div class="contenedor-label">
						<i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
							</svg></i>
						<label class='texto' for="anio">Numero momento</label>
					</div>
					<input type="number" id="numero_momento" placeholder="Numero" name="numero_momento" pattern="[0-9]+" required>
				</div>

				<div class="contenedor-input">
					<div class="contenedor-label">
						<i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
							</svg></i>
						<label class='texto' for="anio">Mes inicio</label>
					</div>
					<input type="number" id="mes_inicio" placeholder="Numero" name="mes_inicio" pattern="[0-9]+" required>
				</div>

				<div class="contenedor-input">
					<div class="contenedor-label">
						<i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
							</svg></i>
						<label class='texto' for="anio">Dia inicio</label>
					</div>
					<input type="number" id="dia_inicio" placeholder="Numero" name="dia_inicio" pattern="[0-9]+" required>
				</div>

				<div class="contenedor-input">
					<div class="contenedor-label">
						<i class="icono"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path fill="currentColor" d="M360 73c-14.43 0-27.79 7.71-38.055 21.395c-10.263 13.684-16.943 33.2-16.943 54.94c0 21.74 6.68 41.252 16.943 54.936c10.264 13.686 23.625 21.396 38.055 21.396s27.79-7.71 38.055-21.395C408.318 190.588 415 171.075 415 149.335c0-21.74-6.682-41.255-16.945-54.94C387.79 80.71 374.43 73 360 73m-240 96c-10.012 0-19.372 5.32-26.74 15.145C85.892 193.968 81 208.15 81 224c0 15.85 4.892 30.032 12.26 39.855C100.628 273.68 109.988 279 120 279c10.012 0 19.374-5.32 26.742-15.145c7.368-9.823 12.256-24.006 12.256-39.855c0-15.85-4.888-30.032-12.256-39.855C139.374 174.32 130.012 169 120 169m188.805 47.674a77.568 77.568 0 0 0-4.737 3.974c-13.716 12.524-23.816 31.052-31.53 54.198c-14.59 43.765-20.404 103.306-30.063 164.154h235.05c-9.66-60.848-15.476-120.39-30.064-164.154c-7.714-23.146-17.812-41.674-31.528-54.198a76.795 76.795 0 0 0-4.737-3.974c-12.84 16.293-30.942 26.994-51.195 26.994s-38.355-10.7-51.195-26.994zM81.27 277.658c-.573.485-1.143.978-1.702 1.488c-9.883 9.024-17.315 22.554-23.03 39.7c-10.6 31.8-15.045 75.344-22.063 120.154h171.048c-7.017-44.81-11.462-88.354-22.062-120.154c-5.714-17.146-13.145-30.676-23.028-39.7a59.378 59.378 0 0 0-1.702-1.488C148.853 289.323 135.222 297 120 297c-15.222 0-28.852-7.678-38.73-19.342" />
							</svg></i>
						<label class='texto' for="anio">periodo</label>
					</div>
					<select id="genero" name="id_periodo" required>
						<option value="">Selecciona una opción</option>
						<?php while ($mostrar = $result->fetch_assoc()) { ?>
							<option value="<?= $mostrar['id'] ?>"><?= $mostrar['periodo'] ?></option>
						<?php } ?>
					</select>
				</div>

			</div>
			<div class="container-botones">
				<button class="boton-registrar" type="submit">Guardar</button>
				<!-- Cambiado a un enlace para regresar -->
				<a href="javascript:history.back()" class="boton-cancelar">Regresar</a>
			</div>
	</div>
	</form>
</div>