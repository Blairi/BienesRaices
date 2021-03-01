<?php
	
	//Validar URL por el id
	$id = $_GET['id'];
	$id = filter_var($id, FILTER_VALIDATE_INT);

	if(!$id){
		header('Location: /admin');
	}

	//Base de datos
	require '../../includes/config/database.php';

	$db = conectarDB();

	//obtener datos de propiedad
	$consulta = "SELECT * FROM propiedades WHERE id = ${id}";
	$resultado = mysqli_query($db, $consulta);
	$propiedad = mysqli_fetch_assoc($resultado);

	// echo "<pre>";
	// var_dump($propiedad);
	// echo "</pre>";

	//Consultar para obtener vendedores
	$consulta = "SELECT * FROM vendedores";
	$resultado = mysqli_query($db, $consulta);

	//Arreglo con mensajes de error
	$errores = [];

	$titulo = $propiedad['titulo'];
	$precio = $propiedad['precio'];
	$descripcion = $propiedad['descripcion'];
	$habitaciones = $propiedad['habitaciones'];
	$wc = $propiedad['wc'];
	$estacionamiento = $propiedad['estacionamiento'];
	$vendedorId = $propiedad['vendedorId'];
	$imagenPropiedad = $propiedad['imagen'];
	
	//Ejecutar el código despues de que el usuario mande el formulario
	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";

		// echo "<pre>";
		// var_dump($_FILES);
		// echo "</pre>";

		$titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
		$precio = mysqli_real_escape_string( $db, $_POST['precio']);
		$descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
		$habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones']);
		$wc = mysqli_real_escape_string( $db, $_POST['wc']);
		$estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento']);
		$vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor']);
		$creado = date('Y/m/d');

		//Asignar files hacian una variable
		$imagen = $_FILES['imagen'];

		// echo "<pre>";
		// var_dump($imagen['name']);
		// echo "</pre>";


		if(!$titulo){
			$errores[] = "Debes añadir un Titulo";
		}

		if(!$precio){
			$errores[] = "Debes añadir un Precio";
		}

		if(strlen($descripcion) < 50){
			$errores[] = "La descripcion es obligatoria y debe tener al menos 50 caracteres";
		}

		if(!$habitaciones){
			$errores[] = "Debes añadir el numero de las habitaciones";
		}

		if(!$wc){
			$errores[] = "Debes añadir el numero de los wc";
		}

		if(!$estacionamiento){
			$errores[] = "Debes añadir el numero de estacionamientos";
		}

		if(!$vendedorId){
			$errores[] = "Elige un vendedor";
		}

		//Validar por tamaño (1mb máximo)
		$medida = 1000 * 1000;

		if($imagen['size'] > $medida){
			$errores[] = "Selecciona una imagen más ligera";
		}



		// echo "<pre>";
		// var_dump($errores);
		// echo "</pre>";

		//Revisar el arreglo de errores este vacio
		if(empty($errores)){
			//Subida de archivos
			//Crear carpeta
			$carpetaImagenes = '../../imagenes/';

			if(!is_dir($carpetaImagenes)){
				mkdir($carpetaImagenes);
			}

			$nombreImagen = '';

			if($imagen['name']){
				//Eliminar la imagen previa
				unlink($carpetaImagenes . $propiedad['imagen']);

				//Generar nombre unico
				$nombreImagen = md5(uniqid(rand(), true)) . ".jpg";


				//subirImagen
				move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
			}else{
				$nombreImagen = $propiedad['imagen'];
			}

			

			



			//Insertar en la base de datos
			$query = " UPDATE propiedades SET titulo = '${titulo}', precio = ${precio},imagen = '${nombreImagen}',descripcion = '${descripcion}',habitaciones = ${habitaciones},wc = ${wc},estacionamiento = ${estacionamiento}, vendedorId = ${vendedorId} WHERE id = ${id} ";
			// echo $query;


			$resultado = mysqli_query($db, $query);

			// echo $resultado;

			if($resultado){
				//Redireccionar al usuario
				header('Location: /admin?resultado=2');
			}
		}	
	}
    
    require '../../includes/funciones.php';
    incluirTemplate('header');

?>


    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error){ ?>
        	<div class="alerta error">
        		<?php echo $error; ?>
        	</div>
    	<?php } ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
        	<fieldset>
        		<legend>Información General</legend>

        		<label for="titulo">Titulo:</label>
        		<input name="titulo" type="text" id="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo ?>">

        		<label for="precio">Precio:</label>
        		<input name="precio" type="number" id="precio" placeholder="Precio Propiedad" value="<?php echo $precio ?>">

        		<label for="imagen">Imagen:</label>
        		<input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        		<img src="/imagenes/<?php echo $imagenPropiedad; ?>" class = "imagen-small">

        		<label for="descripcion">Descripción:</label>
        		<textarea name="descripcion" id="descripcion"><?php echo $descripcion ?></textarea>
        	</fieldset>

        	<fieldset>
        		<legend>Información Propiedad</legend>

        		<label for="habitaciones">Habitaciones</label>
        		<input name="habitaciones" type="number" id="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones ?>">

        		<label for="wc">Baños</label>
        		<input name="wc" type="number" id="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc ?>">

        		<label for="estacionamiento">Estacionamiento</label>
        		<input name="estacionamiento" type="number" id="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento ?>">
        	</fieldset>

        	<fieldset>
        		<legend>Vendedor</legend>

        		<select name="vendedor">
        			<option value="">--Selecciona--</option>
        			<?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>

        				<option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : '';?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . " " .$vendedor['apellido'];  ?></option>

        			<?php endwhile ?>
        		</select>
        	</fieldset>

        	<input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>

<?php

   incluirTemplate('footer');


?>