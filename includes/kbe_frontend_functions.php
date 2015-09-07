<?php
/**
 * Knowledgebase Frontend Functions
 *
 * @version     1.1.0
 * @author      Enigma Plugins
 */


/**
 * KBE Enqueue KBE frontend scripts/styles
 * @since  1.0.0
 */ 

//=========> Enqueue KBE frontend scripts/styles
add_action( 'wp_enqueue_scripts', 'kbe_frontend_scripts');
function kbe_frontend_scripts(){
	if( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_style.css' ) ){
		$stylesheet = get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css'; 
	} else {
		$stylesheet = WP_KNOWLEDGEBASE_URL. 'templates/kbe_style.css';
	}
	wp_enqueue_style ( 'kbe_theme_style', $stylesheet, array(), KBE_PLUGIN_VERSION );
	wp_enqueue_script( 'kbe_live_search', WP_KNOWLEDGEBASE_URL.  'js/jquery.livesearch.js', array('jquery'), KBE_PLUGIN_VERSION, true );
}

/**
 * KBE Dynamic CSS
 * @since  1.0.0
 */ 
add_action( 'wp_head', 'count_bg_color' );
function count_bg_color(){ ?>
<style type="text/css">
<?php
    $kbe_bgcolor = get_option('kbe_bgcolor');
?>
    #kbe_content h2 span.kbe_count {
        background-color: <?php echo $kbe_bgcolor; ?> !important;
    }
    #kbe_content .kbe_child_category h3 span.kbe_count {
        background-color: <?php echo $kbe_bgcolor; ?> !important;
    }
    .kbe_widget .kbe_tags_widget a{
        text-decoration: none;
        color: <?php echo $kbe_bgcolor; ?> !important;
    }
    .kbe_widget .kbe_tags_widget a:hover{
        text-decoration: underline;
        color: <?php echo $kbe_bgcolor; ?> !important;
    }
</style>
<?php
}

/**
 * Live Search initialize
 * @since  1.0.0
 */ 
add_action('wp_head', 'st_add_live_search');
function st_add_live_search () {
?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var kbe = jQuery('#live-search #s').val();
            jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=kbe_knowledgebase&s='});
        });
    </script>
<?php
}

/**
 * Load a template.
 *
 * Handles template usage so that we can use our own templates instead of the themes.
 *
 * Templates are in the 'templates' folder. knowledgebase looks for theme
 * overrides in /theme/wp-knowledgebase/ by default
 *
 * @param mixed $template
 * @return string
 */
add_filter('template_include', 'kbe_template_chooser');
function kbe_template_chooser($template){

	global $wp_query;

	$template_path = apply_filters( 'kbe_template_path', 'wp_knowledgebase/' );
	
	$settings = get_option( 'kbe_settings' );
    $archive_page_id = isset( $settings['archive_page_id' ] ) ? $settings['archive_page_id'] : 0;
    
	$find = array();
	$file = '';

	if ( $wp_query->is_search && get_post_type() == 'kbe_knowledgebase' ){

        $file = 'kbe_search.php';
        $find[] = $file;
		$find[] = $template_path . $file;

    } elseif ( is_single() && get_post_type() == 'kbe_knowledgebase' ) {

		$file   = 'single-kbe_knowledgebase.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	} elseif ( is_tax('kbe_taxonomy') || is_tax( 'kbe_tags') ) {

		$term   = get_queried_object();

		if ( is_tax( 'kbe_taxonomy' ) || is_tax( 'kbe_tags' ) ) {
			$file = 'taxonomy-' . $term->taxonomy . '.php';
		} else {
			$file = 'archive.php';
		}

		$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
		$find[] = $template_path . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
		$find[] = 'taxonomy-' . $term->taxonomy . '.php';
		$find[] = $template_path . 'taxonomy-' . $term->taxonomy . '.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	} elseif ( is_post_type_archive( 'kbe_knowledgebase' ) || ( $archive_page_id && is_page( $archive_page_id ) ) ) {

		$file   = 'archive-kbe_knowledgebase.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	}

	if ( $file ) {
		$template       = locate_template( array_unique( $find ) );
		if ( ! $template ) {
			$template = trailingslashit( WP_KNOWLEDGEBASE_PATH ) . 'templates/' . $file;
		}
	}

	return $template;

}

/**
 * Knoweledgebase Shortcode
 * @since  1.0.0
 */ 
function kbe_shortcode( $atts, $content = null ){
    $return_string = require 'template/archive-kbe_knowledgebase.php';
    wp_reset_query();
    return $return_string;
}
add_shortcode('kbe_knowledgebase', 'kbe_shortcode');

/**
 * Order the Custom Taxonomy Terms
 * @since  1.0.0
 */ 
add_filter( 'get_terms_orderby', 'kbe_tax_order', 10, 2);
function kbe_tax_order($orderby, $args){
    $kbe_tax = "kbe_taxonomy";
    
    if($args['orderby'] == 'terms_order'){
        return 't.terms_order';
    }elseif($kbe_tax == 1 && !isset($_GET['orderby'])){
        return 't.terms_order';
    }else{
        return $orderby;
    }
}
