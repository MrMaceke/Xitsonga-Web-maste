/*
    Ajax Upload By Qassim Hassan, wp-time.com
*/

$( function() {

    $("#my-form").on('change', function (e) { // if change form value

        $("#result").html('<img src="http://qass.im/drag-and-drop-upload-on-change/ajax-loader.GIF">'); // display image loader in #result element

        var eventType = $(this).attr("method"); // get method type for #my-form

        var eventLink = $(this).attr("action"); // get action link for #my-form

        $.ajax({

            type: eventType,

            url: eventLink,

            data: new FormData( this ), // IMPORTANT!

            cache: false,

            contentType: false,

            processData: false,

            success: function(getResult) {

                $('#my-form')[0].reset(); // reset form

                $("#result").html(getResult); // display the result in #result element
                        
            }

        });

        e.preventDefault();

    });

});