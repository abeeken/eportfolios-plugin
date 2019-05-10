<?php
    /*
        Admin functions for EPortfolio plugin
    */

    // === Create the menu
	function add_portfolio_options()
	{
        add_menu_page('Portfolio Options', 'Portfolio Options', 'manage_options', 'ep-options', 'portfolio_options');
        add_submenu_page('ep-options', 'Portfolio Options', 'Portfolio Options', 'manage_options', 'ep-options', 'portfolio_options');
	}
	
	add_action('admin_menu', 'add_portfolio_options');

    // === Functions for option pages

    // ====== Main Page - try setting multiple choice options as arrays, itterating through and comparing the value to the set value to avoid lots of $is_set type things
	function portfolio_options()
	{
        // General options

        // Default values - these should all be stored in thingtype values and decoded out here unless they are standard       
        $coursetypes = json_decode(get_option('coursetypes'), TRUE);
        $portfolio_status = get_statustypes();
        $usertypes = json_decode(get_option('usertypes'), TRUE);

        // Current values
        $current_coursetype = get_option('coursetype');
        $current_status = get_option('status');
        $current_submission_user = get_option('submissionuser');

        //$port_type = get_option('port_type');
?>
        <div class="wrap">
			<h2>ePortfolios</h2>
            <p>Welcome to the ePortfolio system. These options will allow you to set up various aspects of the system</p>

            <h3>General Options</h3>
            
            <form method="post" action="options.php">
				<?php wp_nonce_field('update-options') ?>

                <p>Course Type: 
                    <select name="coursetype">
                        <?php echo options_from_array($coursetypes, "id", "label", $current_coursetype); ?>
                    </select>
                </p>

                <p>Portfolio Status:
                    <select name="status">
                        <?php echo options_from_array($portfolio_status, "", "", $current_status); ?>
                    </select>
                </p>

                <p>Submission User Type:
                    <select name="submissionuser">
                        <?php echo options_from_array($usertypes, "id", "label", $current_submission_user); ?>
                    </select>
                </p>

                <p><input type="submit" name="Submit" value="Save Options" /></p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="coursetype,status,submissionuser" />
            </form>
        </div>
<?php
    }
?>