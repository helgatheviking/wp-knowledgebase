<!--==============
    >> KBE Settings
==============-->
<?php 
$defaults = array( 
    'plugin_slug' => 'Knowledgebase',
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
$settings = wp_parse_args( get_option( 'kbe_settings' ), $defaults );
?>
<div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
            
            <h2><?php _e('Knowledgebase Display Settings','kbe')?></h2>
            <?php settings_errors('kbe_settings');            ?>
            <div class="kbe_admin_left_bar">
                <div class="kbe_admin_left_content">
                    <div class="kbe_admin_left_heading">
                        <h3><?php _e('Settings','kbe'); ?></h3>
                    </div>
                    <div class="kbe_admin_settings">
                        <form method="post" action="options.php">
                        <?php  
                            settings_fields('kbe_settings');
                        ?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 18px;">
                            <tr>
                                <td valign="top">
                                    <label><?php _e( 'Knowledgebase Archive','kbe' ); ?></label>
                                </td>
                                <td colspan="3">
                                    <?php 
                                    $dropdown_args = array( 'show_option_none' => __( '--Select the Knowledgebase Archive--' ), 
                                                            'name'=>'kbe_settings[archive_page_id]',
                                                            'id' => 'kbe_archive_page_id',
                                                            'selected' => $settings['archive_page_id'] );

                                    wp_dropdown_pages( $dropdown_args );
                                    ?> 
                                <p>
                                    <strong><?php _e('Note:','kbe'); ?></strong>
                                    <?php printf( __( 'The base page can also be used in your <a href="%s">Knowledgebase permalinks</a>.', 'kbe' ), admin_url( 'options-permalink.php' ) ); ?>
                                </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Number of articles to show','kbe'); ?></label>
                                </td>
                                <td colspan="3">
                                    <input type="text" name="kbe_settings[article_qty]" id="kbe_article_qty" value="<?php echo esc_attr( $settings['article_qty'] ); ?>">
                                <p>
                                    <strong><?php _e('Note:','kbe'); ?></strong>
                                    <?php _e('Set the number of articles to show in each category on KB homepage','kbe'); ?>
                                </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase search','kbe'); ?></label>
                                </td>
                                <td width="15%">
                                    <input type="radio" name="kbe_settings[search_setting]" id="kbe_search_setting" value="1" <?php checked( $settings['search_setting'], '1' ); ?>>
                                    <span><?php _e('On','kbe'); ?></span>
                                </td>
                                <td width="15%">
                                    <input type="radio" name="kbe_settings[search_setting]" id="kbe_search_setting" value="0" <?php checked( $settings['search_setting'], '0' ); ?>>
                                    <span><?php _e('Off','kbe'); ?></span>
                                </td>
                                <td width="45%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase breadcrumbs','kbe'); ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[breadcrumb_setting]" id="kbe_breadcrumb_setting" value="1" <?php checked( $settings['breadcrumb_setting'], '1' ); ?>>
                                    <span><?php _e('On','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[breadcrumb_setting]" id="kbe_breadcrumb_setting" value="0" <?php checked( $settings['breadcrumb_setting'], '0' ); ?>>
                                    <span><?php _e('Off','kbe'); ?></span>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase home page sidebar','kbe'); ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="1" <?php checked( $settings['sidebar_home'], 1 ); ?>>
                                    <span><?php _e('Left','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="2" <?php checked( $settings['sidebar_home'], 2 ); ?>>
                                    <span><?php _e('Right','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_home]" id="kbe_sidebar_home" value="0" <?php checked( $settings['sidebar_home'], 0 ); ?>>
                                    <span><?php _e('None','kbe'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase inner pages sidebar','kbe'); ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="1" <?php checked( $settings['sidebar_inner'], 1 ); ?>>
                                    <span><?php _e('Left','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="2" <?php checked( $settings['sidebar_inner'], 2 ); ?>>
                                    <span><?php _e('Right','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[sidebar_inner]" id="kbe_sidebar_inner" value="0" <?php checked( $settings['sidebar_inner'], 0 ); ?>>
                                    <span><?php _e('None','kbe'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase comments','kbe'); ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[comment_setting]" id="kbe_comment_setting" value="1" <?php checked( $settings['comment_setting'], '1' ); ?>>
                                    <span><?php _e('On','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_settings[comment_setting]" id="kbe_comment_setting" value="0" <?php checked( $settings['comment_setting'], '0' ); ?>>
                                    <span><?php _e('Off','kbe'); ?></span>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Knowledgebase theme color','kbe'); ?></label>
                                </td>
                                <td colspan="3">
                                    <input type="text" name="kbe_settings[bgcolor]" id="kbe_bgcolor" value="<?php echo esc_attr( $settings['bgcolor'] ); ?>" class="cp-field">
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <label><?php _e('Uninstall Mode','kbe'); ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_nuclear" value="2" <?php checked( $settings['uninstall_mode'], 2 ); ?>>
                                    <span><?php _e('EVERYTHING','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_settings" value="1" <?php checked( $settings['uninstall_mode'], 1 ); ?>>
                                    <span><?php _e('Settings Only','kbe'); ?></span>
                                </td>
                                <td>
                                    <input type="radio" name="kbe_uninstall_settings" id="kbe_uninstall_none" value="0" <?php checked( $settings['uninstall_mode'], 0 ); ?>>
                                    <span><?php _e('Nothing','kbe'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right" style="border:0px;">
                                    <input type="submit" value="<?php _e('Save Changes','kbe'); ?>" name="submit" id="submit">
                                </td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="kbe_admin_sidebar">
            <table cellpadding="0" class="widefat donation" style="margin-bottom:10px; border:solid 2px #008001;" width="50%">
                <thead>
                    <th scope="col">
                        <strong style="color:#008001;"><?php _e('Help Improve This Plugin!','kbe') ?></strong>
                    </th>
                </thead>
      		<tbody>
                    <tr>
                        <td style="border:0;">
                            <?php _e('Enjoyed this plugin? All donations are used to improve and further develop this plugin. Thanks for your contribution.','kbe') ?>
                        </td>
                    </tr>
                    <tr>
          		<td style="border:0;">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="A74K2K689DWTY">
                            <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
            		</form>
                  	</td>
                    </tr>
                    <tr>
          		<td style="border:0;"><?php _e('you can also help by','kbe') ?>
                            <a href="http://wordpress.org/support/view/plugin-reviews/wp-knowledgebase" target="_blank">
                                <?php _e('rating this plugin on wordpress.org','kbe') ?>
                            </a>
                      	</td>
                    </tr>
                </tbody>
            </table>
                
            <table cellpadding="0" class="widefat" border="0">
                <thead>
                    <th scope="col"><?php _e('Need Support?','kbe') ?></th>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:0;">
                            <?php _e('Check out the','kbe') ?>
                            <a href="http://wordpress.org/plugins/wp-knowledgebase/faq" target="_blank"><?php _e('FAQs','kbe'); ?></a>
                            <?php _e('and','kbe') ?>
                            <a href="http://wordpress.org/support/plugin/wp-knowledgebase" target="_blank"><?php _e('Support Forums','kbe') ?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            
        </div>
    </div>
</div>