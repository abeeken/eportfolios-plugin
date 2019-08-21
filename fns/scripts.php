<?php
    /*
        Includes required scripts and styles 
    */

    // Front End
    function epstyles_front(){
        wp_register_style( 'eportfolio-styles', plugins_url( '/css/eportfolios.css', __FILE__ ) );
        wp_enqueue_style( 'eportfolio-styles' );
    }
    add_action( 'wp_enqueue_scripts', 'epstyles_front' );
?>