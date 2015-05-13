(function( $ )
{
    'use strict';
    $.fn.ModAlways = function()
    {
        $( document ).ready( function()
        {
            $( 'form' ).attr( 'autocomplete', 'off' );
            $( 'input[type="password"]' ).attr( 'autocomplete', 'off' );
            $( 'input[type="text"]' ).attr( 'autocomplete', 'off' );
            $( 'input[type="number"]' ).attr( 'autocomplete', 'off' );
        } );
        return this;

    };

}( jQuery ));
