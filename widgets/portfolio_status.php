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
            if(is_user_logged_in()){
                $title = apply_filters( 'widget_title', $instance['title'] );
                $status = get_option('status');
                $statusmessage = $instance[ 'message_status_'.$status ];
                $statuslink = $instance[ 'link_status_'.$status ];
                $statuslinktext = $instance[ 'linktext_status_'.$status ];
                if($statusmessage == ""){
                    $statusmessage = $instance[ 'message_status_default' ];
                }

                // before and after widget arguments are defined by themes
                echo $args['before_widget'];
                if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];
                
                // This is where you run the code and display the output
                // Get status
                // If status = x then do y
                // - Set status messages in back end

                echo __( "<p>".$statusmessage."</p>", 'ep_widget' );
                if($statuslink != ""){
                    echo __( "<p><a href='".$statuslink."'>".$statuslinktext."</a></p>", 'ep_widget' );
                }
                echo $args['after_widget'];
            }
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
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label></p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <?php
                // Default message
                if ( isset( $instance[ 'message_status_default' ] ) ) {
                    $currentmessage = $instance[ 'message_status_default' ];
                } else {
                    $currentmessage = __( 'Default message', 'ep_widget' );
                }
            ?>
            <p><label for="<?php echo $this->get_field_id( 'message_status_default' ); ?>"><?php _e( 'Default text (this will show if any of the fields below are blank)' ); ?>:</label></p>
            <textarea rows="6" cols="30" id="<?php echo $this->get_field_id( 'message_status_default' ); ?>" name="<?php echo $this->get_field_name( 'message_status_default' ); ?>"><?php echo $currentmessage; ?></textarea>  
            <?php
                $statustypes = get_statustypes();
                foreach($statustypes as $statustype){
                    if ( isset( $instance[ 'message_status_'.$statustype ] ) ) {
                        $currentmessage = $instance['message_status_'.$statustype];
                    } else {
                        $currentmessage = __( $statustype.' message', 'ep_widget' );
                    }
                    if ( isset( $instance[ 'link_status_'.$statustype ] ) ) {
                        $currentlink = $instance['link_status_'.$statustype];
                    } else {
                        $currentlink = "";
                    }
                    if ( isset( $instance[ 'linktext_status_'.$statustype ] ) ) {
                        $currentlinktext = $instance['linktext_status_'.$statustype];
                    } else {
                        $currentlinktext = "";
                    }
                    ?>
                    <h3><?php _e( $statustype ); ?></h3>
                    <p><label for="<?php echo $this->get_field_id( 'message_status_'.$statustype ); ?>">Message:</label></p>
                    <textarea rows="6" cols="30" id="<?php echo $this->get_field_id( 'message_status_'.$statustype ); ?>" name="<?php echo $this->get_field_name( 'message_status_'.$statustype ); ?>"><?php echo $currentmessage; ?></textarea>
                    <p><label for="<?php echo $this->get_field_id( 'link_status_'.$statustype ); ?>">Link to:</label></p>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'link_status_'.$statustype ); ?>" name="<?php echo $this->get_field_name( 'link_status_'.$statustype ); ?>" type="text" value="<?php echo esc_attr( $currentlink ); ?>" />
                    <p><label for="<?php echo $this->get_field_id( 'linktext_status_'.$statustype ); ?>">Link text:</label></p>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'linktext_status_'.$statustype ); ?>" name="<?php echo $this->get_field_name( 'linktext_status_'.$statustype ); ?>" type="text" value="<?php echo esc_attr( $currentlinktext ); ?>" />
                    <?php
                }
            ?>
        <?php 
        }
        
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $statustypes = get_statustypes();
            $instance = $old_instance;
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['message_status_default'] = ( ! empty( $new_instance['message_status_default'] ) ) ? strip_tags( $new_instance['message_status_default'] ) : '';
            foreach($statustypes as $statustype){
                $instance['message_status_'.$statustype] = ( ! empty( $new_instance['message_status_'.$statustype] ) ) ? strip_tags( $new_instance['message_status_'.$statustype] ) : '';
                $instance['link_status_'.$statustype] = ( ! empty( $new_instance['link_status_'.$statustype] ) ) ? strip_tags( $new_instance['link_status_'.$statustype] ) : '';
                $instance['linktext_status_'.$statustype] = ( ! empty( $new_instance['linktext_status_'.$statustype] ) ) ? strip_tags( $new_instance['linktext_status_'.$statustype] ) : '';
            }
            return $instance;
        }
    }
?>