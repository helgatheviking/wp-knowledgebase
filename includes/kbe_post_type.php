<?php
/**
 * Custom Post Type and Custom Taxonomies
 *
 * @version     1.1.0
 * @author      Enigma Plugins
 */

/**
 * Register Post Type
 * @since  1.0.0
 */
add_action('init', 'kbe_articles');
function kbe_articles() {

    $settings = get_option( 'kbe_settings' );
    $permalinks = get_option( 'kbe_permalinks' );

    $archive_page_id = isset( $settings['archive_page_id' ] ) ? $settings['archive_page_id'] : 0;
    
    $article_permalink = ! empty( $permalinks['article_base'] ) ? $permalinks['article_base'] : _x( 'knowledgebase', 'default slug', 'kbe' );
    
    $labels = array(
        'name'                  => 	__('Knowledgebase', 'kbe'),
        'singular_name'         => 	__('Knowledgebase', 'kbe'),
        'all_items'             => 	__('Articles', 'kbe'),
        'add_new'               => 	__('New Article', 'kbe'),
        'add_new_item'          => 	__('Add New Article', 'kbe'),
        'edit_item'             => 	__('Edit Article', 'kbe'),
        'new_item'              => 	__('New Article', 'kbe'),
        'view_item'             => 	__('View Article', 'kbe'),
        'search_items'          => 	__('Search Articles', 'kbe'),
        'not_found'             => 	__('Nothing found', 'kbe'),
        'not_found_in_trash'    => 	__('Nothing found in Trash', 'kbe'),
        'parent_item_colon'     => 	''
    );
    
    $args = apply_filters( 'kbe_post_type_args', array(
        'labels'                => 	$labels,
        'description'         => __( 'This is where you can add new articles to your knowledgebase.', 'kbe' ),
        'public'                => 	true,
        'publicly_queryable'    => 	true,
        'show_ui'               => 	true,
        'query_var'             => 	true,
        'menu_icon'             => 	'dashicons-book-alt',
        'capability_type'       => 	'post',
        'hierarchical'          => 	false,
        'supports'              => 	array('title','editor','thumbnail','comments','tags','revisions'),
        'rewrite'               =>  $article_permalink ? array( 'slug' => untrailingslashit( $article_permalink ), 'with_front' => false, 'feeds' => true ) : false,
        'show_in_menu'          => 	true,
        'show_in_nav_menus'     => 	true,
        'show_in_admin_bar'     => 	true,
        'can_export'            => 	true,
        'has_archive'           => 	$archive_page_id && get_post( $archive_page_id ) ? get_page_uri( $archive_page_id ) : 'knowledgebase',
        'exclude_from_search'   => 	true

    ) );
 
    register_post_type( 'kbe_knowledgebase' , $args );
}


/**
 * Register Custom Category
 * @since  1.0.0
 */
add_action( 'init', 'kbe_taxonomies', 0 );
function kbe_taxonomies() {

    $permalinks = get_option( 'kbe_permalinks' );

    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => 	__( 'Knowledgebase Category', 'kbe'),
        'singular_name'     => 	__( 'Knowledgebase Category', 'kbe' ),
        'search_items'      => 	__( 'Search Knowledgebase Category', 'kbe' ),
        'all_items'         => 	__( 'All Knowledgebase Categories', 'kbe' ),
        'parent_item'       => 	__( 'Parent Knowledgebase Category', 'kbe' ),
        'parent_item_colon' => 	__( 'Parent Knowledgebase Category:', 'kbe' ),
        'edit_item'         => 	__( 'Edit Knowledgebase Category', 'kbe' ),
        'update_item'       => 	__( 'Update Knowledgebase Category', 'kbe' ),
        'add_new_item'      => 	__( 'Add New Knowledgebase Category', 'kbe' ),
        'new_item_name'     => 	__( 'New Knowledgebase Category Name', 'kbe' ),
        'menu_name'         => 	__( 'Categories', 'kbe' )
    ); 	

    $category_base = isset( $permalinks['category_base'] ) ? $permalinks['category_base'] : 'knowledgebase_category';

    $args = apply_filters( 'kbe_taxonomy_args', array (
        'hierarchical'      =>  true,
        'labels'            =>  $labels,
        'singular_label'    =>  __( 'Knowledgebase Category', 'kbe'),
        'show_ui'           =>  true,
        'query_var'         =>  true,
        'rewrite'           =>  array( 'slug' => $category_base, 'with_front' => false, 'hierarchical' => true )
    ) );

    register_taxonomy( 'kbe_taxonomy', array( 'kbe_knowledgebase' ), $args );

    $labels = array(
                    'name'      =>  __( 'Knowledgebase Tags', 'kbe' ),
                    'singular_name'     =>  __( 'Knowledgebase Tag', 'kbe' ),
                    'search_items'  =>  __( 'Search Knowledgebase Tags', 'kbe' ),
                    'all_items'     =>  __( 'All Knowledgebase Tags', 'kbe' ),
                    'edit_item'     =>  __( 'Edit Knowledgebase Tag', 'kbe' ),
                    'update_item'   =>  __( 'Update Knowledgebase Tag', 'kbe' ),
                    'add_new_item'  =>  __( 'Add New Knowledgebase Tag', 'kbe' ),
                    'new_item_name'     =>  __( 'New Knowledgebase Tag Name', 'kbe' ),
                    'menu_name'     =>  __( 'Tags', 'kbe' )
            );

    $tag_base = isset( $permalinks['tag_base'] ) ? $permalinks['tag_base'] : 'knowledgebase_tags';

    $args = apply_filters( 'kbe_tags_args', array (
        'hierarchical'      =>  false,
        'labels'            =>  $labels,
        'show_ui'           =>  true,
        'query_var'         =>  true,
        'rewrite'           =>  array( 'slug' => $tag_base, 'with_front' => true )
    ) );

    register_taxonomy( 'kbe_tags', array('kbe_knowledgebase'), $args );

}

