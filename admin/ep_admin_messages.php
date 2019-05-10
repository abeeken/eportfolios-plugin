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
            <h4>Status Widget</h4>
            <?php
                $statustypes = get_statustypes();
                $typelist = "";
                ?>
                <form method="post" action="options.php">
                    <?php wp_nonce_field('update-options') ?>
                <?php
                    foreach($statustypes as $statustype){
                        $currentmessage = get_option("message_status_".$statustype);
                        ?>
                        <p><?php echo $statustype; ?>:</p>
                        <textarea name="message_status_<?php echo $statustype; ?>"><?php echo $currentmessage; ?></textarea>
                        <?php
                        $typelist .= "message_status_".$statustype.",";
                    }
                    rtrim($typelist,",");?>
                    <p><input type="submit" name="Submit" value="Save Options" /></p>
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="page_options" value="<?php echo $typelist; ?>" />
                </form>
        </div>
        <?php 
    }
?>