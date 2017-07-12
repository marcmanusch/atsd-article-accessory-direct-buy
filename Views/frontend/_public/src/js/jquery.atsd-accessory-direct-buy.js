;(function($) {

    // our plugin
    $.plugin( "atsdAccessoryDirectBuy", {

        // selectors
        selector:
            {
                accessories: "#sAddAccessories",
                quantity:    "#sAddAccessoriesQuantity"
            },



        // on initialization
        init: function ()
        {
            // get this
            var me = this;

            // bind all events
            me.bindEvents();
        },



        // bind all events
        bindEvents: function ()
        {
            // get this
            var me = this;

            // bind the click event
            me._on( me.$el.find( ".atsd-accessory--container .article--checkbox" ), 'change', $.proxy( me.updateAccessories, me ) );
            me._on( me.$el.find( ".atsd-accessory--container .article--radio" ), 'change', $.proxy( me.updateAccessories, me ) );
        },



        // ...
        updateAccessories: function ( event )
        {
            // get this
            var me = this;

            // clear current inputs
            $( me.selector.accessories ).val( "" );
            $( me.selector.quantity ).val( "" );

            // loop all checked input fields
            $( ".atsd-accessory--container input:checked" ).each( function( key, value )
                {
                    // get current value
                    var accessories = $( me.selector.accessories ).val();

                    // not empty set?
                    if ( accessories != "" )
                        // append semicolon first
                        accessories += ";";

                    // append current number
                    accessories += $( value ).val();

                    // set the value back
                    $( me.selector.accessories ).val( accessories );



                    // get id
                    var id = value.id;

                    // quantity key if radio or checkbox
                    var quantity_key = ( id.indexOf( "radio" ) > -1 )
                        ? id.replace( "radio_", "quantity_" ) + "_" + $( value ).val()
                        : id.replace( "checkbox_", "quantity_" );

                    // get the quantity
                    var quantity = $( "#" + quantity_key ).val();



                    // get current value
                    var quantities = $( me.selector.quantity ).val();

                    // not empty set?
                    if ( quantities != "" )
                        // append semicolon first
                        quantities += ";";

                    // append current number
                    quantities += quantity;

                    // set the value back
                    $( me.selector.quantity ).val( quantities );
                }
            );
        },



        // on destroy
        destroy: function()
        {
            // get this
            var me = this;

            // call the parent
            me._destroy();
        }

    });



    // wait till the document is ready
    document.asyncReady( function() {
        // call our plugin
        $( "body" ).atsdAccessoryDirectBuy();
    });

})(jQuery);
