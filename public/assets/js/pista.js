$(document).ready(function () {
    $("#botonpista").click(peticionPista);
});

function peticionPista(e) {}
;

function muestraPista(pista) {
    $("#pista").text(`La pista solicitada es: ${pista}`);
}