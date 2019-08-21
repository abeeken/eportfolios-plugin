<?php 
    /*
    Plugin Name: EPortfolios
    Plugin URI: 
    Description: Plugin which allows the creation of an EPortfolio through a number of features
    Author: Andrew Beeken
    Version: 0
    Author URI: http://www.andrewbeeken.co.uk
    */

	/*
	Features to be implemented:
    
    Management Panel
	Sign off grid
	Status
	Widgets
	
    */

    // == Scripts and Styles
    include(dirname( __FILE__ ) .'/scripts.php');
    
    // == General Functions
    include(dirname( __FILE__ ) .'/fns/functions.php');

    // == Admin Functions
    include(dirname( __FILE__ ) .'/admin/ep_admin.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_thingtypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_usertypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_coursetypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_custom_meta.php');
    include(dirname( __FILE__ ) .'/admin/ep_signatures.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_messages.php');
    include(dirname( __FILE__ ) .'/admin/ep_statustypes.php');

    // == Frontend Functions
    include(dirname( __FILE__ ) .'/fns/signoff.php');
    include(dirname( __FILE__ ) .'/fns/signoff_grid.php');

    // == CFE Hooks
    include(dirname( __FILE__ ) .'/fns/cfe_hooks.php');

    // == Widgets
    include(dirname( __FILE__ ) .'/widgets/portfolio_status.php');
?>