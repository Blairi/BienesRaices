<?php
    //Importar la conexion
    require 'includes/config/database.php';
    $db = conectarDB();

    //obtener variable superGLOBAL
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header('Location: /');
    }

    //construir query
    $query = "SELECT * FROM blog WHERE id = ${id}";

    //obtener los resultados
    $resultado = mysqli_query($db, $query);

    //Acceder al objeto para validar si la consulta existe
    if($resultado->num_rows === 0){
        header('Location: /');
    }

    //Obtener arreglo desde la base de datos
    $entrada = mysqli_fetch_assoc($resultado);
    
    require 'includes/funciones.php';

    // echo "<pre>";
    // var_dump($entrada);
    // echo "</pre>";

    incluirTemplate('header');

?>
    

    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $entrada['titulo_entrada']; ?></h1>
        <img loading="lazy" src="/imagenesBlog/<?php echo $entrada['imagen_entrada']; ?>" alt="<?php echo $entrada['titulo_entrada']; ?>">

        <p class="informacion-meta">Escrito el: <span><?php echo $entrada['fecha']; ?></span> por: <span><?php echo $entrada['escritorId']; ?></span> </p>


        <div class="resumen-propiedad aumentar-padding-x">
            <p><?php echo $entrada['entrada']; ?></p>
        </div>
        <a href="blog.php" class="boton-amarillo-block">Volver al Blog</a>
    </main>

<?php

   incluirTemplate('footer');


?>