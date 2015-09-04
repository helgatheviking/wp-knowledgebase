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
    register_setting('kbe_settings_group', 'kbe_plugin_slug');
    register_setting('kbe_settings_group', 'kbe_article_qty');
    register_setting('kbe_settings_group', 'kbe_search_setting');
    register_setting('kbe_settings_group', 'kbe_breadcrumbs_setting');
    register_setting('kbe_settings_group', 'kbe_sidebar_home');
    register_setting('kbe_settings_group', 'kbe_sidebar_inner');
    register_setting('kbe_settings_group', 'kbe_comments_setting');
    register_setting('kbe_settings_group', 'kbe_bgcolor');
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


