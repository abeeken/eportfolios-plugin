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

    // === Signoff Grid - Displays the signoff grid showing pages that need signing off
    function signoff_grid(){
        // Get all user types
        // Get all posts with signoff requirements
        // - We need to look at what they require and what they have and make a grid based on that...

        // Has this portfolio been submitted?


        $usertypes = json_decode(get_option('usertypes'), TRUE);
        $page_ids = get_all_page_ids();
        $grid = array();

        $current_ep_usertype = current_ep_usertype();
        $submissionuser = get_option('submissionuser');

        $total_required = 0;
        $total_signatures = 0;

        foreach($page_ids as $page_id){
            $required = get_post_meta( $page_id, 'signoffs', true );            

            if(is_array($required)){
                $signatures = json_decode(get_post_meta( $page_id, 'signatures', true ), TRUE);
                if(!is_array($signatures)){
                    $signatures = array(); // Initialise as an array - maybe we could make this a function?
                }

                $total_required += count($required);
                $total_signatures += count($signatures);

                $row = array();

                $row["pagetitle"] = get_the_title($page_id);

                foreach($usertypes as $usertype){
                    if(array_key_exists($usertype['id'],$required)){
                        if(in_array_r($usertype['id'],$signatures)){
                            $row[$usertype['id']] = "Y";
                        } else {
                            $row[$usertype['id']] = "N";
                        }
                    } else {
                        $row[$usertype['id']] = "N/A";
                    }
                }

                $grid[] = $row;
            }
        }

        // Build output
        $output = '<div id="signoff_grid">';
        $output .= "    <table>";
        $output .= "        <thead>";
        $output .= "            <tr>";
        $output .= "                <td></td>";

        foreach($usertypes as $usertype){
            $output .= "            <td>".$usertype['label']."</td>";
        }

        $output .= "            </tr>";
        $output .= "        </thead>";
        $output .= "        <tbody>";

        foreach($grid as $row){
            $output .= "        <tr>";
            foreach($row as $element){
                $output .= "        <td>".$element."</td>";
            }
            $output .= "        </tr>";
        }

        $output .= "        </tbody>";
        $output .= "    </table>";
        $output .= "    <p>".$total_required." signatures expected</p>";
        $output .= "    <p>".$total_signatures." signatures recorded</p>";
        $output .= "</div>";

        // Can the user submit the portfolio and is it ready to be submitted?
        if($current_ep_usertype == $submissionuser){
            if($total_required == $total_signatures){
                // Ready to hand in
                $output .= signoff_submit_button();
            } else {
                // Cannot hand in
                $output .= "<p>You cannot hand in your portfolio yet; please check the sign off grid above to see which pages require action.</p>";
            }
        }

        return $output;
    }

    add_shortcode("signoff_grid", "signoff_grid");

    function signoff_submit_button(){
        $output = "";

        $status = get_option('status');

        if($status == "open" || $status == "resit"){
            // Return the button
            $output .= "SIGNOFF BUTTON";

            //Insert JQuery into the header to do the show/hide
            $output .= '<span id="show_submit" class="button">Submit Portfolio</span>';
            $output .= '<div id="submit_form">';
            $output .= '</div>';
        } elseif($status == "submitted"){
            // Return submitted message
            $output .- "SUBMITTED";
        } elseif($status == "marked"){
            // Return marked message
            $output .= "MARKED";
        }

        return $output;
    }
?>