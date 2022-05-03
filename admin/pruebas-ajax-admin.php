<?php

/**
 * AJAX handler using JSON
 */
function my_ajax_handler__json() {
    check_ajax_referer( 'title_example' );
    update_user_meta( get_current_user_id(), 'title_preference', sanitize_post_title( $_POST['title'] ) );
    $args      = array(
        'tag' => $_POST['title'],
    );
    $the_query = new WP_Query( $args );
    wp_send_json( esc_html( $_POST['title'] ) . ' (' . $the_query->post_count . ') ' );
}