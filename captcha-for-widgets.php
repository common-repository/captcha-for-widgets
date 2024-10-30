<?php
/*
Plugin Name: Captcha for Widgets
Description: Use jQuery to dynamically insert a recaptcha image on forms on which you don't have control, such as plugins or widgets, subscription forms, older sites you can not upgrade etc.
Version: 0.1
Author: SimonaIlie
License: GPL2

*/

/*  Copyright 2013  Ilie Simona (email: simonailie@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * DEBUGGING method
 */
if(!function_exists('_pre')) :
function _pre($data, $clr = 'red', $die = false, $ips = array('86.124.38.14', '127.0.0.1'))
{
    if (in_array($_SERVER['REMOTE_ADDR'], $ips) ) {
        echo "<pre style='color:{$clr};'>";
        print_r($data);
        echo "</pre>";
        if($die) die();
    }
}
endif;

/*********************************************************/
/************** CONSTANTS DEFINITIONS ********************/
/*********************************************************/
if( !defined('CFW_PLUGIN_URL') )			define( 'CFW_PLUGIN_URL', trailingslashit(plugin_dir_url( __FILE__ )) );
if( !defined('CFW_PLUGIN_PATH') )			define( 'CFW_PLUGIN_PATH', trailingslashit(dirname(__FILE__)) );

if(!defined('CFW_LOC_FULL_SITE'))                       define('CFW_LOC_FULL_SITE', 1);
if(!defined('CFW_LOC_SOME_PAGES'))                      define('CFW_LOC_SOME_PAGES', 2);
 if(!defined('CFW_LOC_SOME_FORMS'))                      define('CFW_LOC_SOME_FORMS', 3);

if(!defined('CFW_API_URL'))                             define('CFW_API_URL', 'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');

global $cfw_options;
$cfw_options = get_option('cfw_options', array());
// set the option in menu
if( !function_exists('captcha_for_widgets_options_submenu') ) :
function captcha_for_widgets_options_submenu()
{
	// add sub option
	add_submenu_page('options-general.php', 'Captcha for Widgets Settings', 'Captcha for Widgets Settings', 'manage_options', 'captcha_for_widgets_options', 'captcha_for_widgets_options');
}	
endif;
add_action('admin_menu', 'captcha_for_widgets_options_submenu');

if( !function_exists('captcha_for_widgets_options') ) :
function captcha_for_widgets_options()
{
	include_once(CFW_PLUGIN_PATH . 'admin/options.php');
}
endif;

if(is_admin())
{
    // insert the options javascript
    if( !function_exists('captcha_for_widgets_admin_scripts') ) :
    function captcha_for_widgets_admin_scripts() 
    {
        global $cfw_options;
        // javascript for admin
        wp_register_script( 'cfw-admin-script', CFW_PLUGIN_URL . 'admin/js/admin-custom-script.min.js', array( 'jquery' )  );
        
        $vars = array(
            'admin_ajax_url'    => admin_url( 'admin-ajax.php' ),
            'activeTab'         => ( (!empty($cfw_options) && isset($cfw_options['from']) && !empty($cfw_options['from'])) ? $cfw_options['from'] : 'tab_recaptcha')
        );
        
        wp_localize_script( 'cfw-admin-script', 'Vars', $vars );
        wp_enqueue_script( 'cfw-admin-script' );  

        // styles for admin
        wp_register_style( 'cfw-admin-style', CFW_PLUGIN_URL . 'admin/css/custom-style.min.css', array(), time(), 'all' );  
        wp_enqueue_style( 'cfw-admin-style' );  
    }
    endif;

    add_action( 'admin_enqueue_scripts', 'captcha_for_widgets_admin_scripts' );    
} else {
    // insert the options javascript
    if( !function_exists('captcha_for_widgets_scripts') ) :
    function captcha_for_widgets_scripts() 
    {
        global $cfw_options;
        // javascript for front
        wp_register_script( 'captcha-plugin', CFW_PLUGIN_URL . 'js/jquery.mycaptcha.js', array( 'jquery' )  );
        wp_register_script( 'cfw-script', CFW_PLUGIN_URL . 'js/cfw-custom-script.min.js', array( 'jquery' )  );
        $public_cfw_options = $cfw_options;
        if(isset($public_cfw_options['captcha_private_key'])) unset($public_cfw_options['captcha_private_key']);
        $vars = array(
            'plugin_url'        => CFW_PLUGIN_URL,
            'site_url'          => trailingslashit( get_bloginfo('url') ),
            'cfw_options'       => $public_cfw_options,
            'page_id'           => get_the_ID(),
            'captcha_api'       => CFW_API_URL,
            'admin_ajax_url'    => admin_url( 'admin-ajax.php' )
        );
        wp_localize_script( 'cfw-script', 'Vars', $vars );
        
        if(isset($cfw_options['from']))
        {
            if($cfw_options['from'] == 'tab_recaptcha')
            {
                wp_register_script( 'recaptcha-api',  CFW_API_URL);
                wp_enqueue_script( 'recaptcha-api' );
            } else if ($cfw_options['from'] == 'tab_realperson')
            {
                $postfix = (isset($cfw_options['for_newer_jquery']) && $cfw_options['for_newer_jquery'] == "1") ? "new." : "";
                wp_register_script( 'realperson-plugin', CFW_PLUGIN_URL . 'js/' . $postfix . 'jquery.realperson.min.js', array('jquery'));
                wp_enqueue_script( 'realperson-plugin' );
                
                wp_register_style( 'realperson-style', CFW_PLUGIN_URL . 'js/' . $postfix . 'jquery.realperson.min.css');
                wp_enqueue_style( 'realperson-style' );
            }
        }
        
        wp_enqueue_script('captcha-plugin');
        wp_enqueue_script( 'cfw-script' );        
    }
    endif;

    add_action( 'wp_enqueue_scripts', 'captcha_for_widgets_scripts' );             
}

