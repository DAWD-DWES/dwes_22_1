/* Definición del manejador de eventos */
$(document).ready(function () {
    $("#botonpista").click(peticionPista);
});

/* Función a rellenar para iniciar el proceso AJAX de petición de pista */
function peticionPista(e) {}
;

/* Función de ayuda para mostrar la pista en el lugar apropiado de la página */
function muestraPista(pista) {
    $("#pista").text(`La pista solicitada es: ${pista}`);
}