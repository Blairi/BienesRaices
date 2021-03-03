<?php
    
    require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

    
    //Conectar base de datos
    require '../../includes/config/database.php';
	$db = conectarDB();

	// echo "<pre>";
	// var_dump($db);
	// echo "</pre>";

	//Consultar para obtener vendedores
	$consulta = "SELECT * FROM escritores";
	$resultado = mysqli_query($db, $consulta);


	//Arreglo con mensajes de error
	$errores = [];

	$titulo = '';
	$entrada = '';
	$escritorId = '';

	//Ejecutar el código despues de que el usuario mande el formulario
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";

		//Asignamos a la variable su valor
		$titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
		$entrada = mysqli_real_escape_string( $db, $_POST['entrada']);
		$escritorId = mysqli_real_escape_string( $db, $_POST['escritor']);

		//Asignar imagen hacian una variable em LA SUPER GLOBAL FILES
		$imagen = $_FILES['imagen'];

		// echo "<pre>";
		// var_dump($imagen['name']);
		// echo "</pre>";

		//Asignamos la fecha de creacion del blog
		$creado = date('Y/m/d');

		//Validamos el formuario
		if(!$titulo){
			$errores[] = "Debes añadir un Titulo";
		}

		if(strlen($entrada) < 50){
			$errores[] = "Debes añadir una Entrada mayor a los 50 caracteres";
		}
		
		if(!$escritorId){
			$errores[] = "Debes seleccionar un escritor";
		}


		//Validar imagen
		if(!$imagen['name'] || $imagen['error']){
			$errores[] = "No has subido ninguna imagen";
		}

		//Validar por tamaño (1mb máximo)
		$medida = 1000 * 1000;

		if($imagen['size'] > $medida){
			$errores[] = "Selecciona una imagen más ligera";
		}

		//Revisa si el arreglo de errores esta vacio
		if(empty($errores)){
			//Subida de archivos

			//Crear carpeta
			$carpetaImagenesBlog = '../../imagenesBlog/';

			if(!is_dir($carpetaImagenesBlog)){
				mkdir($carpetaImagenesBlog);
			}

			//Generar nombre unico
			$nombreImagen = md5(uniqid(rand(), true)) . ".jpg";


			//subirImagen
			move_uploaded_file($imagen['tmp_name'], $carpetaImagenesBlog . $nombreImagen);


			//Insertar en la base de datos

			//Creamos query
			$query = "INSERT INTO blog (";
			$query .= "titulo_entrada, entrada, imagen_entrada, fecha, escritorId";
			$query .= ") VALUES (";
			$query .= "'$titulo', '$entrada', '$nombreImagen', '$creado', '$escritorId')";

			echo $query;
			//Insertamos el query
			$resultado = mysqli_query($db, $query);

			echo $resultado;

			if($resultado){
				//Redireccionar al usuario
				header('Location: /admin?resultado=4');
			}
		}
	}


	incluirTemplate('header');
?>


    <main class="contenedor seccion">
        <h1>Crear Entrada</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <!-- Imprimir errores -->
        <?php foreach($errores as $error):?>
        	<div class="alerta error">
        		<?php echo $error; ?>
        	</div>
    	<?php endforeach; ?>
        <form action="/admin/blog/crear-entrada.php" class="formulario" method="POST" enctype="multipart/form-data">
        	<fieldset>
        		<legend>General</legend>

        		<label for="titulo">Titulo:</label>
        		<input name="titulo" type="text" id="titulo" placeholder="Titulo Entrada" value="<?php echo $titulo; ?>">

        		<label for="imagen">Imagen:</label>
        		<input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        		<label for="entrada">Entrada:</label>
        		<textarea name="entrada" id="entrada"><?php echo $entrada; ?></textarea>

        		<label for="escritor">Escritor</label>
        		<select name="escritor" id="escritor">
        			<option value="">--Selecciona--</option>
        			<!-- Creamos los options con el while -->
        			<?php while($escritor = mysqli_fetch_assoc($resultado)): ?>

        				<option <?php echo $escritorId === $escritor['id'] ? 'selected' : '';?> value="<?php echo $escritor['id']; ?>"><?php echo $escritor['nombre'] . " " .$escritor['apellido'];  ?></option>

        			<?php endwhile ?>
        		</select>
        	</fieldset>

        	<input type="submit" value="Crear Entrada" class="boton boton-verde">
        </form>
    </main>

<?php

   incluirTemplate('footer');


?>