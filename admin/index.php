<?php

    require '../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

    //Importar conexion
    require '../includes/config/database.php';
    $db = conectarDB();

    //Escribir query
    $query = "SELECT * FROM propiedades";

    //Consultar db
    $resultadoConsulta = mysqli_query($db, $query);

    //Muestra  mensaje condicional
    $resultado = $_GET['resultado'] ?? null;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id){
            //Eliminar el archivo
            $query = "SELECT imagen FROM propiedades WHERE id = ${id}";

            $resultado = mysqli_query($db, $query);
            $propiedad = mysqli_fetch_assoc($resultado);
            
            unlink('../imagenes/' . $propiedad['imagen']);

            //Elimina la propiedad
            $query = "DELETE FROM propiedades WHERE id = ${id}";

            $resultado = mysqli_query($db, $query);

            if($resultado){
                header('Location: /admin?resultado=3');
            }
        }
    }

    //Incluye un template
    incluirTemplate('header');

?>

    <!-- Crear alerta segun la URL -->
    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if(intval($resultado) === 1):?>
        	<p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif(intval($resultado) === 2): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php elseif(intval($resultado) === 3): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php elseif(intval($resultado) === 4): ?>
            <p class="alerta exito">Entrada Creada Correctamente</p>
        <?php elseif(intval($resultado) === 5): ?>
            <p class="alerta exito">Entrada Actualizada Correctamente</p>
        <?php endif; ?>
        

        <!-- TABS -->
        <div class="tabs">
            <div class="tabs-controles">
                <button class="boton-blanco activo">Propiedades</button>
                <button class="boton-blanco">Blog</button>
            </div>
            <div class="contenedor-tabs">
                <div class="contenido-tabs">
                    <h3>Propiedades</h3>
                    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
                    <table class="propiedades">
                    	<thead>
                    		<tr>
                    			<th>ID</th>
                    			<th>Titulo</th>
                    			<th>Imagen</th>
                    			<th>Precio</th>
                    			<th>Acciones</th>
                    		</tr>
                    	</thead>
                    	<tbody><!-- Mostrar Los resultados -->
                            <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                    		<tr>
                    			<td><?php echo $propiedad['id']; ?></td>
                    			<td><?php echo $propiedad['titulo']; ?></td>
                    			<td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </td>
                    			<td>$<?php echo $propiedad['precio']; ?></td>
                    			<td>
                                    <form method="POST" class="w-100">

                                        <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">

                                        <input type="submit" name=""class="boton-rojo-block" value="Eliminar"></input>
                                    </form>
                    				
                    				<a 
                                    href="propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    			</td>
                    		</tr>
                        <?php endwhile; ?>
                    	</tbody>
                    </table>
                </div>


                <?php

                //Modificamos el query para la otra consulta
                $query = "SELECT * FROM blog";

                //Consultar db
                $resultadoConsulta = mysqli_query($db, $query);

                // echo "<pre>";
                // var_dump(mysqli_fetch_assoc($resultadoConsulta));

                // echo "</pre>";

                ?>

                <div class="contenido-tabs">
                    <h2>Blog</h2>
                    <a href="/admin/blog/crear-entrada.php" class="boton boton-verde">Nueva Entrada de Blog</a>
                    <table class="entradas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titulo</th>
                                <th>Imagen</th>
                                <th>Entrada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody><!-- Mostrar Los resultados -->
                            <?php while($entrada = mysqli_fetch_assoc($resultadoConsulta)): ?>
                            <tr>
                                <td><?php echo $entrada['id']; ?></td>
                                <td><?php echo $entrada['titulo_entrada']; ?></td>
                                <td> <img src="/imagenesBlog/<?php echo $entrada['imagen_entrada']; ?>" class="imagen-tabla"> </td>
                                <td class="descripcion-larga"><?php echo $entrada['entrada']; ?></td>
                                <td>
                                    <form method="POST" class="w-100">

                                        <input type="hidden" name="id" value="<?php echo $entrada['id']; ?>">

                                        <input type="submit" name=""class="boton-rojo-block" value="Eliminar"></input>
                                    </form>
                                    
                                    <a 
                                    href="blog/actualizar-entrada.php?id=<?php echo $entrada['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

<?php

    //Cerrar la conexion
    mysqli_close($db);

   incluirTemplate('footer');


?>