<?php

/**
 *
 * @link              https://https://niksingla.xyz/
 * @since             1.0.0
 * @package           Custom_Events
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Events
 * Plugin URI:        https://niksingla.xyz/plugins
 * Description:       Adds custom post type Events in the admin menu. Use the shortcode <strong>[events_list]</strong> on any page or post to show the list of the events. Example shortcodes - <strong>[events_list], [events_list sort=date], [events_list sort=title], [events_list numposts=4]</strong>. Change events view format from the event settings page. Adds Show Upcoming widgets in the sidbar or footer, also allows to edit the values.
 * Version:           1.0.0
 * Author:            Nikhil Singla
 * Author URI:        https://niksingla.xyz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-events
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CUSTOM_EVENTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-events-activator.php
 */
function activate_custom_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-events-activator.php';
	Custom_Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-events-deactivator.php
 */
function deactivate_custom_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-events-deactivator.php';
	Custom_Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_events' );
register_deactivation_hook( __FILE__, 'deactivate_custom_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-events.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function run_custom_events() {

	$plugin = new Custom_Events();
	$plugin->run();

}
run_custom_events();


//Custom Funtions Start

//Including this to add widget on sidebars or footer
include 'widget.php';

//Creating custom post type 'event'
function create_post_type() {
  register_post_type( 'event',
    array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail'),
      'rewrite' => array('slug' => 'events', 'with_front'=>false),
    )
  );
}
add_action( 'init', 'create_post_type' );

//Creating Event Details field
function custom_events_meta_boxes() {
  add_meta_box( 'event_details', 'Event Details', 'custom_events_meta_box_callback', 'event','normal', 'high' );
}
add_action( 'add_meta_boxes', 'custom_events_meta_boxes' );

//Removes the custom fields meta box shown by default in few themes
function remove_custom_fields() {
  remove_meta_box( 'postcustom', 'event', 'normal' );
}
add_action( 'admin_menu', 'remove_custom_fields' );

//Callback function to display the event creation post page
function custom_events_meta_box_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'custom_events_nonce' );
    $description = get_post_meta( $post->ID, 'description', true );
    $date = get_post_meta( $post->ID, '_event_date', true );    
    $timings = get_post_meta( $post->ID, '_event_timings', true );
    $location = get_post_meta( $post->ID, '_event_location', true );
    include 'templates/admin.php';
}

//Save the events data
function custom_events_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['custom_events_nonce'] ) || ! wp_verify_nonce( $_POST['custom_events_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'event' == $_POST['post_type'] ) {
        if ( isset( $_POST['event_location'] ) ) {
            update_post_meta( $post_id, '_event_location', sanitize_text_field( $_POST['event_location'] ) );
        }
        if ( isset( $_POST['event_timings'] ) ) {
            update_post_meta( $post_id, '_event_timings', sanitize_text_field( $_POST['event_timings'] ) );
        }
        if ( isset( $_POST['description'] ) ) {
          update_post_meta( $post_id, 'description', sanitize_text_field( $_POST['description'] ) );
        }
        if ( isset( $_POST['event_date'] ) ) {
          update_post_meta( $post_id, '_event_date', sanitize_text_field( strtotime($_POST['event_date']) ) );
        }
    }
}
add_action( 'save_post', 'custom_events_save_meta_box_data' );

//Shortcode to display on frontend [events_list]
function events_list_disp($atts){
  if(is_array($atts)){
    $atts = array_change_key_case($atts, CASE_LOWER);
    $atts = shortcode_atts(array(
      'sort'=> 'default',
      'numposts' => -1,
    ),$atts);
    if(!ctype_digit($atts['numposts'])) $atts['numposts']=-1;
  } 
  else{
    $atts = array(
      'sort' => 'default',
      'numposts' => -1,
    );
  }
  ob_start();
  include 'templates/front.php';
  $html = ob_get_clean();
  return $html;
}
add_shortcode('events_list', 'events_list_disp');

//Displaying Event details on single Event page
function add_post_information( $content ) {
  if ( is_singular('event') && is_main_query() ) {
    $post_id = get_the_ID();
    $event_desc = get_post_meta($post_id, 'description', true);
    $event_loc = get_post_meta($post_id, '_event_location', true);
    $event_time = get_post_meta($post_id, '_event_timings', true);
    $event_time = date("h:i A", strtotime($event_time));  
    $event_date = get_post_meta($post_id, '_event_date', true);
    $event_date = date("jS \of F, Y", $event_date);  
    $additional_content = '<div class="post-information">';
    $additional_content .= has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID()) : '';
    $additional_content .= '<p><strong>Event Title:</strong> ' . get_the_title() . '</p>';
    $additional_content .= '<p><strong>Description:</strong> ' . $event_desc . '</p>';
    $additional_content .= '<p><strong>Event Date:</strong> ' . $event_date . '</p>';
    $additional_content .= '<p><strong>Location:</strong> ' . $event_loc . '</p>';
    $additional_content .= '<p><strong>Timings:</strong> ' . $event_time . '</p>';
    $additional_content .= '</div>';
    $additional_content .= $content;
    return $additional_content;
  }
  return $content;
}
add_filter( 'the_content', 'add_post_information' );

//Display event submenu for display settings
function event_settings_page() {
  ?>
  <div class="wrap">
    <form method="post" action="options.php">
      <?php settings_fields( 'event_settings_group' ); ?>
      <?php do_settings_sections( 'event_settings_page' ); ?>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}
function event_settings() {
  add_settings_section( 'event_settings_section', '', 'event_settings_callback', 'event_settings_page' );
  register_setting( 'event_settings_group', 'event_view_setting' );
}
function event_settings_callback() {
  $setting_value = get_option( 'event_view_setting');
  if($setting_value=='') $setting_value = 'list';
  include 'templates/display_set.php';
}
add_action( 'admin_menu', 'event_admin_menu' );
function event_admin_menu() {
  add_submenu_page( 'edit.php?post_type=event', 'Event Display Settings', 'Event Display Settings', 'manage_options', 'event_settings_page', 'event_settings_page' );
}
add_action( 'admin_init', 'event_settings' );
