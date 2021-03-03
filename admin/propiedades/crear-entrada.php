<?php
    
    require '../../includes/funciones.php';

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
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";

		//Asignamos a la variable su valor
		$titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
		$entrada = mysqli_real_escape_string( $db, $_POST['entrada']);
		$escritorId = mysqli_real_escape_string( $db, $_POST['escritor']);

		//Asignamos la fecha de creacion del blog
		$creado = date('Y/m/d');

		//Validamos el formuario
		if(!$titulo){
			$errores[] = "Debes añadir un Titulo";
		}

		if(!$entrada){
			$errores[] = "Debes añadir una Entrada";
		}
		
		if(!$escritorId){
			$errores[] = "Debes seleccionar un escritor";
		}

		//Revisa si el arreglo de errores esta vacio
		if(empty($errores)){
			//Insertar en la base de datos

			//Creamos query
			$query = "INSERT INTO blog (";
			$query .= "titulo_entrada, entrada, fecha, escritorId";
			$query .= ") VALUES (";
			$query .= "'$titulo', '$entrada', '$creado', '$escritorId')";

			// echo $query;

			//Insertamos el query
			$resultado = mysqli_query($db, $query);

			echo $resultado;
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
        <form action="/admin/propiedades/crear-entrada.php" class="formulario" method="POST" enctype="multipart/form-data">
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

        			<option value="1">Blairi Blitz</option>
        		</select>
        	</fieldset>

        	<input type="submit" value="Crear Entrada" class="boton boton-verde">
        </form>
    </main>

<?php

   incluirTemplate('footer');


?>