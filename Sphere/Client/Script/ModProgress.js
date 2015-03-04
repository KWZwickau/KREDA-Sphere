(function( $ )
{
    'use strict';
    $.fn.ModProgress = function( options )
    {
        var settings, pWidth;

        settings = $.extend( {
            'Total': 0,
            'Size': 0,
            'Speed': 0,
            'Time': 0
        }, options );

        if (1 > settings.Total) {
            settings.Total = 1;
        }

        pWidth = 100 / settings.Total * settings.Size;

        if (100 < pWidth) {
            pWidth = 100;
        }

        this.find( '.progress-bar' ).css( {"width": pWidth + '%'} );

        if (0 < settings.Total && settings.Total == settings.Size) {
            this.removeClass( 'active' );
            this.removeClass( 'progress-striped' );
            this.find( '.progress-bar' ).removeClass( 'progress-bar-warning' );
            this.find( '.progress-bar' ).addClass( 'progress-bar-success' )
        }

        return this;
    };

}( jQuery ));
