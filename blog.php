<?php
    
    require 'includes/funciones.php';
    incluirTemplate('header');

?>


    <main class="contenedor seccion">

        <h2>Nuestro Blog</h2>
        <?php

            $limite = 10;

            include 'includes/templates/blog.php';
        ?>
    </main>
<?php

   incluirTemplate('footer');


?>