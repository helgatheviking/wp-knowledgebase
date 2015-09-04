<?php
/**
 * Knowledgebase Settings
 *
 * @version     1.1.0
 * @author      Enigma Plugins
 */

/**
 * Add plugin menus
 * @since  1.0.0
 */
add_action( 'admin_menu', 'kbe_plugin_menu' );
function kbe_plugin_menu() {
    add_submenu_page( 'edit.php?post_type=kbe_knowledgebase', 'Order', 'Order', 'manage_options', 'kbe_order', 'wp_kbe_order' );
    add_submenu_page( 'edit.php?post_type=kbe_knowledgebase', 'Settings', 'Settings', 'manage_options', 'kbe_options', 'wp_kbe_options' );
}

/**
 * Register plugin settings
 * @since  1.0.0
 */
add_action( 'admin_init', 'kbe_register_settings' );
function kbe_register_settings() {
    register_setting( 'kbe_settings', 'kbe_settings', 'kbe_validate_settings' );
}

/**
 * Conditionally enqueue admin scripts/styles
 * @since  1.1.0
 */
add_action( 'current_screen', 'kbe_admin_settings_scripts' );
function kbe_admin_settings_scripts($screen) {
    // first check that $hook_suffix is appropriate for your admin page
    if( $screen->id == 'kbe_knowledgebase_page_kbe_options' ){
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE.'js/color_picker.js', array( 'wp-color-picker' ), false, true);
    } elseif ( $screen->id == 'kbe_knowledgebase_page_kbe_order' ){
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
    if( $screen->post_type == 'kbe_knowledgebase' ){
        wp_enqueue_style('kbe_admin_css', WP_KNOWLEDGEBASE.'css/kbe_admin_style.css');
    }
}


/**
 * Callback for main plugin settings
 * @since  1.0.0
 */
function wp_kbe_options(){
    require "kbe_settings.php";
}

/**
 * Callback for post type re-ordering
 * @since  1.0.0
 */
function wp_kbe_order(){
    require "kbe_order.php";
}

/**
 * Sanitize and validate plugin settings
 * @param  array $input
 * @return array
 * @since  1.1.0
 */
function kbe_validate_settings( $input ) {
    $settings = get_option( 'kbe_settings' );

    $clean = array();

    $clean['plugin_slug'] = isset( $input['plugin_slug'] ) ? sanitize_title( $input['plugin_slug'] ) : '';
    $clean['article_qty'] = intval( $input['article_qty'] );

    $clean['search_settings'] =  isset( $input['search_settings'] ) && $input['search_settings'] ? 1 : 0 ;  //checkbox
    $clean['breadcrumb_settings'] =  isset( $input['breadcrumb_settings'] ) && $input['breadcrumb_settings'] ? 1 : 0 ;  //checkbox

    $radio = array( 0, 1, 2 );

    $clean['sidebar_home'] = isset( $input['sidebar_home'] ) && in_array( $input['sidebar_home'], $radio ) ? intval( $input['sidebar_home'] ) : 0;
    $clean['sidebar_inner'] = isset( $input['sidebar_inner'] ) && in_array( $input['sidebar_inner'], $radio ) ? intval( $input['sidebar_inner'] ) : 0;

    $clean['comment_settings'] =  isset( $input['comment_settings'] ) && $input['comment_settings'] ? 1 : 0 ;  //checkbox

    $clean['bgcolor'] = isset( $input['bgcolor'] ) ? sanitize_hex_color( $input['bgcolor'] ) : '';

    $clean['uninstall_mode'] = isset( $input['uninstall_mode'] ) && in_array( $input['uninstall_mode'], $radio ) ? intval( $input['sidebar_home'] ) : 0;
    
    return $clean;
    
}

