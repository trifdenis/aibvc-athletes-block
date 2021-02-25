/**
 * Use GridJS to manipulate the athletes on the Front-End.
 */

var $  = require('jquery');

$(document).ready(function() {

    let athletesWrapper = $(".aibvc-athletes-wrapper");
    let athletesData = JSON.parse( athletesWrapper.attr( 'athletes-data' ) );

    new gridjs.Grid( { 
        columns: [ '#', 'Nome', 'Cognome', 'Punteggio' ],
        data: athletesData,
        search: {
            enabled: true,
        },
        pagination: {
            enabled: true,
        }
     } ).render(athletesWrapper.get(0));

});

