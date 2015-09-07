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
	// regular plugin settings
	register_setting( 'kbe_settings', 'kbe_settings', 'kbe_validate_settings' );

	// Add a section to the permalinks page
	add_settings_section( 'wp-knowledgebase-permalink', __( 'Knowledgebase Article permalink base', 'kbe' ), 'kbe_permalink_display_settings', 'permalink' );

	// Add our permalink settings
	add_settings_field(
		'kbe_article_category_slug',            // id
		__( 'Knowledgebase category base', 'kbe' ),   // setting title
		'kbe_article_category_slug_input',  // display callback
		'permalink',                                    // settings page
		'optional'                                      // settings section
	);
	add_settings_field(
		'kbe_article_tag_slug',                 // id
		__( 'Knowledgebase tag base', 'kbe' ),        // setting title
		'kbe_article_tag_slug_input',       // display callback
		'permalink',                                    // settings page
		'optional'                                      // settings section
	);

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
        wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE_URL.'js/admin/color_picker.js', array( 'wp-color-picker' ), KBE_PLUGIN_VERSION, true);
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
 
	$clean = array();
  
	$clean['archive_page_id'] = isset( $input['archive_page_id'] ) && ! is_wp_error( get_post( intval( $input['archive_page_id'] ) ) ) ? intval( $input['archive_page_id'] ) : 0;
 
	$clean['article_qty'] = intval( $input['article_qty'] );

	$clean['search_setting'] =  isset( $input['search_setting'] ) && $input['search_setting'] ? 1 : 0 ;  //checkbox
	$clean['breadcrumb_setting'] =  isset( $input['breadcrumb_setting'] ) && $input['breadcrumb_setting'] ? 1 : 0 ;  //checkbox

	$radio = array( 0, 1, 2 );

	$clean['sidebar_home'] = isset( $input['sidebar_home'] ) && in_array( $input['sidebar_home'], $radio ) ? intval( $input['sidebar_home'] ) : 0;
	$clean['sidebar_inner'] = isset( $input['sidebar_inner'] ) && in_array( $input['sidebar_inner'], $radio ) ? intval( $input['sidebar_inner'] ) : 0;

	$clean['comment_settings'] =  isset( $input['comment_settings'] ) && $input['comment_settings'] ? 1 : 0 ;  //checkbox

	$clean['bgcolor'] = isset( $input['bgcolor'] ) ? sanitize_hex_color( $input['bgcolor'] ) : '';

	$clean['uninstall_mode'] = isset( $input['uninstall_mode'] ) && in_array( $input['uninstall_mode'], $radio ) ? intval( $input['sidebar_home'] ) : 0;
	
	return $clean;
	
}


/**
 * Show a slug input box.
 * @since  1.1.0
 */
function kbe_article_category_slug_input() {                      
    $permalinks = get_option( 'kbe_permalinks' );
    ?>
    <input name="kbe_permalinks[category_base]" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['category_base'] ) ) echo esc_attr( $permalinks['category_base'] ); ?>" placeholder="<?php echo esc_attr_x('knowledgebase_category', 'slug', 'kbe') ?>" />
    <?php
}


/**
 * Show a slug input box.
 * @since  1.1.0
 */
function kbe_article_tag_slug_input() {
    $permalinks = get_option( 'kbe_permalinks' );
    ?>
    <input name="kbe_permalinks[tag_base]" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['tag_base'] ) ) echo esc_attr( $permalinks['tag_base'] ); ?>" placeholder="<?php echo esc_attr_x('knowledgebase_tags', 'slug', 'kbe') ?>" />
    <?php
}

/**
 * Show the settings
 * @since  1.1.0
 */
