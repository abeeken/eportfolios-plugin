<?php
    /*
        Functions to display signoff fields and grid
    */

    // === Signoff forms
  
    function signoff_form($content){
        // - Process any sign offs
        // - Get all _sign values from post meta
        // - Gather list of users based on those requirements
        // - Display list
        //      - If user has signed then state
        //      - If user is logged in and not then show a login form

        global $post;  
        $required = get_post_meta( $post->ID, 'signoffs', true ); // Signatures required on this page

        //If no signatures are required, don't bother going any further!
        if(is_array($required)){

            $signatures = json_decode(get_post_meta( $post->ID, 'signatures', true ), TRUE); // Signatures present on this page
            $current_user = wp_get_current_user(); // Currently logged in user
            $current_user_meta = get_user_meta($current_user->ID);

            if($signatures == ""){
                $signatures = array(); // Initialise signatures if empty
            }        

            $output = "";
            $tosign = "";
            $signed = "";

            // Has someone signed?
            if(array_key_exists("sign", $_POST)){
                $signatures[] = array(
                        "username" => $_POST["sign"],
                        "nicename" => $_POST["nicename"],
                        "type" => $_POST["type"],
                        "date" => date("F j, Y, g:i a")
                    );
                update_post_meta($post->ID, 'signatures', json_encode($signatures));
            }

            //Build list of current signatures
            foreach($signatures as $signature){
                $signed .= "<li>".$signature["nicename"]." on ".$signature["date"]."</li>"; 
            }

        
            foreach($required as $key => $value){
                //Check for a signoff

                //Has this already been signed?
                if(!in_array_r($key,$signatures)){
                    $users = get_users( array(
                        'meta_query' =>
                            array(
                                array(
                                    'key' => 'ep_usertype',
                                    'value' => $key,
                                    'compare' => "=="
                                )
                            )
                        )
                    );

                    foreach($users as $user){
                        $sign = "";
                        // Is this the current user?
                        if($user->user_login == $current_user->user_login){
                            $sign = '<form action="#" method="post"><input type="hidden" name="sign" value="'.$current_user->user_login.'" /><input type="hidden" name="nicename" value="'.$current_user->display_name.'" /><input type="hidden" name="type" value="'.$current_user_meta['ep_usertype'][0].'" /><input type="submit" name="submit" value="Sign" /></form>';
                        }
                        // Has this user already signed?
                        if(!in_array_r($user->user_login, $signatures)){
                            $tosign .= "<li>".$user->data->display_name." (".$key.")".$sign."</li>";
                        }
                    }
                }
            }

            // Compile the output
            if($tosign != ""){
                $output .= '<div id="required_signatures"><h4>Signatures required</h4>';
                $output .= "<ul>";
                $output .= $tosign;
                $output .= "</ul></div>";
            }

            if($signed != ""){
                $output .= '<div id="current_signatures"><h4>Current signatures</h4>';
                $output .= "<ul>";
                $output .= $signed;
                $output .= "</ul></div>";
            }

            $content .= $output;
        }

        return($content);
    }

    add_filter('the_content', 'signoff_form', 0);
?>