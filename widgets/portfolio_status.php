<?php
    //Sidebar widget to show different messages based on portfolio status
    // Register and load the widget
    function ep_load_status() {
        register_widget( 'ep_status' );
    }
    add_action( 'widgets_init', 'ep_load_status' );

    class ep_status extends WP_Widget {        
        function __construct() {
            parent::__construct(        
            'ep_status', 
            __('Portfolio Status', 'ep_widget'),
            array( 'description' => __( 'Displays customisable messages based on Portfolio status', 'ep_widget' ), ) 
            );
        }
    
        // Frontend
        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', $instance['title'] );
            $status = get_option('status');
            $statusmessage = get_option('message_status_'.$status);

            // before and after widget arguments are defined by themes
            echo $args['before_widget'];
            if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
            
            // This is where you run the code and display the output
            // Get status
            // If status = x then do y
            // - Set status messages in back end

            echo __( $statusmessage, 'ep_widget' );
            echo $args['after_widget'];
        }
            
        // Backend 
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            } else {
                $title = __( 'New title', 'ep_widget' );
            }
            // Widget admin form
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
        <?php 
        }
        
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            return $instance;
        }
    }
?>