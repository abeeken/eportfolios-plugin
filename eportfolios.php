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
    
    // == General Functions
    include(dirname( __FILE__ ) .'/functions.php');

    // == Admin Functions
    include(dirname( __FILE__ ) .'/admin/ep_admin.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_thingtypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_usertypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_coursetypes.php');
    include(dirname( __FILE__ ) .'/admin/ep_admin_custom_meta.php');
    include(dirname( __FILE__ ) .'/admin/ep_signatures.php');

    // == Frontend Functions
    include(dirname( __FILE__ ) .'/signoff.php');
?>