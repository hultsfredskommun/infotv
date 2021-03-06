<?php
	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */


	require_once( 'external/starkers-utilities.php' );
	require_once( 'acf-include.php' );

	


	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

	add_theme_support('post-thumbnails');
	
	// register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'script_enqueuer' );

	add_filter( 'body_class', 'add_slug_to_body_class' );

	/* ========================================================================================================================
	
	Custom Post Types - include custom post types and taxonimies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );
	
	======================================================================================================================== */



	/* ========================================================================================================================
	
	Scripts
	
	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */

	function script_enqueuer() {


		wp_register_script( 'transit', get_template_directory_uri().'/js/jquery.transit.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'transit' );

		wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ) );
		wp_enqueue_script( 'site' );
		$data = array(
			'site_url' => site_url(),
			'admin_ajax_url' => admin_url('admin-ajax.php'));
		wp_localize_script('site', 'infotv_data', $data);

		wp_register_style( 'screen', get_template_directory_uri().'/style.css', '', '', 'screen' );
        wp_enqueue_style( 'screen' );
		

	}	


	/*
		Removes the version on the script and the css files
	*/
	function control_wp_url_versioning($src)
	{
	    // $src is the URL that WP has generated for the script or stlye you added 
	    // with wp_enqueue_script() or wp_enqueue_style(). This function currently 
	    // removes the version string off *all* scripts. If you need to do something 
	    // different, then you should do it here.
	    $src = remove_query_arg( 'ver', $src );
	    return $src;
	}

	// The default script priority is 10. We load these filters with priority 15 to 
	// ensure they are run *after* all the default filters have run. 
	add_filter('script_loader_src', 'control_wp_url_versioning', 15); 
	add_filter('style_loader_src', 'control_wp_url_versioning', 15); 



	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	function starkers_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>	
		<li>
			<article id="comment-<?php comment_ID() ?>">
				<?php echo get_avatar( $comment ); ?>
				<h4><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</article>
		<?php endif;
	}


/*
add_action( 'edit_post', 'w3_flush_page_custom', 10, 1 );
function w3_flush_page_custom( $post_id ) {
	if (isset($w3_plugin_totalcache)) {
		if ( 'slide' == get_post_type( $post_id ) || 'slide_settings' == get_post_type( $post_id ) )
			$w3_plugin_totalcache->flush_pgcache();
	}
} 
*/


add_action( 'admin_init', 'register_settings' );
function register_settings() {
	register_setting( 'infotv-settings', 'count' );
}

add_action( 'init', 'create_slide_cms_post_types' );

function create_slide_cms_post_types() {
	register_post_type( 'slide',
		array(
			'labels' => array(
			'name' => __( 'Sidor' ),
			'singular_name' => __( 'sida' ),
			'add_new' => __( 'Lägg till ny' ),
			'add_new_item' => __( 'Lägg till ny sida' ),
			'edit' => __( 'Ändra' ),
			'edit_item' => __( 'Ändra sida' ),
			'new_item' => __( 'Ny sida' ),
			'view' => __( 'Se sida' ),
			'view_item' => __( 'Se sida' ),
			'search_items' => __( 'Sök sida' ),
			'not_found' => __( 'Inga sidor hittades' ),
			'not_found_in_trash' => __( 'Inga sidor i papperskorgen' ),
			'parent' => __( 'Förälder till sida' ),
			),
			'public' => true,
			'query_var' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
			//'rewrite' => false 
			'rewrite' => array( 'slug' => 'slide', 'with_front' => true )
		)
	);
	register_post_type( 'slide_settings',
		array(
			'labels' => array(
			'name' => __( 'Inställningssidor' ),
			'singular_name' => __( 'Inställningssida' ),
			'add_new' => __( 'Lägg till ny' ),
			'add_new_item' => __( 'Lägg till ny inställningssida' ),
			'edit' => __( 'Ändra' ),
			'edit_item' => __( 'Ändra inställningssida' ),
			'new_item' => __( 'Ny inställningssida' ),
			'view' => __( 'Se inställningssida' ),
			'view_item' => __( 'Se inställningssida' ),
			'search_items' => __( 'Sök inställningssidor' ),
			'not_found' => __( 'Inga inställningssidor hittades' ),
			'not_found_in_trash' => __( 'Inga inställningssidor i papperskorgen' ),
			'parent' => __( 'Förälder till inställningssida' ),
			),
			'public' => true,
			'query_var' => true,
			'has_archive' => true,
			//'rewrite' => false 
			'rewrite' => array( 'slug' => 'settings', 'with_front' => true )
		)
	);

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Skärmens plats', 'taxonomy general name' ),
		'singular_name' => _x( 'Skärmens plats', 'taxonomy singular name' ),
		'search_items' =>  __( 'Sök efter plats' ),
		'popular_items' => __( 'Populära platser' ),
		'all_items' => __( 'Alla platser' ),
		'parent_item' => __( 'Förälder plats' ),
		'parent_item_colon' => __( 'Förälder plats:' ),
		'edit_item' => __( 'Ändra plats' ), 
		'update_item' => __( 'Uppdatera plats' ),
		'add_new_item' => __( 'Lägg till plats' ),
		'new_item_name' => __( 'Ny plats' ),
		'menu_name' => __( 'Plats' ),
	); 

	register_taxonomy('place',array('slide','slide_settings'),array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'platser' ),
	));
}

