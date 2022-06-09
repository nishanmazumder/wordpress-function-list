This functions are only for my personal note. Copy form - Wpbeginner (http://www.wpbeginner.com/wp-tutorials/25-extremely-useful-tricks-for-the-wordpress-functions-file/)

<?php

// Custom Mime Types - fonts
function nm_allow_font_mime_types($mimes) {
  $mimes['eot']   = 'application/vnd.ms-fontobject';
  $mimes['woff']  = 'application/font-woff'; 
  $mimes['otf'] = 'application/font-sfnt';
  $mimes['svg']   = 'image/svg+xml';

  return $mimes;
}
add_filter('upload_mimes', 'nm_allow_font_mime_types'); 


function import_scripts_and_styles() {
// To enqueue style.css
wp_enqueue_style( ‘style.css’, get_stylesheet_directory_uri() . ‘/assets/css/style.css’, array(), time(), false );
// To enqueue custom-script.js
wp_enqueue_script( ‘custom-js’, get_stylesheet_directory_uri() . ‘/assets/js/custom-script.js’, array(), ”, true );
}
add_action(‘wp_enqueue_scripts’, ‘import_scripts_and_styles’);


add_action('wp_enqueue_scripts', 'forex_calculator');
function forex_calculator() {
    wp_register_style( 'bootcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' );
    wp_enqueue_style( 'bootcss' );
    wp_enqueue_script( 'bootjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js', array( 'jquery' ) );
}


//Customizr Api

function nm_customize_setup($wp_customize)
{
    
	$wp_customize->add_setting('nm_term_text');
	
	$wp_customize->add_section('nm_terms_section', [
		'title' => 'Extra Terms & condition ',
		'priority' => 1
	]); 
	
	$wp_customize->add_control('mys_header_title_ctrl', [
        'label' => 'Extra condition',
        'section' => 'nm_terms_section',
        'settings' => 'nm_term_text',
        'type' => 'text'
    ]); 
	
	
}
add_action('customize_register', 'nm_customize_setup'); 


// Remove WordPress Version Number

function wpb_remove_version() {
return '';
}
add_filter('the_generator', 'wpb_remove_version');

// Background Image set for Inner page

<?php $nmbackgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>
          
  <div class="header-wrap" style="background: url('<?php echo $nmbackgroundImg[0]; ?>') no-repeat;">
     <header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
     </header>
  </div>  

// Add a Custom Dashboard Logo

function wpb_custom_logo() {
echo '
<style type="text/css">
#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
background-image: url(' . get_bloginfo('stylesheet_directory') . '/images/custom-logo.png) !important;
background-position: 0 0;
color:rgba(0, 0, 0, 0);
}
#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
background-position: 0 0;
}
</style>
';
}
//hook into the administrative header output
add_action('wp_before_admin_bar_render', 'wpb_custom_logo');


// Change the Footer in WordPress Admin Panel

function remove_footer_admin () {

echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | WordPress Tutorials: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
  
}
add_filter('admin_footer_text', 'remove_footer_admin');


// Custom Dashboard Widgets

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
}

function custom_dashboard_help() {
echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
}


// Change the Default Gravatar in WordPress

add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
$myavatar = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}

// Dynamic Copyright Date in Footer

function wpb_copyright() {
global $wpdb;
$copyright_dates = $wpdb->get_results("
SELECT
YEAR(min(post_date_gmt)) AS firstdate,
YEAR(max(post_date_gmt)) AS lastdate
FROM
$wpdb->posts
WHERE
post_status = 'publish'
");
$output = '';
if($copyright_dates) {
$copyright = "© " . $copyright_dates[0]->firstdate;
if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
$copyright .= '-' . $copyright_dates[0]->lastdate;
}
$output = $copyright;
}
return $output;
}


// Randomly Change Background Color

function wpb_bg() { 
$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
$color ='#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].
$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
echo $color;
}

<body <?php body_class(); ?> style="background-color:<?php wpb_bg();?>">>


// Update WordPress URLs

update_option( 'siteurl', 'http://example.com' );
update_option( 'home', 'http://example.com' );


// Add Additional Image Sizes in WordPress

add_image_size( 'sidebar-thumb', 120, 120, true ); // Hard Crop Mode
add_image_size( 'homepage-thumb', 220, 180 ); // Soft Crop Mode
add_image_size( 'singlepost-thumb', 590, 9999 ); // Unlimited Height Mode


<?php the_post_thumbnail( 'homepage-thumb' ); ?>


