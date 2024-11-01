<?php
/*
Plugin Name: The Total Views
Plugin URI: http://www.gamix.fr/
Description: A simple plugin to display the total number of articles views by widget or/and by shortcode.
Version: 1.1.0
Author: Fredd
Author URI: http://www.gamix.fr/
License: GPL2
*/

function thetotalviews_admin_menu() {
  
    add_options_page(
      __('The Total Views',  'the_total_views') ,
      __('The Total Views',  'the_total_views'), 
      'manage_options' ,
      basename(__FILE__),
      'thetotalviews_options_page');
}
add_action('admin_menu', 'thetotalviews_admin_menu');

function thetotalviews_options_page()

{
    echo thetotalviews_output_options_page();
}


function thetotalviews_output_options_page()

{

    return "<div class=wrap><br /><hr>".__('This plugin requires no adjustment , you simply place the widget to where you want to display the total number of views articles or place shortcode [thetotalviews] in the page of your choice',  'the_total_views')."</div>";
}

function thetotalviews_register_assets(){
    load_plugin_textdomain( 'the_total_views', false, dirname( plugin_basename( __FILE__ ) ) .'/languages' );
	}
add_action('init', 'thetotalviews_register_assets');


class TheTotalViews extends WP_Widget
{
  function TheTotalViews()
  {
    $widget_ops = array('classname' => 'TheTotalViews', 'description' => 'Displays the total number of articles views' );
    $this->WP_Widget('TheTotalViews', 'Total Views', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
global $wpdb;

    $table_name = $wpdb->prefix . "postmeta";
    $sql = "SELECT SUM(meta_value) FROM ".$table_name." WHERE meta_key = 'views'";
    $total = $wpdb->get_var($sql);
    echo "<b>".$total."</b>";
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("TheTotalViews");') );?>

<?php

function get_thetotalviews() {
global $wpdb;
$table_name = $wpdb->prefix . "postmeta";
    $sql = "SELECT SUM(meta_value) FROM ".$table_name." WHERE meta_key = 'views'";
    $total = $wpdb->get_var($sql);
    echo "<b>".$total."</b>";
}

add_shortcode('thetotalviews', 'get_thetotalviews');