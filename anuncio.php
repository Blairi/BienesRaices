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
    $query = "SELECT * FROM propiedades WHERE id = ${id}";

    //obtener los resultados
    $resultado = mysqli_query($db, $query);

    //Acceder al objeto para validar si la consulta existe
    if($resultado->num_rows === 0){
        header('Location: /');
    }

    //Obtener arreglo desde la base de datos
    $propiedad = mysqli_fetch_assoc($resultado);

    require 'includes/funciones.php';
    incluirTemplate('header');

?>
    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad['titulo'] ?></h1>
        <img loading="lazy" src="imagenes/<?php echo $propiedad['imagen'] ?>" alt="<?php echo $propiedad['titulo'] ?>">

        <div class="resumen-propiedad">
            <p class="precio"><?php echo $propiedad['precio'] ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad['wc'] ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad['estacionamiento'] ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propiedad['habitaciones'] ?></p>
                </li>
            </ul>

            <p><?php echo $propiedad['descripcion'] ?></p>
        </div>
    </main>
<?php

   incluirTemplate('footer');
    //Cerra la conexion
    mysqli_close($db);

?>