add_filter( 'manage_edit-slide_columns', 'my_edit_slide_columns' ) ;

function my_edit_slide_columns( $columns ) {

  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Namn' ),
    'place' => __( 'Skärm' ),
    'from' => __('Visas från'),
    'to' => __('Visas till'),
    'date' => __( 'Datum' )
  );

  return $columns;
}


add_action( 'manage_slide_posts_custom_column', 'my_manage_slide_columns', 10, 2 );

function my_manage_slide_columns( $column, $post_id ) {
  global $post;


  $schedule_mode = get_field('time_settings_choise', $post_id);

   if ( empty( $schedule_mode ) )
   {
   	$schedule_mode = "no";
   }

  switch( $column ) {


/* If displaying the 'annr' column. */
    
    /* If displaying the 'raanr' column. */
    case 'from' :

		/* Get the post meta. */
	  	//$from = get_post_meta( $post_id, 'from', true );
		$from = infotv_get_field_date( 'from', $post_id );
		
		if ( empty( $from ) )
			echo __( '-' );
		else
			echo $from;
		break;

	case 'to' :

		/* Get the post meta. */
	  	//$to = get_post_meta( $post_id, 'to', true );
		$to = infotv_get_field_date( 'to', $post_id );
	  
		if ( empty( $to ) )
			echo __( '-' );
		else
			echo $to;

		break;


      /* If displaying the 'place' column. */
    case 'place' :

      /* Get the genres for the post. */
      $terms = get_the_terms( $post_id, 'place' );

      /* If terms were found. */
      if ( !empty( $terms ) ) {

        $out = array();

        /* Loop through each term, linking to the 'edit posts' page for the specific term. */
        foreach ( $terms as $term ) {
          $out[] = sprintf( '<a href="%s">%s</a>',
            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'place' => $term->slug ), 'edit.php' ) ),
            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'place', 'display' ) )
          );
        }

        /* Join the terms, separating them with a comma. */
        echo join( ', ', $out );
      }

      /* If no terms were found, output a default message. */
      else {
        _e( '-' );
      }

      break;

    /* Just break out of the switch statement for everything else. */
    default :
      break;
  }
}

add_filter( 'manage_edit-slide_sortable_columns', 'my_slide_sortable_columns' );

function my_slide_sortable_columns( $columns ) {

  $columns['to'] = 'to'; 
  $columns['from'] = 'from';

  return $columns;
}


function is_time24($val) 
{ 
	return (bool)preg_match("/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/", $val); 
} 

function get_image($image_id, $image_mode)
{

	$image_size_name = "";
	switch ($image_mode) {
		case 'normal-left':
			$image_size_name = "large";
			break;
		case 'normal-right':
			$image_size_name = "large";
			break;
		case 'half-left':
			$image_size_name = "half";
			break;
		case 'half-right':
			$image_size_name = "half";
			break;
		case 'whole':
			$image_size_name = "whole";
			break;
		default:
			return ""; // break and return empty
	}
	// if normal
	if ($image_size_name == "large") {
		$image = wp_get_attachment_image( $image_id, $image_size_name );
		if ($image != "")
			return "<div class='slide_image $image_mode $image_size_name'>$image</div>";
		
		// else try smaller size
		$image_size_name = "medium";
		$image = wp_get_attachment_image( $image_id, $image_size_name );
		if ($image != "")
			return "<div class='slide_image $image_mode $image_size_name'>$image</div>";
	}
	else { // if half or whole
		$image = wp_get_attachment_image( $image_id, $image_size_name );
		if ($image != "")
			return "<div class='slide_image $image_mode $image_size_name'>$image</div>";
		
		// else try smaller size
		$image_size_name .= "2";
		$image = wp_get_attachment_image( $image_id, $image_size_name );
		if ($image != "")
			return "<div class='slide_image $image_mode $image_size_name'>$image</div>";
	}
	$image = wp_get_attachment_image( $image_id, $image_size_name );
	if ($image != "")
		return "<div class='slide_image $image_mode $image_size_name'>$image</div>";

	return "";
}

