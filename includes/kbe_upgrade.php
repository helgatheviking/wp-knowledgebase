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
        
        // check if new $settings exist
     	$settings = get_option( 'kbe_settings', array() );
     	
     	// merge all old options fields into 1 option
     	if ( empty( $settings )) {
     		
	     	$permalinks = array();

    		global $wpdb;
			// look for an existing page
			$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%[kbe_knowledgebase]%" ) );

			$settings['archive_page_id'] = $page_found && $page_found > 0 ? $page_found : 0;
			$settings['article_qty'] = get_option( 'kbe_article_qty', 5 );
			$settings['search_setting'] = get_option( 'kbe_search_setting', 0 );
			$settings['breadcrumbs_setting'] = get_option( 'kbe_breadcrumbs_setting', 0 );
			$settings['sidebar_home'] = get_option( 'kbe_sidebar_home', 0 );
			$settings['sidebar_inner'] = get_option( 'kbe_sidebar_inner', 0 );
			$settings['comments_setting'] = get_option( 'kbe_comments_setting', 0 );
			$settings['bgcolor'] = get_option( 'kbe_bgcolor', '' );

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
			update_option( 'kbe_settings', $settings );
			update_option( 'kbe_permalinks', $settings );

		}

        // update the db version number
        update_option( $v, '1.1.0' );
 
    }

    return false;
}
