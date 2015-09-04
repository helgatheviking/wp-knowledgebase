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
    //  Post type and taxonomies
    require "includes/kbe_frontend_functions.php";
    //  Post type and taxonomies
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
function wp_kbe_hooks($kbe_networkwide) {
    
    kbe_articles();
    kbe_taxonomies();
    kbe_custom_tags();
    flush_rewrite_rules();
    
    global $wpdb;
    /*Create "term_order" Field in "wp_terms" Table for sortable order*/
    $term_order_qry = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'terms_order'");
    if($term_order_qry == 0){
        $wpdb->query("ALTER TABLE $wpdb->terms ADD `terms_order` INT(4) NULL DEFAULT '0'");
    }
    
    $kbe_prefix = $wpdb->prefix;

    $kbe_pageSql = $wpdb->get_results("Select *
                                       From ".$kbe_prefix."posts
                                       Where post_content like '%[kbe_knowledgebase]%'
                                       And post_type = 'page'");

    if(!$kbe_pageSql){
        //  Insert a "Knowledgebase" page
        $kbe_max_page_Sql = $wpdb->get_results("SELECT Max(ID) As kbe_maxId FROM ".$kbe_prefix."posts");
        foreach($kbe_max_page_Sql as $kbe_max_page_row) {
            $kbe_maxId = $kbe_max_page_row->kbe_maxId;
            $kbe_maxId = $kbe_maxId + 1;
        }

        $kbe_now = date('Y-m-d H:i:s');
        $kbe_now_gmt = gmdate('Y-m-d H:i:s');
        $kbe_guid = get_option('home') . '/?page_id='.$kbe_maxId;
        $kbe_user_id = get_current_user_id();

        $kbe_table_posts = $wpdb->prefix.'posts';

        $kbe_data_posts = array(
                            'post_author'           =>  $kbe_user_id,
                            'post_date'             =>  $kbe_now,
                            'post_date_gmt'         =>  $kbe_now_gmt,
                            'post_content'          =>  '[kbe_knowledgebase]',
                            'post_title'            =>  'Knowledgebase',
                            'post_excerpt'          =>  '',
                            'post_status'           =>  'publish',
                            'comment_status'        =>  'closed',
                            'ping_status'           =>  'closed',
                            'post_password'         =>  '',
                            'post_name'             =>  'knowledgebase',
                            'to_ping'               =>  '',
                            'pinged'                =>  '',
                            'post_modified'         =>  $kbe_now,
                            'post_modified_gmt'     =>  $kbe_now_gmt,
                            'post_content_filtered' =>  '',
                            'post_parent'           =>  '0',
                            'guid'                  =>  $kbe_guid,
                            'menu_order'            =>  '0',
                            'post_type'             =>  'page',
                            'post_mime_type'        =>  '',
                            'comment_count'         =>  '0',
                        );
        $wpdb->insert($kbe_table_posts,$kbe_data_posts) or die(mysql_error());

        //  Insert a page template for knowlwdgebase
        $kbe_tempTableSql = $wpdb->get_results("Select post_content, ID
                                                From ".$kbe_prefix."posts
                                                Where post_content Like '%[kbe_knowledgebase]%'
                                                And post_type <> 'revision'");
        foreach($kbe_tempTableSql as $kbe_tempTableRow) {
            $tempPageId = $kbe_tempTableRow->ID;

            //  Set Knowledgebase page template
            add_post_meta($tempPageId, '_wp_page_template', 'wp_knowledgebase/kbe_knowledgebase.php');
        }
    }

    $kbe_optSlugSql = $wpdb->get_results("Select * From ".$kbe_prefix."options Where option_name like '%kbe_plugin_slug%'");

    if(!$kbe_optSlugSql){
        add_option( 'kbe_plugin_slug', 'knowledgebase', '', 'yes' );
    }

    $kbe_optPageSql = $wpdb->get_results("Select * From ".$kbe_prefix."options Where option_name like '%kbe_article_qty%'");

    if(!$kbe_optPageSql){
        add_option( 'kbe_article_qty', '5', '', 'yes' );
    }
    
    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($kbe_networkwide) {
            $kbe_old_blog = $wpdb->blogid;
            // Get all blog ids
            $kbe_blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($kbe_blog_ids as $kbe_blog_id) {
                switch_to_blog($kbe_blog_id);
            }
            switch_to_blog($kbe_old_blog);
            return;
        }   
    } 
}


/**
 * Deprecate all constants eventually
 * @deprecated  1.1.0
 */
//  define options values
define('KBE_ARTICLE_QTY', get_option('kbe_article_qty'));
define('KBE_PLUGIN_SLUG', get_option('kbe_plugin_slug'));
define('KBE_SEARCH_SETTING', get_option('kbe_search_setting'));
define('KBE_BREADCRUMBS_SETTING', get_option('kbe_breadcrumbs_setting'));
define('KBE_SIDEBAR_HOME', get_option('kbe_sidebar_home'));
define('KBE_SIDEBAR_INNER', get_option('kbe_sidebar_inner'));
define('KBE_COMMENT_SETTING', get_option('kbe_comments_setting'));
define('KBE_BG_COLOR', get_option('kbe_bgcolor'));
define('KBE_LINK_STRUCTURE', get_option('permalink_structure'));
define('KBE_POST_TYPE', 'kbe_knowledgebase');
define('KBE_POST_TAXONOMY', 'kbe_taxonomy');
define('KBE_POST_TAGS', 'kbe_tags');

//=========> Get Knowledgebase title
global $wpdb;
$getSql = $wpdb->get_results("Select ID From $wpdb->posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'");

foreach($getSql as $getRow) {
    $pageId = $getRow->ID;
}
define('KBE_PAGE_TITLE', $pageId);



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