<?php
/**
 * Knowledgebase Template Functions
 *
 * @version     1.1.0
 * @author      Enigma Plugins
 */

//=========> KBE Search Form
function kbe_search_form(){
?>
<!-- #live-search -->
<div id="live-search">
    <div class="kbe_search_field">
        <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
            <input type="text" onfocus="if (this.value == '<?php _e("Search Articles...", "kbe") ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php _e("Search Articles...", "kbe") ?>';}" value="<?php _e("Search Articles...", "kbe") ?>" name="s" id="s" />
            <!--<ul id="kbe_search_dropdown"></ul>-->
            <input type="hidden" name="post_type" value="kbe_knowledgebase" />
        </form>
    </div>
</div>
<!-- /#live-search -->
<?php
}

add_action('wp_head', 'kbe_search_drop');
function kbe_search_drop(){
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#s').keyup(function() {
            jQuery('#search-result').slideDown("slow");
	});
    });
	
    jQuery(document).ready(function(e) {
	jQuery('body').click(function(){
            jQuery('#search-result').slideDown("slow",function(){
		document.body.addEventListener('click', boxCloser, false);
            });
	});
		
	function boxCloser(e){
            if(e.target.id != 's'){
		document.body.removeEventListener('click', boxCloser, false);
		jQuery('#search-result').slideUp("slow");
            }
	}
    });
    
    jQuery(document).ready(function () {
        
        var tree_id = 0;
        jQuery('div.kbe_category:has(.kbe_child_category)').addClass('has-child').prepend('<span class="switch"><img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-plus.png" /></span>').each(function () {
            tree_id++;
            jQuery(this).attr('id', 'tree' + tree_id);
        });

        jQuery('div.kbe_category > span.switch').click(function () {
            var tree_id = jQuery(this).parent().attr('id');
            if (jQuery(this).hasClass('open')) {
                jQuery(this).parent().find('div:first').slideUp('fast');
                jQuery(this).removeClass('open');
                jQuery(this).html('<img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-plus.png" />');
            } else {
                jQuery(this).parent().find('div:first').slideDown('fast');
                jQuery(this).html('<img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-minus.png" />');
                jQuery(this).addClass('open');
            }
        });

    });
</script>
<?php
}

//=========> KBE Plugin Breadcrumbs
function kbe_breadcrumbs(){
    global $post;
    
    $kbe_slug_case = ucwords(strtolower(KBE_PLUGIN_SLUG));
                        
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    if(strpos($url, 'knowledgebase_category') || strpos($url, 'kbe_taxonomy')){
        $kbe_bc_name = get_queried_object()->name;
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, 'kbe_tags') || strpos($url, 'knowledgebase_tags')){
        $kbe_bc_tag_name = get_queried_object()->name;
?>
	<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_tag_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, '?s')){
	$kbe_search_word = $_GET['s'];
?>
	<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_search_word; ?></li>
        </ul>
<?php
    }elseif(is_single()){
        $kbe_bc_term = get_the_terms( $post->ID , KBE_POST_TAXONOMY );
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
        <?php
            foreach($kbe_bc_term as $kbe_tax_term){
        ?>
                <li>
                    <a href="<?php echo get_term_link($kbe_tax_term->slug, KBE_POST_TAXONOMY) ?>">
                        <?php echo $kbe_tax_term->name ?>
                    </a>
                </li>
        <?php
            }
        ?>
            <li>
                <?php
                    if(strlen(the_title('', '', FALSE) >= 50)) {
                        echo substr(the_title('', '', FALSE), 0, 50)."....";
                    } else {
                        the_title();
                    }
                ?>
            </li>
        </ul>
<?php
    }else{
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><?php _e($kbe_slug_case ,'kbe'); ?></li>
        </ul>
<?php
    }
}


//=========> KBE Article Tags
function kbe_show_tags(){
    $kbe_tags_term = get_the_terms( $post->ID , KBE_POST_TAGS );
    if($kbe_tags_term){
?>
    <div class="kbe_tags_div">
        <div class="kbe_tags_icon"></div>
        <ul>
        <?php
            foreach($kbe_tags_term as $kbe_tag){
        ?>
            <li>
                <a href="<?php echo get_term_link($kbe_tag->slug, KBE_POST_TAGS) ?>">
                    <?php echo $kbe_tag->name; ?>
                </a>
            </li>
        <?php
            }
        ?>
        </ul>
    </div>
<?php
    }
}

//=========>  KBE Short Content
function kbe_short_content($limit) {
    $content = get_the_content();
    $pad="&hellip;";
    
    if(strlen($content) <= $limit) {
        return strip_tags($content);
    } else {
        $content = substr($content, 0, $limit) . $pad;
        return strip_tags($content);
    }
}