<?php
/**
 * Plugin Name:       pruebas
 * Description:       haciendo pruebas.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pablo Reyes
 * License:           GPL v2 or later
 */

/**
 * Register the "book" custom post type
 */

function pluginprefix_setup_post_type() {
    register_post_type( 'book', ['public' => true ] ); 
} 
add_action( 'init', 'pluginprefix_setup_post_type' );
 
 
/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
    // Trigger our function that registers the custom post type plugin.
    pluginprefix_setup_post_type(); 
    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'pluginprefix_activate' );


/**
 * Deactivation hook.
 */
function pluginprefix_deactivate() {
    // Unregister the post type, so the rules are no longer in memory.
    unregister_post_type( 'book' );
    // Clear the permalinks to remove our post type's rules from the database.
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );

/**
 * Iniciando plugin - usuarop Admin o Public?
 */

if ( is_admin() ) {
    // we are in admin mode
    require_once __DIR__ . '/admin/plugin-pruebas-admin.php';
}else{
    // Estamos en modo publico
    require_once __DIR__ . '/public/plugin-pruebas-public.php';
}
