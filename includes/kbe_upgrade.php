<?php
/**
 * Plugin Upgrades
 *
 * @version     1.1.0
 * @author      Enigma Plugins
 */

/**
 * Upgrade Routine
 * @since  1.1.0
 */
add_action( 'admin_init', 'kbe_upgrade_plugin', 1 );
function kbe_upgrade_plugin(){

	$v = 'kbe_db_version';

    $db_version = get_option( $v, false );

    // major 1.1.0 upgrade
    if( version_compare( $db_version, '1.1.0', '<')) {
        
     	// merge all options fields into 1 option
     	$options = $permalinks = array();

    	global $wpdb;
		$getSql = $wpdb->get_results("Select ID From $wpdb->posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'");

		foreach($getSql as $getRow) {
		    $options['archive_page_id'] = $getRow->ID;
		}

		$options['article_qty'] = get_option( 'kbe_article_qty', 5 );
		$options['search_setting'] = get_option( 'kbe_search_setting', 0 );
		$options['breadcrumb_setting'] = get_option( 'kbe_breadcrumbs_setting', 0 );
		$options['sidebar_home'] = get_option( 'kbe_sidebar_home', 0 );
		$options['sidebar_inner'] = get_option( 'kbe_sidebar_inner', 0 );
		$options['comments_setting'] = get_option( 'kbe_comments_setting', 0 );
		$options['bgcolor'] = get_option( 'kbe_bgcolor', '' );

		$slug = trim( get_option( 'kbe_plugin_slug' ) );
		$permalinks['article_base'] = $slug ? $slug : 'knowledgebase';

		// delete old options
		delete_option('kbe_bgcolor');
	    delete_option('kbe_plugin_slug');
	    delete_option('kbe_article_qty');
	    delete_option('kbe_sidebar_home');
	    delete_option('kbe_sidebar_inner');
	    delete_option('kbe_search_setting');
	    delete_option('kbe_comments_setting');
	    delete_option('kbe_taxonomy_children');
	    delete_option('kbe_breadcrumbs_setting');
	    delete_option('widget_kbe_tags_widgets');
	    delete_option('widget_kbe_search_widget');
	    delete_option('widget_kbe_article_widget');
	    delete_option('widget_kbe_category_widget');
	    delete_option('kbe_permalinks');

		// update the new options
		update_option( 'kbe_settings', $options );
		update_option( 'kbe_permalinks', $options );

        // update the db version number
        update_option( $v, '1.1.0' );
 
    }

    return false;
}
