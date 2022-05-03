<?php

/**
 * Añadiendo Scripts
*/

function pruebas_enqueue_script(){
    $myscript = plugins_url('js/myscript.js', __file__);
    wp_enqueue_script('myscript', $myscript);
}
add_action('init', 'pruebas_enqueue_script');

/**
 * pruebas con jQuery y AJAX Frontend
 */

function add_this_script_footer(){ ?>
    <form id="radioform">
    <table>
        <tbody>
        <tr>
            <td><input class="pref" checked="checked" name="book" type="button" value="1" />Sycamore Row</td>
            <td>John Grisham</td>
        </tr>
        </tbody>
    </table>
</form>
    <script>
    jQuery(document).ready(function($) {
        $(".pref").click(function(){
    

        // This is the variable we are passing via AJAX
        var fruit = 'Banana';
    
        // This does the ajax request (The Call).
        $.ajax({
            url: ajaxurl, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
            data: {
                'action':'example_ajax_request', // This is our PHP function below
                'fruit' : $('.pref').val() // This is the variable we are sending via AJAX
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
    </script>
    <?php } 
    
    add_action('in_admin_footer', 'add_this_script_footer'); 

    function example_ajax_request() {

        // The $_REQUEST contains all the data sent via AJAX from the Javascript call
        if ( isset($_REQUEST) ) {
    
            $fruit = $_REQUEST['fruit'];
    
            // This bit is going to process our fruit variable into an Apple
            if ( $fruit == 'Banana' ) {
                $fruit = 'Apple';
            }
    
            // Now let's return the result to the Javascript function (The Callback) 
            add_option('WPPP_opcion_de_pruebel', $fruit);
            echo 'meta creado';        
        }
    
        // Always die in functions echoing AJAX content
       die();
    }
    
    // This bit is a special action hook that works with the WordPress AJAX functionality. 
    add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' ); 