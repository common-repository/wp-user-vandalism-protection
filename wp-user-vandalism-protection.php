<?php
! defined( 'ABSPATH' ) AND exit;
/**
 * Plugin Name: WP User Vandalism Protection
 * Plugin URI: http://www.kuantero.com/projects
 * Description: Remove "Profile" tab from admin bar and side menu, where users can edit their profile and kill profile page if user isn't an administrator.


# Version: 1.2
 * Author: Kuantero
 * Author URI: http://www.kuantero.com
 */

function protect_tester_stop_access_profile()
{
	Global $menu;
	
    // Remove AdminBar Link
    if ( 
        'wp_before_admin_bar_render' === current_filter()
        AND ! current_user_can( 'manage_options' )
    )
        return $GLOBALS['wp_admin_bar']->remove_menu( 'edit-profile', 'user-actions' );

    // Remove (sub)menu items
	if (!isset($menu) || empty($menu))
	{
		$menu = array();
	}
	
	if (function_exists('remove_menu_page'))
	{
		remove_menu_page( 'profile.php' );
    }
	
	if (function_exists('remove_submenu_page'))
	{
		remove_submenu_page( 'users.php', 'profile.php' );
	}
	
    // Deny access to the profile page and redirect upon try
    if ( 
        defined( 'IS_PROFILE_PAGE' )
        AND IS_PROFILE_PAGE
        AND ! current_user_can( 'manage_options' )
        )
    {
        wp_redirect( admin_url() );
        exit;
    }
}

add_action( 'wp_before_admin_bar_render', 'protect_tester_stop_access_profile' );
add_action( 'admin_init', 'protect_tester_stop_access_profile' );