function kbe_permalink_display_settings() {
	echo wpautop( __( 'These settings control the permalinks used for Knowledgebase articles. These settings only apply when <strong>not</strong> using "default" permalinks above.', 'kbe' ) );

	$settings = get_option( 'kbe_settings' );
	$permalinks = get_option( 'kbe_permalinks' );

	$article_permalink = isset( $permalinks['article_base'] ) ? $permalinks['article_base'] : 'kbe_knowledgebase';

	$category_slug = isset( $permalinks['category_base'] ) ? $permalinks['category_base'] : 'knowledgebase_category';

	// Get archive page
	$archive_page_id   = isset( $settings['archive_page_id'] ) ? $settings['archive_page_id'] : 0;
 
	$archive_slug      = urldecode( ( $archive_page_id > 0 && get_post( $archive_page_id ) ) ? get_page_uri( $archive_page_id ) : _x( 'knowledgebase', 'default slug', 'kbe' ) );
	$default_base   = _x( 'knowledgebase', 'default slug', 'kbe' );

	$structures = array(
		0 => '',
		1 => '/' . trailingslashit( $default_base ),
		2 => '/' . trailingslashit( $archive_slug )
	);

		?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label><input name="kbe_permalinks[article_permalink]" type="radio" value="<?php echo esc_attr( $structures[0] ); ?>" class="kbetog" <?php checked( $structures[0], $article_permalink ); ?> /> <?php _e( 'Default', 'kbe' ); ?></label></th>
				<td><code><?php echo esc_html( home_url() ); ?>/?knowledgebase=sample-article</code></td>
			</tr>
			<tr>
				<th><label><input name="kbe_permalinks[article_permalink]" type="radio" value="<?php echo esc_attr( $structures[1] ); ?>" class="kbetog" <?php checked( $structures[1], $article_permalink ); ?> /> <?php _e( 'Article', 'kbe' ); ?></label></th>
				<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $default_base ); ?>/sample-article/</code></td>
			</tr>
		<?php if ( $archive_page_id ) : ?>
			<tr>
				<th><label><input name="kbe_permalinks[article_permalink]" type="radio" value="<?php echo esc_attr( $structures[2] ); ?>" class="kbetog" <?php checked( $structures[2], $article_permalink ); ?> /> <?php _e( 'Knowledgebase archive', 'kbe' ); ?></label></th>
				<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $archive_slug ); ?>/sample-article/</code></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th><label><input name="kbe_permalinks[article_permalink]" id="knowledgebase_custom_selection" type="radio" value="custom" class="tog" <?php checked( in_array( $article_permalink, $structures ), false ); ?> />
				<?php _e( 'Custom Base', 'kbe' ); ?></label></th>
				<td>
					<input name="kbe_permalinks[article_permalink_structure]" id="knowledgebase_permalink_structure" type="text" value="<?php echo esc_attr( $article_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'kbe' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery( function() {
			jQuery('input.kbetog').change(function() {
				jQuery('#knowledgebase_permalink_structure').val( jQuery( this ).val() );
			});

			jQuery('#knowledgebase_permalink_structure').focus( function(){
				jQuery('#knowledgebase_custom_selection').click();
			} );
		} );
	</script>
<?php
}

/**
 * Save the permalinks
 * @since  1.1.0
 */
add_action( 'load-options-permalink.php', 'kbe_validate_permalinks' );
function kbe_validate_permalinks(){

	// @todo check permissions

	// We need to save the options ourselves; settings api does not trigger save for the permalinks page
	if ( isset( $_POST['kbe_permalinks'] ) ) {

		$input = $_POST['kbe_permalinks'];

		$settings = get_option( 'kbe_settings', array() );
		$permalinks = get_option( 'kbe_permalinks', array() );

		$permalinks['category_base'] = isset( $input['category_base'] ) ? untrailingslashit( sanitize_text_field( $input['category_base'] ) ) : '';
		$permalinks['tag_base'] = isset( $input['tag_base'] ) ? untrailingslashit( sanitize_text_field( $input['tag_base'] ) ) : '';

		// article base
		$article_permalink = isset( $input['article_permalink'] ) ? untrailingslashit( sanitize_text_field( $input['article_permalink'] ) ) : '';

		if ( $article_permalink == 'custom' ) {
			// Get permalink without slashes
			$article_permalink = trim( sanitize_text_field( $input['article_permalink_structure'] ), '/' );

			// This is an invalid base structure and breaks pages
			if ( '%article_cat%' == $article_permalink ) {
				$article_permalink = _x( 'knowledgebase', 'slug', 'kbe' ) . '/' . $article_permalink;
			}

			// Prepending slash
			$article_permalink = '/' . $article_permalink;
		} elseif ( empty( $article_permalink ) ) {
			$article_permalink = false;
		}

		$permalinks['article_base'] = untrailingslashit( $article_permalink );

		// Shop base may require verbose page rules if nesting pages
		$archive_page_id = isset( $settings['archive_page_id' ] ) ? $settings['archive_page_id'] : 0;
		$archive_permalink = ( $archive_page_id > 0 && get_post( $archive_page_id ) ) ? get_page_uri( $archive_page_id ) : _x( 'knowledgebase', 'default-slug', 'kbe' );

		if ( $archive_page_id && trim( $permalinks['article_base'], '/' ) === $archive_permalink ) {
			$permalinks['use_verbose_page_rules'] = true;
		}

		update_option( 'kbe_permalinks', $permalinks );

	}

}