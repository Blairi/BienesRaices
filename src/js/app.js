document.addEventListener('DOMContentLoaded', function() {

    eventListeners();

    darkMode();

    //Funcion para añadir ... en cada descripcion
    descripcion();

    //Tabs
    tabs();
});

function tabs(){
    tabs = document.querySelectorAll('.tabs-controles button');

    //Iteramos sobre el arreglo tabs para acceder a cada uno de ellos
    tabs.forEach(boton=>{
       //Agregamos un eventListener por cada boton
       boton.addEventListener('click', () =>{
        //Accedemos al padre de los botones tabs
        const parent = boton.parentNode;
        //Accedemos al padre del padre de los botones tabs
        const grandParent = parent.parentNode;
        //Accedemos al contenedor de los tabs
        const contenedorTabs = grandParent.querySelector(".contenedor-tabs");

        //Convertimos el HTMLCollection a un arreglo para poder aplicar metodos de arreglo
        const childrenList = Array.from(parent.children);

        //Guardamos la posicion de los botones tabs segun su posicion
        const index = childrenList.indexOf(boton);

        //Editamos los estilos para agregar una animacion
        contenedorTabs.style.transform = `translate(-${index * 100}%)`;
        
        //Removemos la clase activo al presionar otro boton
        const tabActuales = parent.querySelectorAll('.activo');

        //Eliminamos la clase de todos los botones que la tengan
        tabActuales.forEach(tabActual => tabActual.classList.remove('activo'));

        //Agregamos clase
        boton.classList.add('activo');

       }); 
    });
}

function descripcion(){

    //Seleccionamos los elementos que queremos aplicar el codigo
    const descripcion = document.querySelectorAll('.descripcion-larga');
    
    //Ponemos el limite de caracteres que deseamos 
    const limite = 220;

    let descripciones = [];

    let arrayLetras = [];

    //Iteramos sobre el arreglo de descripcion para aplicarle el codigo a todos
    for(let i = 0; i < descripcion.length; i++){
        //reiniciamos el arrayLetras por cada iteracion
        arrayLetras = [];

        // console.log(descripcion[i].textContent);

        //Guardamos el texto de las descripciones en un arreglo
        descripciones.push(descripcion[i].textContent);
        // console.log(descripciones[i].charAt(0));

        //Itera para guardar en el arreglo el numero de letras que hayamos puesto en el limite
        for(let o = 0; o < limite; o++){
            //Guarda las letras en el arreglo
            arrayLetras.push(descripciones[i].charAt(o));
            
        }

        //Valida el numero de letras
        if(descripciones[i].length >= limite){
            //Pinta las letras que hayamos guardado en el arreglo de letras
            for(let u = 0; u < limite; u++){

                //Inyecta las letras
                descripcion[i].innerHTML = arrayLetras.join('') + '...';
                //El join lo usamos para eliminar las comas al pintar el arreglo
            }
        }

        // console.log(arrayLetras);
        
    }

}

function darkMode() {

    const prefiereDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

    // console.log(prefiereDarkMode.matches);

    if(prefiereDarkMode.matches) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }

    prefiereDarkMode.addEventListener('change', function() {
        if(prefiereDarkMode.matches) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    });

    const botonDarkMode = document.querySelector('.dark-mode-boton');
    botonDarkMode.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
    });
}

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenu.addEventListener('click', navegacionResponsive);


}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');

    navegacion.classList.toggle('mostrar')
}