/**
 * Register Custom Tags
 * @since  1.0.0
 * @deprecated 1.1.0
 */
function kbe_custom_tags() {
    _deprecated_function( 'kbe_custom_tags', '1.1.0', 'kbe_taxonomies' );
}


/**
 * Set Article Views
 * @since  1.0.0
 */
function kbe_set_post_views($postID) {
    $count_key = 'kbe_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
	
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/**
 * Remove Pre-Fetching to keep count accurate
 * @since  1.0.0
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
function kbe_get_post_views($postID){
    $count_key = 'kbe_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
	
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
        $count = 1;
    }

    return sprintf( _n( '%s View', '%s Views', $count, 'kbe' ), $count );

}

/**
 * Custom Post Type Columns
 * @param array $columns
 * @since  1.0.0
 */
add_filter("manage_edit-kbe_knowledgebase_columns", "kbe_edit_columns");     
function kbe_edit_columns($columns){
    $columns = array(  
        "cb" 		=> 	"<input type=\"checkbox\" />", 
        "title" 	=> 	__("Title", "kbe"),
        "author" 	=> 	__("Author", "kbe"),
        "cat" 		=> 	__("Cateogry", "kbe"),
        "tag" 		=> 	__("Tags", "kbe"),
        "comment" 	=> 	__("Comments", "kbe"),
        'views' 	=> 	__("Views", "kbe"),
        "date" 		=> 	__("Date", "kbe")
    );
    return $columns;  
}    

/**
 * Display of Custom Post Type Columns
 * @param array $column
 * @since  1.0.0
 */
add_action("manage_posts_custom_column",  "kbe_custom_columns");   
function kbe_custom_columns($column){
    global $post;  
    switch ($column){ 
        case "title":         
            the_title();
        break; 
        case "author":         
            the_author();
        break;
        case "cat":         
            echo get_the_term_list( $post->ID, 'kbe_taxonomy' , ' ' , ', ' , '' );
        break;
        case "tag":         
            echo get_the_term_list( $post->ID, 'kbe_tags' , ' ' , ', ' , '' );
        break;
        case "comment":         
            comments_number( __('No Comments','kbe'), __('1 Comment','kbe'), __('% Comments','kbe') );
        break;
        case "views":
            $views = get_post_meta($post->ID, 'kbe_post_views_count', true);
            if($views){
                echo $views .__(' Views', 'kbe');
            }else{
                echo __('No Views', 'kbe');
            }
        break;
        case "date":         
            the_date();
        break;
    }
}


/**
 * Register KBE widget area
 * @since  1.0.0
 */
add_action( 'widgets_init' , 'kbe_register_sidebar' );
function kbe_register_sidebar(){
    register_sidebar(array(
        'name' => __('WP Knowledgebase Sidebar','kbe'),
        'id' => 'kbe_cat_widget',
        'description' => __('WP Knowledgebase sidebar area','kbe'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h6>',
        'after_title' => '</h6>',
    ));
}