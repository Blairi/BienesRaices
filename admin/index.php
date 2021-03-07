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


    //Eliminar
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Propiedades
        $idPropiedad = $_POST['idPropiedad'];
        $idPropiedad = filter_var($idPropiedad, FILTER_VALIDATE_INT);

        // Entradas de blog
        $idBlog = $_POST['idBlog'];
        $idBlog = filter_var($idBlog, FILTER_VALIDATE_INT);


        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        //Si existe el input con nombre idPropiedad se ejecuta
        if($idPropiedad){
            //Eliminar la imagen
            $query = "SELECT imagen FROM propiedades WHERE id = ${idPropiedad}";

            $resultado = mysqli_query($db, $query);
            $propiedad = mysqli_fetch_assoc($resultado);
            
            // var_dump($query);

            //Eliminamos imagen
            unlink('../imagenes/' . $propiedad['imagen']);

            //Elimina la propiedad
            $query = "DELETE FROM propiedades WHERE id = ${idPropiedad}";

            $resultado = mysqli_query($db, $query);

            if($resultado){
                header('Location: /admin?resultado=3');
            }
        }

        //Si existe el input con nombre idBlog se ejecuta
        if($idBlog){
            //Eliminar la imagen
            $query = "SELECT imagen_entrada FROM blog WHERE id = ${idBlog}";

            $resultado = mysqli_query($db, $query);
            $entrada = mysqli_fetch_assoc($resultado);
            
            // var_dump($query);

            // var_dump($entrada['imagen_entrada']);

            //Eliminar Imagen
            unlink('../imagenesBlog/' . $entrada['imagen_entrada']);

            //Elimina la propiedad
            $query = "DELETE FROM blog WHERE id = ${idBlog}";

            $resultado = mysqli_query($db, $query);

            if($resultado){
                header('Location: /admin?resultado=6');
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
        <?php elseif(intval($resultado) === 6): ?>
            <p class="alerta exito">Entrada Eliminada Correctamente</p>
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
                    <div class="tabla">
                    	<div class="tabla-encabezados-propiedades">
                    		<div class="encabezado-propiedades">ID</div>
                    		<div class="encabezado-propiedades">Titulo</div>
                    		<div class="encabezado-propiedades">Imagen</div>
                    		<div class="encabezado-propiedades">Precio</div>
                    		<div class="encabezado-propiedades acciones">Acciones</div>
                    	</div>
                    	<div class="tabla-cuerpos-propiedades"><!-- Mostrar Los resultados -->
                            <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                            <div class="cuerpo-propiedad">    
                        		<div class="campo-propiedades"><?php echo $propiedad['id']; ?></div>
                        		<div class="campo-propiedades"><?php echo $propiedad['titulo']; ?></div>
                        		<div class="campo-propiedades"> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </div>
                        		<div class="campo-propiedades">$<?php echo $propiedad['precio']; ?></div>
                        		<div class="campo-propiedades-acciones">
                                    <form method="POST" class="w-100">

                                        <input type="hidden" name="idPropiedad" value="<?php echo $propiedad['id']; ?>">

                                        <input type="submit" name=""class="boton-rojo-block" value="Eliminar"></input>
                                    </form>
                        				
                        			<a href="propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block w-100">Actualizar</a>
                        		</div>
                            </div>
                        <?php endwhile; ?>
                    	</div>
                    </div>
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
                    <div class="tabla">
                        <div class="tabla-encabezados-entradas">
                            <div>ID</div>
                            <div>Titulo</div>
                            <div>Imagen</div>
                            <div>EscritorID</div>
                            <div class="acciones">Acciones</div>
                        </div>
                        <div class="tabla-cuerpos-entradas"><!-- Mostrar Los resultados -->
                            <?php while($entrada = mysqli_fetch_assoc($resultadoConsulta)): ?>
                            <div class="cuerpo-entrada">
                                <div class="campo-entrada"><?php echo $entrada['id']; ?></div class="">
                                <div class="campo-entrada"><?php echo $entrada['titulo_entrada']; ?></div class="">
                                <div class="campo-entrada"> <img src="/imagenesBlog/<?php echo $entrada['imagen_entrada']; ?>" class="imagen-tabla"> </div>



                                <div class="campo-entrada"><?php echo $entrada['escritorId']; ?></div>
                                <div class="campo-entradas-acciones">
                                    <form method="POST" class="w-100">

                                        <input type="hidden" name="idBlog" value="<?php echo $entrada['id']; ?>">

                                        <input type="submit" name=""class="boton-rojo-block" value="Eliminar"></input>
                                    </form>
                                    
                                    <a 
                                    href="blog/actualizar-entrada.php?id=<?php echo $entrada['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php

    //Cerrar la conexion
    mysqli_close($db);

   incluirTemplate('footer');


?>