if(!function_exists('cfw_validate_captcha_func')) :
function cfw_validate_captcha_func()
{
    global $cfw_options;
    if(isset($cfw_options['captcha_private_key'])) {
        include_once(CFW_PLUGIN_PATH . 'lib/recaptchalib.php');

        $resp = recaptcha_check_answer ($cfw_options['captcha_private_key'],
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["challenge"],
                    $_POST["response"]);
        echo (!$resp->is_valid) ? 'Incorrect captcha' : 'OK';
    }
    die();
}
endif;

add_action( 'wp_ajax_nopriv_cfw_validate_captcha', 'cfw_validate_captcha_func' ); 

add_action( 'wp_ajax_cfw_validate_captcha', 'cfw_validate_captcha_func' ); 


if(!function_exists('cfw_load_tab_content')) :
function cfw_load_tab_content()
{    
    $act = (isset($_POST['action'])) ? $_POST['action'] : null;
    if(!is_null($act)){                      
        $tab = str_replace('tab_', '', $act);
        $file_path = CFW_PLUGIN_PATH . 'admin/' . $tab . '-settings.php';
       
        if(file_exists($file_path))
            include($file_path);
    }
    die();
}
endif;

add_action( 'wp_ajax_nopriv_tab_recaptcha', 'cfw_must_login' ); 
add_action( 'wp_ajax_tab_recaptcha', 'cfw_load_tab_content' ); 
add_action( 'wp_ajax_nopriv_tab_realperson', 'cfw_must_login' ); 
add_action( 'wp_ajax_tab_realperson', 'cfw_load_tab_content' );

if(!function_exists('cfw_must_login')) :
function cfw_must_login()
{
    if(!is_user_logged_in())
        die('You must log in first.');
}
endif;

if(!function_exists('cfw_validate_realperson_func')) :
function cfw_validate_realperson_func()
{
     echo  (rpHash($_POST['response']) == $_POST['challenge'] || rpHash64($_POST['response']) == $_POST['challenge']) ? "OK" : "Incorrect captcha.";
     
     die();
}
endif;

add_action('wp_ajax_nopriv_cfw_validate_realperson', 'cfw_validate_realperson_func');
add_action('wp_ajax_cfw_validate_realperson', 'cfw_validate_realperson_func');

if(!function_exists('rpHash')):
function rpHash($value)
{   
    $hash = 5381; 
    $value = strtoupper($value); 
    for($i = 0; $i < strlen($value); $i++) { 
        $hash = (($hash << 5) + $hash) + ord(substr($value, $i)); 
    } 
    return $hash; 
}   
endif;

if(!function_exists('rpHash64')):
function rpHash64($value)
{   
    $hash = 5381; 
    $value = strtoupper($value); 
    for($i = 0; $i < strlen($value); $i++) { 
        $hash = (leftShift32($hash, 5) + $hash) + ord(substr($value, $i)); 
    } 
    return $hash;  
}   
endif;

if(!function_exists('leftShift32')) :
function leftShift32($number, $steps) { 
    // convert to binary (string) 
    $binary = decbin($number); 
    // left-pad with 0's if necessary 
    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT); 
    // left shift manually 
    $binary = $binary.str_repeat("0", $steps); 
    // get the last 32 bits 
    $binary = substr($binary, strlen($binary) - 32); 
    // if it's a positive number return it 
    // otherwise return the 2's complement 
    return ($binary{0} == "0" ? bindec($binary) : 
        -(pow(2, 31) - bindec(substr($binary, 1)))); 
} 
endif;

    


