<?php
    //Importar la conexion
    
    $archivo = basename($_SERVER['PHP_SELF']);
    $pagina = str_replace('.php', '', $archivo);

    if($pagina == 'blog'){
        require __DIR__ . '/../config/database.php';
        $db = conectarDB();
    }
    

    //consultar
    $query = "SELECT * FROM blog LIMIT ${limite}";

    //obtener los resultados
    $resultado = mysqli_query($db, $query);

?>

<div class="contenedor-blog">
    <?php while($entrada = mysqli_fetch_assoc($resultado)): ?>
        <article class="entrada-blog">
                <div class="imagen">
                    <img loading="lazy" src="/imagenesBlog/<?php echo $entrada['imagen_entrada']; ?>" alt="Texto Entrada Blog">
                </div>

                <div class="texto-entrada">
                    <a href="entrada.php?id=<?php echo $entrada['id']; ?>">
                        <h4><?php echo $entrada['titulo_entrada']; ?></h4>
                        <p class="informacion-meta">Escrito el: <span><?php echo $entrada['fecha']; ?></span> por: <span><?php echo $entrada['escritorId']; ?></span> </p>
                        <p class="descripcion-larga"><?php echo $entrada['entrada']; ?></p>
                    </a>
                </div>
            </article>
    <?php endwhile; ?>
</div> <!--.contenedor-blog-->

<?php

    //Cerra la conexion
    mysqli_close($db);
?>