// Add New Navigation Menus to Your Theme
<?php
function wpb_custom_new_menu() {
  register_nav_menu('my-custom-menu',__( 'My Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );

<?php
wp_nav_menu( array( 
    'theme_location' => 'my-custom-menu', 
    'container_class' => 'custom-menu-class' ) ); 



// Add Author Profile Fields

function wpb_new_contactmethods( $contactmethods ) {
// Add Twitter
$contactmethods['twitter'] = 'Twitter';
//add Facebook
$contactmethods['facebook'] = 'Facebook';

return $contactmethods;
}
add_filter('user_contactmethods','wpb_new_contactmethods',10,1);

<?php echo $curauth->twitter;


// Adding Widget  Areas or Sidebar

// Register Sidebars
function custom_sidebars() {

	$args = array(
		'id'            => 'custom_sidebar',
		'name'          => __( 'Custom Widget Area', 'text_domain' ),
		'description'   => __( 'A custom widget area', 'text_domain' ),
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $args );

}
add_action( 'widgets_init', 'custom_sidebars' );


<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('custom_sidebar') ) : ?>
<!–Default sidebar info goes here–>
<?php endif;


// Hide Login Errors

function no_wordpress_errors(){
  return 'Something is wrong!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


// Disable Login by Email

remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );


// Disable Search Feature

function fb_filter_query( $query, $error = true ) {

if ( is_search() ) {
$query->is_search = false;
$query->query_vars[s] = false;
$query->query[s] = false;

// to error
if ( $error == true )
$query->is_404 = true;
}
}

add_action( 'parse_query', 'fb_filter_query' );
add_filter( 'get_search_form', create_function( '$a', "return null;" ) );


// Change Read More Text for Excerpts

function modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">Your Read More Link Text</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );


// Change Excerpt Length

functionnew_excerpt_length($length) {
return 100;
}
add_filter('excerpt_length', 'new_excerpt_length');


// Add an Admin User

function wpb_admin_account(){
$user = 'Username';
$pass = 'Password';
$email = 'email@domain.com';
if ( !username_exists( $user )  && !email_exists( $email ) ) {
$user_id = wp_create_user( $user, $pass, $email );
$user = new WP_User( $user_id );
$user->set_role( 'administrator' );
} }
add_action('init','wpb_admin_account');


// Remove Welcome Panel

remove_action('welcome_panel', 'wp_welcome_panel');



// Show Total Number of Registered Users

// Function to return user count
function wpb_user_count() { 
$usercount = count_users();
$result = $usercount['total_users']; 
return $result; 
} 
// Creating a shortcode to display user count
add_shortcode('user_count', 'wpb_user_count');



// Add Odd and Even CSS style

function oddeven_post_class ( $classes ) {
   global $current_class;
   $classes[] = $current_class;
   $current_class = ($current_class == 'odd') ? 'even' : 'odd';
   return $classes;
}
add_filter ( 'post_class' , 'oddeven_post_class' );
global $current_class;
$current_class = 'odd';

//style
.even {
background:#f0f8ff;  
} 
.odd {
 background:#f4f4fb;
}

//  Remove Default Image Links

function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);




// Add an Author Info Box in WordPress Posts

function wpb_author_info_box( $content ) {

global $post;

// Detect if it is a single post with a post author
if ( is_single() && isset( $post->post_author ) ) {

// Get author's display name 
$display_name = get_the_author_meta( 'display_name', $post->post_author );

// If display name is not available then use nickname as display name
if ( empty( $display_name ) )
$display_name = get_the_author_meta( 'nickname', $post->post_author );

// Get author's biographical information or description
$user_description = get_the_author_meta( 'user_description', $post->post_author );

// Get author's website URL 
$user_website = get_the_author_meta('url', $post->post_author);

// Get link to the author archive page
$user_posts = get_author_posts_url( get_the_author_meta( 'ID' , $post->post_author));
 
if ( ! empty( $display_name ) )

$author_details = '<p class="author_name">About ' . $display_name . '</p>';

if ( ! empty( $user_description ) )
// Author avatar and bio

$author_details .= '<p class="author_details">' . get_avatar( get_the_author_meta('user_email') , 90 ) . nl2br( $user_description ). '</p>';

$author_details .= '<p class="author_links"><a href="'. $user_posts .'">View all posts by ' . $display_name . '</a>';  

// Check if author has a website in their profile
if ( ! empty( $user_website ) ) {

// Display author website link
$author_details .= ' | <a href="' . $user_website .'" target="_blank" rel="nofollow">Website</a></p>';

} else { 
// if there is no author website then just close the paragraph
$author_details .= '</p>';
}

// Pass all this info to post content  
$content = $content . '<footer class="author_bio_section" >' . $author_details . '</footer>';
}
return $content;
}

// Add our function to the post content filter 
add_action( 'the_content', 'wpb_author_info_box' );

// Allow HTML in author bio section 
remove_filter('pre_user_description', 'wp_filter_kses');


//style
.author_bio_section{
background: none repeat scroll 0 0 #F5F5F5;
padding: 15px;
border: 1px solid #ccc;
}

.author_name{
font-size:16px;
font-weight: bold;
}

.author_details img {
border: 1px solid #D8D8D8;
border-radius: 50%;
float: left;
margin: 0 10px 10px 0;
}

// Plugin update notification remove

function themeisle_plugin_updates( $value ) {
    unset( $value->response['themeisle-companion/themeisle-companion.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'themeisle_plugin_updates' );


// Feature image dark remove

.boxed-layout-header::before{
	background: transparent !important;
}

// Custom Breadcrumbs //https://www.thewebtaylor.com/articles/wordpress-creating-breadcrumbs-without-a-plugin
function custom_breadcrumbs() {
       
    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Homepage';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
              
            } else {
                  
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
                   
            } else {
                   
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
               
            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }
       
        echo '</ul>';
           
    }
       
}
// Styling breadcrumbs
#breadcrumbs{
    list-style:none;
    margin:10px 0;
    overflow:hidden;
}
  
#breadcrumbs li{
    display:inline-block;
    vertical-align:middle;
    margin-right:15px;
}
  
