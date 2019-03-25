<?php
    /*
        Functions to handle portfolio specific user types
    */

    // === Add to the menu
	function add_portfolio_options_usertypes()
	{
        add_submenu_page('ep-options', 'Portfolio Options > User Types', 'User Types', 'manage_options', 'ep-usertypes', 'portfolio_usertypes');
	}
	
    add_action('admin_menu', 'add_portfolio_options_usertypes');
    
    // === User Type Setup - User types are stored as serialised JSON in options
    function portfolio_usertypes()
    {
        // DO THINGTYPES
        do_thingtypes("usertypes", "User", "Set or delete custom user types for the portfolio");        
    }

    // === Add fields to the user profiles to store the portfolio types
    function portfolio_usertype_extra( $user ){
        $usertypes = json_decode(get_option('usertypes'), TRUE);

        ?>
        <h3><?php _e("ePortfolio User Type", "blank"); ?></h3>
        <select name="ep_usertype">
            <?php
            foreach($usertypes as $usertype){
                echo '<option value="'.$usertype['id'].'"';
                if(get_the_author_meta( 'ep_usertype', $user->ID ) == $usertype['id']){
                    echo " selected";
                }
                echo '>'.$usertype['label'].'</option>';
            }
            ?>
        </select>
        <?php        
    }

    add_action( 'show_user_profile', 'portfolio_usertype_extra' );
    add_action( 'edit_user_profile', 'portfolio_usertype_extra' );

    function save_portfolio_usertype_extra( $user_id ){
        if ( !current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }
        update_user_meta( $user_id, 'ep_usertype', $_POST['ep_usertype'] );
    }

    add_action( 'personal_options_update', 'save_portfolio_usertype_extra' );
    add_action( 'edit_user_profile_update', 'save_portfolio_usertype_extra' );
?>