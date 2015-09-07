<?php
/*
  Plugin Name: WP Knowledgebase
  Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
  Description: Simple and flexible knowledgebase plugin for WordPress
  Author: Enigma Plugins
  Version: 1.0.9
  Author URI: http://enigmaplugins.com
  Requires at least: 4.3
 */


//=========> Define plugin URL
define( 'WP_KNOWLEDGEBASE', plugin_dir_url(__FILE__));

//=========> Define plugin path
define( 'WP_KNOWLEDGEBASE_PATH', plugin_dir_path(__FILE__));

//=========> Define plugin version
define( 'KBE_PLUGIN_VERSION', '1.0.9' );

/**
 * Load Localisation files.
 * @since 1.0.0
 */
add_action( 'plugins_loaded', 'kbe_plugin_load_textdomain' );
function kbe_plugin_load_textdomain() {
    load_plugin_textdomain( 'kbe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Require Files
 * @since 1.1.0
 */
add_action( 'plugins_loaded', 'kbe_includes' );
function kbe_includes(){
    // admin includes
    if(is_admin()){
        //  Plugin Settings
        require "includes/kbe_admin.php";
        //  Plugin upgrade routine
        require "includes/kbe_upgrade.php";
    }

    //  Post type and taxonomies
    require "includes/kbe_post_type.php";
    //  Front end display functions
    require "includes/kbe_frontend_functions.php";
    //  Template tags
    require "includes/kbe_template_functions.php";

    //  Require Category Widget file
    require "widget/kbe_widget_category.php";
    //  Require Articles Widget file
    require "widget/kbe_widget_article.php";
    //  Require Search Articles Widget file
    require "widget/kbe_widget_search.php";
    //  Require Tags Widget file
    require "widget/kbe_widget_tags.php";

}



/**
 * Activate Plugin
 * @since 1.0.0
 */
register_activation_hook(__FILE__, 'wp_kbe_hooks');
function wp_kbe_hooks() {
    global $wpdb;

    /*Create "term_order" Field in "wp_terms" Table for sortable order*/
    $term_order_qry = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'terms_order'");
    if($term_order_qry == 0){
        $wpdb->query("ALTER TABLE $wpdb->terms ADD `terms_order` INT(4) NULL DEFAULT '0'");
    }

    // set default settings
    $settings = get_option( 'kbe_settings', array() );

    if( empty( $settings ) ){
        $settings = array( 
            'archive_page_id' => 0,
            'article_qty' => 5,
            'search_setting' =>  0,
            'breadcrumb_setting' =>  0,
            'sidebar_home' => 0,
            'sidebar_inner' => 0,
            'comment_setting' => 0,
            'uninstall_mode' => 0,
            'bgcolor' => '',
        );
    }

    // create the archive page
    $archive_page_id = isset( $settings['archive_page_id'] ) ? $settings['archive_page_id'] : 0;

    // check to see if has settings page and it exists
    if ( $archive_page_id > 0 && ( $page_object = get_post( $archive_page_id ) ) ) {
        if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ){
            return; // found the page and it is published so we're good
        } 
    }

    // Search for an existing page with the specified page content (typically a shortcode)
    $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%[kbe_knowledgebase]%" ) );
    
    // valid page was found so update settings
    if ( $valid_page_found ) {
        $settings['archive_page_id'] = $valid_page_found;
        update_option( 'kbe_settings', $settings );
        return;
    }

    // Search for a matching valid trashed page
    $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%[kbe_knowledgebase]%" ) );
    
    // update trashed page
    if ( $trashed_page_found ) {
        $page_id   = $trashed_page_found;
        $page_data = array(
            'ID'             => $page_found,
            'post_status'    => 'publish',
        );
        $page_id = wp_update_post( $page_data );
    }
    // or create the new page
    else {
        $page_data = array(
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_author'    => get_current_user_id(),
            'post_name'      => _x( 'knowledgebase', 'default slug', 'kbe' ),
            'post_title'     => __( 'Knowledgebase', 'kbe' ),
            'post_content'   => '[kbe_knowledgebase]',
            'comment_status' => 'closed',
            'ping_status'           =>  'closed',
        );
        $page_id   = wp_insert_post( $page_data );
    } 

    $settings['archive_page_id'] = $page_id;
    update_option( 'kbe_settings', $settings );
    
    //  Flush Rewrite Rules
    require "includes/kbe_post_type.php";
    kbe_articles();
    kbe_taxonomies();
    flush_rewrite_rules();

}

/**
 * Trigger activation on new blogs if network activated
 * @since 1.1.0
 */
add_action( 'wpmu_new_blog', 'kbe_activate_sitewide_plugins' );
function kbe_activate_sitewide_plugins( $blog_id ){
    // Switch to new website
    switch_to_blog( $blog_id );
 
    // Activate
    do_action( 'activate_wp-knowledgebase', false );
 
    // Restore current website
    restore_current_blog();
}
 

/**
 * Deprecate all constants eventually
 * @deprecated  1.1.0
 */
//  define options values
$settings = get_option( 'kbe_settings' );
define('KBE_ARTICLE_QTY', isset( $settings['article_qty'] ) ? $settings['article_qty'] : 5 );
define('KBE_PLUGIN_SLUG', isset( $settings['article_base'] ) ? $settings['article_base'] : 'knowledgebase' );
define('KBE_SEARCH_SETTING', isset( $settings['search_setting'] ) ? $settings['search_setting'] : 0 );
define('KBE_BREADCRUMBS_SETTING', isset( $settings['breadcrumbs_setting'] ) ? $settings['breadcrumbs_setting'] : 0 );
define('KBE_SIDEBAR_HOME', isset( $settings['sidebar_home'] ) ? $settings['sidebar_home'] : 0 );
define('KBE_SIDEBAR_INNER', isset( $settings['sidebar_inner'] ) ? $settings['sidebar_inner'] : 0 );
define('KBE_COMMENT_SETTING', isset( $settings['comments_setting'] ) ? $settings['comments_setting'] : 0 );
define('KBE_BG_COLOR', isset( $settings['bgcolor'] ) ? $settings['bgcolor'] : '' );
define('KBE_LINK_STRUCTURE', get_option('permalink_structure') );
define('KBE_POST_TYPE', 'kbe_knowledgebase');
define('KBE_POST_TAXONOMY', 'kbe_taxonomy');
define('KBE_POST_TAGS', 'kbe_tags');
define('KBE_PAGE_TITLE', isset( $settings['archive_page_id'] ) ? $settings['archive_page_id'] : 0 );


/**
 * Deprecated functions
 * @deprecated  1.1.0
 */
function kbe_styles(){
    _deprecated_function( 'kbe_styles', '1.1.0', 'kbe_frontend_scripts' );
}


/**
 * Load Live search
 * @deprecated  1.1.0
 */
function kbe_live_search(){
    _deprecated_function( 'kbe_live_search', '1.1.0', 'kbe_frontend_scripts' );
}

/**
 * Load admin scripts
 * @deprecated  1.1.0
 */
function wp_kbe_scripts(){
    _deprecated_function( 'wp_kbe_scripts', '1.1.0', 'kbe_admin_settings_scripts' );
}

/**
 * Load all jquery in admin settings
 * @deprecated  1.1.0
 */
function enqueue_color_picker($hook_suffix) {
    _deprecated_function( 'enqueue_color_picker', '1.1.0', 'kbe_admin_settings_scripts' );
}

/**
 * Load all jquery in admin settings
 * @deprecated  1.1.0
 */
function load_all_jquery() {
    _deprecated_function( 'load_all_jquery', '1.1.0', 'kbe_admin_settings_scripts' );
}