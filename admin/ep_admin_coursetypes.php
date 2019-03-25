<?php
    /*
        Functions to handle portfolio specific course types
    */

    // === Add to the menu
	function add_portfolio_options_coursetypes()
	{
        add_submenu_page('ep-options', 'Portfolio Options > Course Types', 'Course Types', 'manage_options', 'ep-coursetypes', 'portfolio_coursetypes');
	}
	
    add_action('admin_menu', 'add_portfolio_options_coursetypes');
    
    // === Portfolio Type Setup
    function portfolio_coursetypes()
    {
        do_thingtypes("coursetypes", "Course", "Set or delete custom course types for the portfolio");        
    }
?>