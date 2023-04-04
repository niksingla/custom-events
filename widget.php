<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Custom_Post_Type_Widget extends WP_Widget {

    // Constructor function
    function __construct() {
        parent::__construct(
            'show_events_widget',
            __( 'Show Upcoming Events', 'text_domain' ),
            array( 'description' => __( 'Displays Upcoming Events', 'text_domain' ) )
        );
    }
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : 'Upcoming Events';
        $num_events = isset( $instance['num_events'] ) ? $instance['num_events'] : 4;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_events' ); ?>"><?php _e( 'Number of events to Display:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'num_events' ); ?>" name="<?php echo $this->get_field_name( 'num_events' ); ?>" type="text" value="<?php echo esc_attr( $num_events ); ?>">
        </p>
        <?php
    }
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = !empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['num_events'] = !empty( $new_instance['num_events'] ) ? sanitize_text_field( $new_instance['num_events'] ) : 4;
        return $instance;
    }
        
    // Widget function
    public function widget( $args, $instance ) {
        // Query custom post type data
        $query = new WP_Query( array(
            'post_type' => 'event',
            'meta_key' => '_event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
        ) );
        // Display custom post type data
        if ( $query->have_posts() ) {
            echo $args['before_widget'];
            echo $args['before_title'] . __( $instance['title'], 'text_domain' ) . $args['after_title'];
            echo '<div class="event-widget">';
            $count = 0;
            while ( $query->have_posts() ) {
                if($count==$instance['num_events']) break;
                $query->the_post();
                $event_time = get_post_meta(get_the_ID(), '_event_timings', true);
                $event_time = date("h:i A", strtotime($event_time));  
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $event_date = date("F j, y", ($event_date));  
                if(strtotime(($event_date.' '.$event_time)) - time()>0){
                    echo '<div class="event-widget-item">';
                    echo the_post_thumbnail();
                    echo '<a href="'.get_the_permalink().'">';
                    echo '<h5>' . get_the_title() . '</h5>';
                    echo '<div class="event-time"><p>'.$event_time.', '.$event_date.'</p></div>';
                    echo '</a>';
                    echo '</div>';
                    $count++;
                }                
            }
            echo '</div>';
            echo $args['after_widget'];
        }

        wp_reset_postdata();
    }
  
  }
  function register_custom_post_type_widget() {
    register_widget( 'Custom_Post_Type_Widget' );
  }
  add_action( 'widgets_init', 'register_custom_post_type_widget' );
?>
