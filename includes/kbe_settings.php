<?php
/**
 * Knowledgebase Settings Display Page
 *
 * @version     1.0.0
 * @author      Enigma Plugins
 */

$defaults = array( 
	'archive_page_id' => 0,
	'article_qty' => 5,
	'search_setting' =>  0,
	'breadcrumbs_setting' =>  0,
	'sidebar_home' => 0,
	'sidebar_inner' => 0,
	'comment_setting' => 0,
	'uninstall_mode' => 0,
	'bgcolor' => '',
);
$settings = wp_parse_args( get_option( 'kbe_settings' ), $defaults );
?>
<div id="wpbody">
	<div id="wpbody-content">
		<div class="wrap">
			
			<h1><?php _e('Knowledgebase Display Settings','kbe')?></h1>
	
			<?php settings_errors('kbe_settings');            ?>
		
			<div class="kbe_admin_settings kbe_admin_left_content">
					
				<form method="post" action="options.php">
			
				<?php settings_fields('kbe_settings'); ?>
			
				<h3 class="title"><?php _e( 'Settings', 'kbe' ); ?></h3>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form-table knowedgebase-settings">
					<tr>
						<th><label for="kbe_archive_page_id"><?php _e( 'Knowledgebase Archive','kbe' ); ?></label></th>
						<td colspan="3">
							<?php 
							$dropdown_args = array( 'show_option_none' => __( '--Select the Knowledgebase Archive--' ), 
													'name'=>'kbe_settings[archive_page_id]',
													'id' => 'kbe_archive_page_id',
													'selected' => $settings['archive_page_id'] );
							wp_dropdown_pages( $dropdown_args );
							?> 
						<p class="description">
							<?php printf( __( 'The base page can also be used in your <a href="%s">Knowledgebase permalinks</a>.', 'kbe' ), '<strong>', '</strong>', admin_url( 'options-permalink.php' ) ); ?>
						</p>
						</td>
					</tr>
					<tr>
						<th><label for="kbe_article_qty"><?php _e('Number of articles to show','kbe'); ?></label></th>
						<td colspan="3">
							<input type="text" name="kbe_settings[article_qty]" id="kbe_article_qty" value="<?php echo esc_attr( $settings['article_qty'] ); ?>">
							<p class="description">
								<?php _e( 'Set the number of articles to show in each category on KB homepage', 'kbe' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><?php _e('Display search bar','kbe'); ?></th>
						<td 
							<fieldset>
								<label><input type="radio" name="kbe_settings[search_setting]" id="kbe_search_setting" value="1" <?php checked( $settings['search_setting'], '1' ); ?>><?php _e('On','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[search_setting]" id="kbe_search_setting" value="0" <?php checked( $settings['search_setting'], '0' ); ?>><?php _e('Off','kbe'); ?></label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th><?php _e('Display breadcrumbs','kbe'); ?></th>
						<td>
							<fieldset>
								<label><input type="radio" name="kbe_settings[breadcrumbs_setting]" id="kbe_breadcrumbs_setting_1" value="1" <?php checked( $settings['breadcrumbs_setting'], '1' ); ?>><?php _e('On','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[breadcrumbs_setting]" id="kbe_breadcrumbs_setting_0" value="0" <?php checked( $settings['breadcrumbs_setting'], '0' ); ?>><?php _e('Off','kbe'); ?></label>
							</fieldset>
						</td>
						
					</tr>
					<tr>
						<th><?php _e('Archive page sidebar','kbe'); ?></th>
						<td>
							<fieldset>
								<label><input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="1" <?php checked( $settings['sidebar_home'], 1 ); ?>><?php _e('Left','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="2" <?php checked( $settings['sidebar_home'], 2 ); ?>><?php _e('Right','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="0" <?php checked( $settings['sidebar_home'], 0 ); ?>><?php _e('None','kbe'); ?></label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th><?php _e('Article sidebar','kbe'); ?></th>
						<td>
							<fieldset>
								<label><input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="1" <?php checked( $settings['sidebar_inner'], 1 ); ?>><?php _e('Left','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="2" <?php checked( $settings['sidebar_inner'], 2 ); ?>><?php _e('Right','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="0" <?php checked( $settings['sidebar_inner'], 0 ); ?>><?php _e('None','kbe'); ?></label>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th><?php _e('Comments','kbe'); ?></th>
						<td>
							<fieldset>
								<label><input type="radio" name="kbe_settings[comment_setting]" id="kbe_comment_setting" value="1" <?php checked( $settings['comment_setting'], '1' ); ?>><?php _e('On','kbe'); ?></label>
								<label><input type="radio" name="kbe_settings[comment_setting]" id="kbe_comment_setting" value="0" <?php checked( $settings['comment_setting'], '0' ); ?>><?php _e('Off','kbe'); ?></label>
							</fieldset>
							<p class="description">
								<?php _e( 'If yes, comments can still be individually toggled on/off on a per-article basis.', 'kbe' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><label><?php _e('Knowledgebase theme color','kbe'); ?></label></th>
						<td>
							<input type="text" name="kbe_settings[bgcolor]" id="kbe_bgcolor" value="<?php echo esc_attr( $settings['bgcolor'] ); ?>" class="cp-field">
						</td>
					</tr>
					<tr>
						<th><?php _e('Uninstall Mode','kbe'); ?></th>
						<td>
							<fieldset>
								<label><input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_nuclear" value="2" <?php checked( $settings['uninstall_mode'], 2 ); ?>><?php _e('Everything','kbe'); ?></label>
								<label><input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_settings" value="1" <?php checked( $settings['uninstall_mode'], 1 ); ?>><?php _e('Settings Only','kbe'); ?></label>
								<label><input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_none" value="0" <?php checked( $settings['uninstall_mode'], 0 ); ?>><?php _e('Nothing','kbe'); ?></label>
							</fieldset>
							<p class="description">
								<?php printf( __( '%sCaution!%s Everything means everything. All articles and settings will be deleted from the database when the plugin is uninstalled.', 'kbe' ), '<strong>', '</strong>' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" value="<?php _e('Save Changes','kbe'); ?>" name="submit" id="submit" class="button button-primary">
						</td>
					</tr>
				</table>
				</form>
			</div>
			
			<?php include_once( 'kbe_settings_sidebar.php' ); ?>
			
	</div>
</div>