<?php
/**
 * This file is part of child, twentytwenty child theme.
 * All functions of this file will be loaded before of parent theme functions.
 * Learn more at https://codex.wordpress.org/Child_Themes.

 * Note: this function loads the parent stylesheet before, then child theme stylesheet
 * (leave it in place unless you know what you are doing.)
*/

// Child Theme Functions File
add_action( 'wp_enqueue_scripts', 'child_enqueue_wp_child_theme' );
function child_enqueue_wp_child_theme() {
	$parent_style = 'parent-style';
	wp_enqueue_style($parent_style, trailingslashit( get_template_directory_uri() ).'style.css' );
	wp_enqueue_style('child-css', trailingslashit( get_stylesheet_uri() ). 'style.css', array( $parent_style ), wp_get_theme()->get('Version') );
}


/**
 * Custom Image section in Customizer
 * Register Admin screen, Footer, Admin bar logo
 * Get the information about the logo.
 */
add_action( 'customize_register', function ( $wp_customize ) {

    //
    $logo_array = ['footer','admin','adminbar'];

    if( isset($logo_array) && count($logo_array)>0 ){

        foreach ($logo_array as $name) {
            $wp_customize->add_setting("my_theme_{$name}_logo", ['type'=>'theme_mod','transport'=>'refresh','height'=>325]);
            $wp_customize->add_control(
                new WP_Customize_Image_Control(
                    $wp_customize,
                    "my_theme_{$name}_logo",
                    [
                        'width'         => 250,
                        'height'        => 250,
                        'flex-height'   => true,
                        'flex-width'    => true,
                        'description' => __("Upload {$name} logo here", 'my_custom_logo'),
                        'header-text'   => array( 'site-title', 'site-description' ),
                        'selector'      => ".{$name}_logo",
                        'label'         => __(ucwords("{$name} Logo"), 'my_custom_logo'),
                        'section'       => 'options',
                        'settings'      => "my_theme_{$name}_logo",
                        'priority'      => 100, 
                    ]
                )
            );
        }        
    }
}); 

// Set admin login logo
function my_admin_login_logo() {

    $my_logo_id = get_theme_mod( 'my_theme_admin_logo' );
    $my_logo_width = '250';
    $my_logo_height = '70';
    echo '<style type="text/css"> #login h1 a, .login h1 a { background-image: url('.($my_logo_id!='' ? $my_logo_id : esc_url( get_stylesheet_directory_uri() ).'/assets/images/admin-logo.png').'); width: '.$my_logo_width.'px; height: '.$my_logo_height.'px; max-width: 100%; background-size: '.$my_logo_width.'px '.$my_logo_height.'px; background-repeat: no-repeat;}
    </style>';
}
add_action( 'login_enqueue_scripts', 'my_admin_login_logo' );

// Changing the logo link from wordpress.org to your site
function mb_login_url() { return home_url(); }
add_filter( 'login_headerurl', 'mb_login_url' );

// Changing the alt text on the logo to show your site name
function mb_login_title() { return get_option( 'blogname' ); }
add_filter( 'login_headertext', 'mb_login_title' );
/* End Logo Customizer Section */

/* 
* Register my theme menu
*/
function register_my_menu() {
    $location = array(
            'optional' => __( 'Optional Menu', 'my_additional_menus' ),
        );
    register_nav_menus( $location );
}
add_action( 'init', 'register_my_menu' );


/*
* Options Page (ACF itself uses a check to see if the framework has been loaded.)
*/
if ( class_exists('ACF') ) {
    add_action('acf/init', 'my_acf_init');
    function my_acf_init() {
        if( function_exists('acf_add_options_page') ) {        
            $parent = acf_add_options_page(array(
                'page_title'    => __('Theme General Settings', 'my_acf_settings'),
                'menu_title'    => __('Theme Settings', 'my_acf_settings'),
                'menu_slug'     => 'theme-general-settings',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'autoload'      => true
            ));        
            
            /*acf_add_options_sub_page(array(
                'page_title'    => __('Theme Header Settings', 'my_acf_settings'),
                'menu_title'    => __('Header', 'my_acf_settings'),
                'parent_slug'   => $parent['menu_slug'],
            ));*/
            
            /*acf_add_options_sub_page(array(
                'page_title'    => __('Theme Footer Settings', 'my_acf_settings'),
                'menu_title'    => __('Footer', 'my_acf_settings'),
                'parent_slug'   => $parent['menu_slug'],
            ));*/        
        }
    }
}


/**
 * Register widget areas.
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function register_my_widget() {
    register_sidebar(array(
        'name' => __('Additional Widget', 'my_additional_widgets'),
        'description' => __('Additional widget section', 'my_additional_widgets'),
        'id' => 'additional-sidebar',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
        'before_widget' => '<div class="additional-widget %2$s">',
        'after_widget' => '</div>'
    ));
}
add_action( 'widgets_init', 'register_my_widget' );
// add_action( 'init', 'my_register_widget' );



