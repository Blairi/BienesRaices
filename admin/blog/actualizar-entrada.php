<?php
    
    require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

    //Validar URL por el id
	$id = $_GET['id'];
	$id = filter_var($id, FILTER_VALIDATE_INT);

	if(!$id){
		header('Location: /admin');
	}

    
    //Conectar base de datos
    require '../../includes/config/database.php';
	$db = conectarDB();

	// echo "<pre>";
	// var_dump($db);
	// echo "</pre>";

	//obtener datos de propiedad al entrar
	$consulta = "SELECT * FROM blog WHERE id = ${id}";
	$resultado = mysqli_query($db, $consulta);
	$entrada = mysqli_fetch_assoc($resultado);

	//Consultar para obtener escritores
	$consulta = "SELECT * FROM escritores";
	$resultado = mysqli_query($db, $consulta);


	// echo "<pre>";
	// var_dump($entrada);
	// echo "</pre>";

	//Arreglo con mensajes de error
	$errores = [];

	$titulo = $entrada['titulo_entrada'];
	$entradaBlog = $entrada['entrada'];
	$escritorId = $entrada['escritorId'];
	$imagenEntrada = $entrada['imagen_entrada'];

	//Ejecutar el código despues de que el usuario mande el formulario
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";

		//Asignamos a la variable su valor
		$titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
		$entradaBlog = mysqli_real_escape_string( $db, $_POST['entrada']);
		$escritorId = mysqli_real_escape_string( $db, $_POST['escritor']);

		//Actualizamos la fecha
		$creado = date('Y/m/d');

		//Asignar imagen hacian una variable em LA SUPER GLOBAL FILES
		$imagen = $_FILES['imagen'];

		echo "<pre>";
		var_dump($imagen);
		echo "</pre>";

		//Asignamos la fecha de creacion del blog
		$creado = date('Y/m/d');

		//Validamos el formuario
		if(!$titulo){
			$errores[] = "Debes añadir un Titulo";
		}

		if(strlen($entradaBlog) < 50){
			$errores[] = "Debes añadir una Entrada mayor a los 50 caracteres";
		}
		
		if(!$escritorId){
			$errores[] = "Debes seleccionar un escritor";
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

			//nombre
			$nombreImagen = '';

			// echo $entrada['imagen_entrada'];

			//Si se cambia la imagen actualizamos el nombre
			if($imagen['name']){
				//Eliminar la imagen previa
				unlink($carpetaImagenesBlog . $entrada['imagen_entrada']);

				//Generar nombre unico a la nueva imagen
				$nombreImagen = md5(uniqid(rand(), true)) . ".jpg";


				//subirImagen
				move_uploaded_file($imagen['tmp_name'], $carpetaImagenesBlog . $nombreImagen);
			}else{
				//En caso de no hacerlo se conserva la imagen
				$nombreImagen = $entrada['imagen_entrada'];
			}



			//Insertar en la base de datos

			//Creamos query
			$query = "UPDATE blog SET titulo_entrada='$titulo',entrada='$entradaBlog',imagen_entrada='$nombreImagen',fecha='$creado',escritorId='$escritorId' WHERE id = ${id}";

			// echo $query;

			//Insertamos el query
			$resultado = mysqli_query($db, $query);
			// echo $resultado;

			if($resultado){
				//Redireccionar al usuario
				header('Location: /admin?resultado=5');
			}
		}
	}


	incluirTemplate('header');
?>


    <main class="contenedor seccion">
        <h1>Actualizar Entrada</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <!-- Imprimir errores -->
        <?php foreach($errores as $error):?>
        	<div class="alerta error">
        		<?php echo $error; ?>
        	</div>
    	<?php endforeach; ?>
        <form class="formulario" method="POST" enctype="multipart/form-data">
        	<fieldset>
        		<legend>General</legend>

        		<label for="titulo">Titulo:</label>
        		<input name="titulo" type="text" id="titulo" placeholder="Titulo Entrada" value="<?php echo $titulo; ?>">

        		<label for="imagen">Imagen:</label>
        		<input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        		<img src="/imagenesBlog/<?php echo $imagenEntrada; ?>" class = "imagen-small">

        		<label for="entrada">Entrada:</label>
        		<textarea name="entrada" id="entrada"><?php echo $entradaBlog; ?></textarea>

        		<label for="escritor">Escritor</label>
        		<select name="escritor" id="escritor">
        			<option value="">--Selecciona--</option>
        			<!-- Creamos los options con el while -->
        			<?php while($escritor = mysqli_fetch_assoc($resultado)): ?>

        				<option <?php echo $escritorId === $escritor['id'] ? 'selected' : '';?> value="<?php echo $escritor['id']; ?>"><?php echo $escritor['nombre'] . " " .$escritor['apellido'];  ?></option>

        			<?php endwhile ?>
        		</select>
        	</fieldset>

        	<input type="submit" value="Actualizar Entrada" class="boton boton-verde">
        </form>
    </main>

<?php

   incluirTemplate('footer');


?>