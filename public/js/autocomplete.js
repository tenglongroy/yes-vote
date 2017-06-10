$(function() {



    /*$( "#search_input" ).autocomplete({
        source: function( request, response ) {
            $.ajax( {
                url: {{ URL::route('/ajax/search') }},
                dataType: "jsonp",
                data: {
                    term: request.term
                },
                success: function( data ) {
                    response( data );
                }
            } );
        },
        autoFocus:true,
        minLength: 2,
        select: function( event, ui ) {
            log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    } );*/

    /*$("#topic_title").autocomplete({
     source: "/path/to/ajax_autocomplete.php",
     minLength: 2,
     select: function(event, ui) {
     var url = ui.item.id;
     if(url != '#') {
     location.href = '/blog/' + url;
     }
     },

     html: true, // optional (jquery.ui.autocomplete.html.js required)

     // optional (if other layers overlap autocomplete list)
     open: function(event, ui) {
     $(".ui-autocomplete").css("z-index", 1000);
     }
     });*/
});