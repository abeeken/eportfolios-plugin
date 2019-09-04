<?php
    //Sidebar widget to show different messages based on portfolio usertype
    // Register and load the widget
    function ep_load_usertype_message() {
        register_widget( 'ep_usertype_message' );
    }
    add_action( 'widgets_init', 'ep_load_usertype_message' );

    class ep_usertype_message extends WP_Widget {        
        function __construct() {
            parent::__construct(        
            'ep_usertype_message', 
            __('Portfolio User Type Message', 'ep_widget'),
            array( 'description' => __( 'Displays customisable messages based on Portfolio user type - will only show for that specific user type', 'ep_widget' ), ) 
            );
        }
    
        // Frontend
        public function widget( $args, $instance ) {
            if(is_user_logged_in()){
                $current_ep_usertype = current_ep_usertype();     
                $usertype = $instance['usertype'];
                if($usertype == $current_ep_usertype){
                    $title = apply_filters( 'widget_title', $instance['title'] );
                    $message = $instance[ 'message' ];
                    $url = $instance[ 'url' ];
                    $link = $instance[ 'link' ];

                    // before and after widget arguments are defined by themes
                    echo $args['before_widget'];
                    if ( ! empty( $title ) )
                    echo $args['before_title'] . $title . $args['after_title'];

                    echo __( "<p>".$message."</p>", 'ep_widget' );

                    if( ! empty( $url ) )
                    echo __( '<p><a href="'.$url.'">'.$link.'</a>');
                    echo $args['after_widget'];
                }
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
            <p><label for="<?php echo $this->get_field_id( 'usertype' ); ?>"><?php _e( 'User Type:' ); ?></label></p>            
        <?php
            if ( isset( $instance['usertype'] ) ) {
                $usertype = $instance['usertype'];
            } else {
                $usertype = "";
            }
            $usertypes = json_decode(get_option('usertypes'), TRUE);
            ?>
            <select id="<?php echo $this->get_field_id( 'usertype' ); ?>" name="<?php echo $this->get_field_name( 'usertype' ); ?>">
            <?php
            foreach($usertypes as $utype){
                ?>
                <option value="<?php echo $utype['id']; ?>"<?php if($utype['id'] == $usertype){ echo " selected"; } ?>><?php echo $utype['label']; ?></option>
                <?php
            }
            ?>
            </select>
            <?php
                if ( isset( $instance['message'] ) ) {
                    $message = $instance['message'];
                } else {
                    $message = "";
                }
            ?>
            <p><label for="<?php echo $this->get_field_id( 'message' ); ?>"><?php _e( 'Message (HTML allowed - use with care!)' ); ?>:</label></p>
            <textarea rows="6" cols="30" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>"><?php echo $message; ?></textarea>
            <?php
                if ( isset( $instance['url'] ) ) {
                    $url = $instance['url'];
                } else {
                    $url = "";
                }
            ?>
            <p><label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Link' ); ?>:</label></p>
            <input type="input" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $url; ?>" />
            <?php
                if ( isset( $instance['link'] ) ) {
                    $link = $instance['link'];
                } else {
                    $link = "";
                }
            ?>
            <p><label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link Text' ); ?>:</label></p>
            <input type="input" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $link; ?>" />
            <?php
        }
        
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $statustypes = get_statustypes();
            $instance = $old_instance;
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['usertype'] = ( ! empty( $new_instance['usertype'] ) ) ? strip_tags( $new_instance['usertype'] ) : '';
            $instance['message'] = ( ! empty( $new_instance['message'] ) ) ? $new_instance['message'] : '';
            $instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
            $instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
            return $instance;
        }
    }
?>