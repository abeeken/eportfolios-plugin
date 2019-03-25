<?php
    /*
        Admin functions for managing signatures
    */

    // === Set required signatures
    function ep_signoff_meta_box() {
        add_meta_box(
            'ep_signoff_meta_box', // $id
            'EPortfolio Settings', // $title
            'show_ep_signoff_meta_box', // $callback
            'page', // $screen
            'normal', // $context
            'high' // $priority
        );
    }
    add_action( 'add_meta_boxes', 'ep_signoff_meta_box' );

    function show_ep_signoff_meta_box() {
        global $post;  
        $meta = get_post_meta( $post->ID, 'signoffs', true );
    ?>
        <input type="hidden" name="signoffs_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <?php // ========= Sign offs required for usertypes ?>

        <p>User Sign-Off's Required:</p>
        
    <?php
        $usertypes = json_decode(get_option('usertypes'), TRUE);

        foreach($usertypes as $usertype){
            $sign_check = $usertype['id'];
            echo '<p><input type="checkbox" name="signoffs['.$sign_check.']" value="true"';
            if(is_array($meta) && array_key_exists($sign_check, $meta)){
                if ($meta[$sign_check] === 'true'){
                    echo " checked";
                }
            }
            echo ' /> '.$usertype['label'].'</p>';
        }    
    }

    function save_signoffs_meta( $post_id ) {   
        // verify nonce
        if ( !wp_verify_nonce( $_POST['signoffs_meta_box_nonce'], basename(__FILE__) ) ) {
            return $post_id; 
        }
        // check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // check permissions
        if ( 'page' === $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }  
        }
        
        $old = get_post_meta( $post_id, 'signoffs', true );
        $new = $_POST['signoffs'];
    
        if ( $new && $new !== $old ) {
            update_post_meta( $post_id, 'signoffs', $new );
        } elseif ( '' === $new && $old ) {
            delete_post_meta( $post_id, 'signoffs', $old );
        }
    }
    add_action( 'save_post', 'save_signoffs_meta' );

    // === Remove signatures from pages
    function ep_signatures_meta_box() {
        add_meta_box(
            'ep_signatures_meta_box', // $id
            'Page Signatures', // $title
            'show_ep_signatures_meta_box', // $callback
            'page', // $screen
            'normal', // $context
            'high' // $priority
        );
    }
    add_action( 'add_meta_boxes', 'ep_signatures_meta_box' );

    function show_ep_signatures_meta_box() {
        global $post;  
        $signatures = json_decode(get_post_meta( $post->ID, 'signatures', true ), TRUE);
    ?>
        <input type="hidden" name="ep_signatures_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <?php
        // Show all current signatures
        if(is_array($signatures)){
            foreach($signatures as $signature){
                echo '<p><input type="checkbox" name="ep_signatures['.$signature['username'].']" value="'.$signature['nicename'].'|'.$signature['type'].'|'.$signature['date'].'" checked /> '.$signature['nicename'].' on '.$signature['date'].'</p>';
            }
        }
    }

    function save_ep_signatures_meta( $post_id ) {   
        // verify nonce
        if ( !wp_verify_nonce( $_POST['ep_signatures_meta_box_nonce'], basename(__FILE__) ) ) {
            return $post_id;
        }
        // check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // check permissions
        if ( 'page' === $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }  
        }
        
        if(array_key_exists('ep_signatures',$_POST)){
            foreach($_POST['ep_signatures'] as $key => $value){
                $value = explode("|",$value);
                $signatures[] = array(
                    "username" => $key,
                    "nicename" => $value[0],
                    "type" => $value[1],
                    "date" => $value[2]
                );
            }
        }
        update_post_meta( $post_id, 'signatures', json_encode($signatures) );
    }
    add_action( 'save_post', 'save_ep_signatures_meta' );
?>