function edit_admin_menus() {  
	global $menu;  
	global $submenu;  
	
	remove_menu_page('edit.php'); // Remove the post menu
	remove_menu_page('link-manager.php'); // Remove the link manager menu
	remove_menu_page('edit.php?post_type=page'); // Remove the page menu
	remove_menu_page('edit-comments.php'); // Remove the comments Menu
}  
add_action( 'admin_menu', 'edit_admin_menus' );  

function custom_menu_order($menu_ord) {  
	if (!$menu_ord) return true;  
	return array(  
		'index.php', // Dashboard  
		'separator1', // First separator  
		'edit.php?post_type=slide',
		'edit.php?post_type=slide_settings',
		'edit.php', // Posts  
		'upload.php', // Media  
		'link-manager.php', // Links  
		'edit.php?post_type=page', // Pages  
		'edit-comments.php', // Comments  
		'separator2', // Third separator 
		'themes.php', // Appearance
		
		'plugins.php', // Plugins  
		'users.php', // Users  
		'tools.php', // Tools  
		'options-general.php', // Settings  
		'separator-last', // Last separator  
	);  
} 
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order  
add_filter('menu_order', 'custom_menu_order');

add_image_size( 'half',960,1080,true ); // Half size
add_image_size( 'whole',1920,1080,true ); // Full size
add_image_size( 'half2',512,768,true ); // Half size
add_image_size( 'whole2',1024,768,true ); // Full size

