jQuery( document ).ready( function() {
    jQuery( '.tc-tooltip' ).each( function( index ) {
        let $self = jQuery( this );

        let classes = $self.attr( 'class' ).split( /\s+/ );
        let data = '';

        for (let i = 0; i < classes.length; i++) {
            if (classes[i].includes( 'tc-tooltip-' )) {
                data = classes[i].split( 'tc-tooltip-' )[1];
                break;
            }
        }

        let $tooltip = jQuery( '.tc-tooltip-text[data-tooltip="' + data + '"]' );
        let $arrow = jQuery( '.tc-tooltip-arrow[data-tooltip="' + data + '"]' );

        if ($self.is('section')) {
            $tooltip.appendTo($self);
            $arrow.appendTo($self);
        }

        $self.hover(() => {
            $tooltip.css('visibility', 'visible');
            $arrow.css('visibility', 'visible');
        }, () => {
            $tooltip.css('visibility', 'hidden');
            $arrow.css('visibility', 'hidden');
        })

        let t_width = $tooltip.outerWidth();
        let t_height = $tooltip.outerHeight();

        let a_width = $arrow.outerWidth();
        let a_height = $arrow.outerHeight();

        let offset = 10;

        switch ( true ) {
            case $tooltip.hasClass( 'tc-tooltip-top' ):
                $tooltip.css( 'top', '-' + (t_height + offset) + 'px' );
                $tooltip.css( 'left', 'calc(50% - ' + (t_width / 2) + 'px)' );

                $arrow.css( 'top', '-' + offset + 'px' );
                $arrow.css( 'left', 'calc(50% - ' + (a_width / 2) + 'px)' );

                break;
            case $tooltip.hasClass( 'tc-tooltip-bottom' ):
                $tooltip.css( 'bottom', '-' + (t_height + offset) + 'px' );
                $tooltip.css( 'left', 'calc(50% - ' + (t_width / 2) + 'px)' );

                $arrow.css( 'bottom', '-' + offset + 'px');
                $arrow.css( 'left', 'calc(50% - ' + (a_width / 2) + 'px)' );
                break;
            case $tooltip.hasClass( 'tc-tooltip-right' ):
                $tooltip.css( 'bottom', 'calc(50% - ' + (t_height / 2) + 'px)' );
                $tooltip.css( 'right', '-' + (t_width  + offset) + 'px');

                $arrow.css( 'bottom', 'calc(50% - ' + (a_height / 2) + 'px)' );
                $arrow.css( 'right', '-' + offset + 'px');
                break;
            case $tooltip.hasClass( 'tc-tooltip-left' ):
                $tooltip.css( 'bottom', 'calc(50% - ' + (t_height / 2) + 'px)' );
                $tooltip.css( 'left', '-' + (t_width  + offset) + 'px');

                $arrow.css( 'bottom', 'calc(50% - ' + (a_height / 2) + 'px)' );
                $arrow.css( 'left', '-' + offset + 'px' );
                break;
        }
    } )
} )