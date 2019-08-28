<?php
    // === Signoff Grid - Displays the signoff grid showing pages that need signing off
    function signoff_grid(){
        // Get all user types
        // Get all posts with signoff requirements
        // - We need to look at what they require and what they have and make a grid based on that...

        // Has this portfolio been submitted?
        // - If yes, then we need to set the status to submitted before we do anything else...
        if(array_key_exists('submit_portfolio', $_POST)){
            if($_POST['submit_portfolio'] == true){
                do_submit();
            }
        }

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
                $output .= "        <td class='".strtolower(str_replace("/","",$element))."'>".$element."</td>";
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
            //Insert JQuery into the footer to do the show/hide...
            $output .= '<span id="show_submit" class="button">Submit Portfolio</span>';
            $output .= '<div id="submit_form">';
            $output .= '    <form action="" method="post">';
            $output .= '        <input type="hidden" name="submit_portfolio" value="true" />';
            $output .= '        <p>By clicking this button you confirm that you are happy to submit your ePortfolio. PLEASE ENSURE ALL WORK IS COMPLETED BEFORE DOING SO.</p>';
            $output .= '        <button type="submit">I confirm that I am happy to submit my ePortfolio</button>';
            $output .= '    </form>';
            $output .= '</div>';
        } elseif($status == "submitted"){
            // Return submitted message
            $output .= "<p>Your ePortfolio has been submitted for marking.</p>";
        } elseif($status == "marked"){
            // Return marked message
            $output .= "MARKED";
        }

        return $output;
    }

    function do_submit(){
        update_option('status', 'submitted');
        // We'll likely want to do something to lock down the front end forms here once they're implemented with proper hooks...
    }
?>