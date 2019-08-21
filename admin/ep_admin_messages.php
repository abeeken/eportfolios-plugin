<?php
    /*
        Functions to handle portfolio messages
    */

    // === Add to the menu
	function add_portfolio_options_messages()
	{
        add_submenu_page('ep-options', 'Portfolio Options > Messages', 'Messages', 'manage_options', 'ep-messages', 'portfolio_messages');
	}
	
    add_action('admin_menu', 'add_portfolio_options_messages');

    // === Message Setup
    function portfolio_messages()
    {
        ?>
        <div class="wrap">
            <h2>Portfolio Options</h2>
            <h3>Messages</h3>
            <p>Set custom messages for the portfolio</p>            
        </div>
        <?php 
    }
?>