// shortcodes
function nofullscreenvideo_func( $atts ){
	$retValue = "<div class='hidden nofullscreenvideo'></div>";
	return $retValue;
}
add_shortcode( 'normalvideostorlek', 'nofullscreenvideo_func' );
function playaudio_func( $atts ){
	$retValue = "<div class='hidden playaudio'></div>";
	return $retValue;
}
add_shortcode( 'spelaljud', 'playaudio_func' );
function sql_func( $atts ){
	extract( shortcode_atts( array(
		'host' => '',
		'db' => '',
		'pwd' => '',
		'user' => '',
		'query' => '',
		'connectionstring' => '',
		'noresults' => 'Inget planerat idag.',
		'fontsize' => '150%',
		'padding' => '10px',
		'convertfrom' => 'UTF-8',
		'convertto' => 'ISO-8859-1'
	), $atts, 'sql' ) );

	$retValue = "<div class='hidden sql'></div>";
	
	if ($host == "" || $user == "" || $db == "" || $pwd == "" || $query == "")
		return $retValue . '<div class="error">Kan inte kontakta databasen utan r&auml;tt uppgifter.</div>';

	if (!function_exists("mssql_connect"))
		return $retValue . '<div class="error">Det m&aring;ste finnas mssql i PHP f&ouml;r att st&auml;lla fr&aring;gan.</div>';
	
	// try to connect
	$link = mssql_connect($host, $user, $pwd);
	if (!$link) {
		return $retValue . '<div class="error">Kunde inte kontakta databasen. Fel: ' . mssql_get_last_message() . '</div>';
	}

	mssql_select_db($db);
	
	// do the search
	$count = 1;
	$query = str_replace(array(";","[","]","{","}"),"",$query);
	$result = mssql_query($query);
	
	if (!$result || mssql_num_rows($result) == 0) {
		$retValue .= $noresults;
	}
	else
	{	
		//echo table
		echo "<table style='font-size: $fontsize'>";
		while ($row = mssql_fetch_assoc($result)) {
			echo "<tr>";
			foreach ($row as $key => $col) {
				echo "<td style='padding: $padding'>" . mb_convert_encoding($col, $convertfrom, $convertto) . "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}	
	mssql_close($link);
	


	
	return $retValue;
}
add_shortcode( 'sql', 'sql_func' );


add_action('admin_init', 'infotv_options_init' );
add_action('admin_menu', 'infotv_options_add_page');

// Init plugin options to white list our options
function infotv_options_init(){
	register_setting( 'infotv_options_options', 'infotv', 'infotv_options_validate' );
}

// Add menu page
function infotv_options_add_page() {
	add_theme_page('InfoTV Inst&auml;llningar', 'InfoTV Inst&auml;llningar', 'manage_options', 'infotv_options', 'infotv_options_do_page');
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function infotv_options_validate($input) {
	// Our first value is either 0 or 1
	//$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	
	// Say our second option must be safe text with no HTML tags
	//$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
	
	return $input;
}
// Draw the menu page itself
function infotv_options_do_page() {
	?>
	<div class="wrap">
		<h1>Inställningar för temat InfoTV</h1>
		<form id="form_hk_options" method="post" action="options.php">
			<?php settings_fields('infotv_options_options'); ?>
			<?php $options = get_option('infotv'); ?>
	
			<div class="wrap"><div id="icon-tools" class="icon32"></div>
			<h2>Vilka har anslutit?</h2><table style="margin-top:24px;border: 1px solid gray; border-spacing:10px;">
			<tr>
				<td style='display:none'><b>key</b></td>
				<td><b>plats</b></td>
				<td><b>skärmstorlek</b></td>
				<td><b>webbläsare</b></td>
				<td><b>datum</b></td>
				<td><b>uppdateringar</b></td>
			</tr>
			<?php if (!empty($options["count"])) 
			foreach ($options["count"] as $key => $arr) {
				echo "<tr><td style='display:none'>" . $key . "</td>".
				"<td>" . $arr["plats"] . "</td>".
				"<td>" . $arr["screensize"] . "</td>".
				"<td>" . $arr["browser"] . "</td>".
				"<td>" . $arr["date"] . "</td>".
				"<td>" . $arr["count"] . "</td>".
				"</tr>";
			}?>
	</table></div>
	<?php submit_button("Rensa"); ?>
	</form>
<?php }

//wp_enqueue_script('jquery');


function infotv_count_func(){
	$options = get_option('infotv');
	if ($options == null || $options == "") {
		$options["count"] = Array();
	}
	$count = $options["count"];
	if (!is_array($count))
		$count = Array();
	$key = $_REQUEST["unique"];

	if ($count[$key] != "")
		$count[$key] = Array("plats" => $_REQUEST["plats"], "browser" => $_REQUEST["browser"], "screensize" => $_REQUEST['screensize'], "date" => $_REQUEST["date"], "count" => ++$count[$key]["count"]);
	else
		$count[$key] = Array("plats" => $_REQUEST["plats"], "browser" => $_REQUEST["browser"], "screensize" => $_REQUEST['screensize'], "date" => $_REQUEST["date"], "count" => 1);
	
	$options["count"] = $count;
	update_option("infotv",$options);
	echo "<div class='hidden'>";
	print_r($_REQUEST);
	echo "</div>";
	echo "$key counted";
	die();
	
}
add_action('wp_ajax_infotv_count', 'infotv_count_func'); 
add_action('wp_ajax_nopriv_infotv_count', 'infotv_count_func'); 
	
	
add_filter('tiny_mce_before_init', 'infotv_tiny_mce_remove_unused_formats' );
/*
 * Modify TinyMCE editor to remove H1.
 */
function infotv_tiny_mce_remove_unused_formats($init) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 4=h4';
	return $init;
}

/* helper function to handle compability when ACF date picker change 20160621 
   field from was replaced with from2 and to with to2 */
function infotv_get_field_date($field,$post_id) {
	$slide_from = get_field($field.'2',$post_id);
	if (empty($slide_from)) {
		$slide_from = intval(get_post_meta( $post_id, $field, true ));
		$slide_from = Date("Y-m-d H:i",$slide_from);
	}
	return $slide_from;
}


/* Register meta box. */
function infotv_register_meta_boxes() {
    add_meta_box( 'meta-box-id', 'Gammal tidsinställning', 'infotv_display_date_callback', null, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'infotv_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function infotv_display_date_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
	
	$gammal = $ny = "";
	$field = "from";
	$slide_from = get_field($field.'2',$post->ID);
	if (empty($slide_from)) {
		$slide_from = get_post_meta( $post->ID, $field, true );
		$slide_from = Date("Y-m-d H:i",$slide_from);
		if (!empty($slide_from)) {
			$gammal .= "Du har en satt en från-tid med gamla tidsinställningen: <b>" . $slide_from . "</b>.<br>\n";
		}
	}
	else {
		$ny .= "Du har en satt en från-tid med nya tidsinställningen nedan.<br>\n";
	}
	
	$field = "to";
	$slide_to = get_field($field.'2',$post->ID);
	if (empty($slide_to)) {
		$slide_to = get_post_meta( $post->ID, $field, true );
		$slide_to = Date("Y-m-d H:i",$slide_to);
		if (!empty($slide_to)) {
			$gammal .= "Du har en satt en till-tid med gamla tidsinställningen: <b>" . $slide_to . "</b>.<br>\n";
		}
	}
	else {
		$ny .= "Du har en satt en till-tid med nya tidsinställningen nedan.<br>\n";
	}

	if (empty($gammal)) {
		//echo "$ny<br>\n";
		echo "Det är nu en ny version av tidsinställning. Se Datuminställningar nedan. Gamla inställningar fungerar fortfarande och visas här när det finns någon sådan satt.<br>\n";
	}
	else {
		echo "Det är nu en ny version av tidsinställning. Inställningar som gjordes tidigare fungerar fortfarande men syns inte i rätt i inställningarna nedan. <br>\n<br>\n$gammal<br>\nNär du sätter tid med den nya inställningen så försvinner automatiskt det gamla som är satt.<br>\n";
	}
}
