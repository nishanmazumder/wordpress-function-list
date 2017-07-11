This functions are only for my personal note. Copy form - Wpbeginner (http://www.wpbeginner.com/wp-tutorials/25-extremely-useful-tricks-for-the-wordpress-functions-file/)

<?php

// Remove WordPress Version Number

function wpb_remove_version() {
return '';
}
add_filter('the_generator', 'wpb_remove_version');



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

// Remove

// Remove

// Remove

// Remove

// Remove

