
jQuery(document).ready(function($) {          //wrapper
    $(".pref").change(function() {             //event
        $.ajax({
            url: ajaxurl, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
            data: {
                'action':'example_ajax_request', // This is our PHP function below
                'fruit' : fruit // This is the variable we are sending via AJAX
            },
            success:function(data) {
        // This outputs the result of the ajax request (The Callback)
                window.alert(data);
            },  
            error: function(errorThrown){
                window.alert(errorThrown);
            }
        });   
    });
    });