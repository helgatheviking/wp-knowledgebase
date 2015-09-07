<?php
/**
 * Knowledgebase Terms and Article Re-ordering
 *
 * @version     1.0.0
 * @author      Enigma Plugins
 */
?>
<div id="wpbody">
	<div id="wpbody-content">
		<div class="wrap">
			
			<h2><?php _e('Re-Order Categories and Articles','kbe')?></h2>

			<div id="message" class="updated" style="display: none"></div>
			
			<div class="kbe_admin_settings kbe_admin_left_content">
				<!--=============== Re Order Catgories ===============-->
				<h3><?php _e('Category Order','kbe'); ?></h3>
		
				<form name="custom_order_form" method="post" action="">
				<?php wp_nonce_field( 'kbe_order_nonce', 'kbe_order_nonce' ); ?>
					<?php
					$kbe_parent_ID = 0;
					$kbe_args = array(
						'orderby' => 'terms_order',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => $kbe_parent_ID
					);

					$kbe_terms = get_terms('kbe_taxonomy', $kbe_args);
					if($kbe_terms){
					?>
						<p><?php _e('Drag and drop items to customise the order of categories in WP Knowledgebase','kbe') ?></p>
							
						<ul id="kbe_order_sortable" class="kbe_admin_order">
						<?php foreach($kbe_terms as $kbe_term) : ?>
							<li data-id="<?php echo $kbe_term->term_id; ?>" class="lineitem ui-state-default"><?php echo $kbe_term->name; ?></li>
						<?php endforeach; ?>
						
						</ul>
						
						<input type="hidden" name="kbe_category_custom_order" class="custom_order" />
						<input type="submit" name="kbe_order_submit" class="kbe-reorder-submit button-primary" value="<?php _e('Save Order', 'kbe') ?>" data-type="category"/>
						<div class="spinner"></div>
						<?php
					}else{
						?>
						<p>
							<?php _e('No terms found', 'kbe'); ?>
						</p>
					<?php
						}
					?>
				</form>

			
				<!--=============== Re Order Articles ===============-->
				<h3><?php _e('Article Order','kbe'); ?></h3>
				<form name="custom_order_form" method="post" action="">
					<?php
					$kbe_article_args = new WP_Query(array(
													'post_type' => 'kbe_knowledgebase',
													'order'     => 'ASC',
													'orderby'   => 'menu_order',
													'nopaging'  => true,
												));
					if($kbe_article_args->have_posts()){
						?>
					<p><?php _e('Drag and drop items to customize the order of articles in WP Knowledgebase','kbe') ?></p>
							
					<ul id="kbe_article_sortable" class="kbe_admin_order">
						<?php $i = 1;
							while($kbe_article_args->have_posts()) :
								$kbe_article_args->the_post();
						?>
								<li data-id="<?php the_ID(); ?>" class="lineitem"><?php the_title(); ?></li>
						<?php $i++;
							endwhile;
						?>
						</ul>
						<input type="hidden" id="kbe_article_custom_order" name="kbe_article_custom_order" class="custom_order" />
						<input type="submit" name="kbe_article_submit" class="kbe-reorder-submit button-primary" value="<?php _e('Save Order', 'kbe') ?>" data-type="article"/>
						<div class="spinner"></div>						
					<?php
						}else{
					?>
						<p>
							<?php _e('No Articles found', 'kbe'); ?>
						</p>
				<?php
					}
				?>
				</form>

			</div>
			
			<?php include_once( 'kbe_settings_sidebar.php' ); ?>        
			
	</div>
	</div>
</div>