#breadcrumbs .separator{
    font-size:18px;
    font-weight:100;
    color:#ccc;
}

							// Get all category name in loop

							$categories = get_categories( array(
									'orderby' => 'name',
									'order'   => 'ASC'
								) );
								 
								foreach( $categories as $category ) {
									$category_link = sprintf( 
										'<a href="%1$s" alt="%2$s">%3$s</a>',
										esc_url( get_category_link( $category->term_id ) ),
										esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
										esc_html( $category->name )
									);
									 
									echo '<li class="blog-category col-12 col-md-4">' . sprintf( esc_html__( '%s', 'textdomain' ), $category_link ) . '</li> ';
								} 
								
							// Get all category slug  in loop	

								//get only parents
								$args = array('orderby' => 'name','order' => 'ASC','parent' => 0);
								$Parent_categories = get_categories($args);

								foreach($Parent_categories as $category) { 
									echo '<li class="blog-category col-12 col-md-4"><a href="'.get_category_link( $category->term_id ).'">'.$category->category_nicename.'</a><br/>';
									
									//get all children of this category
									$args = array('orderby' => 'name','order' => 'ASC','parent' => $category->term_id);
									$Child_categories = get_categories($args);
									foreach ($Child_categories as $c){
										echo '<span class="cat-child"><a href="'.get_category_link( $c->term_id ).'">'.$c->category_nicename.'</a></span>  ';
									}
									echo '</li>';
								}					

							// Get features post title by category "Features Post"


							$posts = new WP_Query();
							$posts->query( "category_name='{feature-article}'&posts_per_page=6" );
							if($posts->have_posts()) :
								while ($posts->have_posts()) : $posts->the_post(); ?>
									<li>
										<a href="<?php the_permalink(); ?>" target="_blank" rel="noopener" data-wpel-link="internal">
											<img class="bullet-list-img" src="<?php echo $ts_theme_option['ts_feature_post_icon']['url'];?>" alt="Bullet list"/> <?php the_title(); ?>
										</a>
									</li>
								<?php endwhile;        
							endif;
							wp_reset_postdata();
						

// Change the menu item labels
function change_post_menu_label_post() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Short Answer';
  $submenu['edit.php'][5][0] = 'Short Answer';
  $submenu['edit.php'][10][0] = 'Add Article';
  echo '';
  
}
add_action( 'admin_menu', 'change_post_menu_label_post' );
 
// Change the menu item labels
function change_post_menu_label_page() {
  global $menu;
  global $submenu;
  $menu[20][0] = 'Long Answer';
  $submenu['edit.php'][5][0] = 'Add Article';
  $submenu['edit.php'][10][0] = 'Add New Article';
  echo '';
}
add_action( 'admin_menu', 'change_post_menu_label_page' );
 


// Remove

// Remove

// Remove

