<?php

if (!class_exists('LH_web_application_menu_class')) {


class LH_web_application_menu_class extends LH_web_application_plugin {
    
    

public function enqueue_admin_scripts(){
        // Isn't it nice to use dependencies and the already registered core js files?
wp_enqueue_script('jquery');
wp_enqueue_style( 'wp-color-picker' );

$file = plugin_dir_path( __FILE__ ) . 'menu_assets/scripts/colourpicker.js';

wp_enqueue_script( parent::return_plugin_namespace().'-colour_picker',plugins_url( 'menu_assets/scripts/colourpicker.js', __FILE__ ), array( 'wp-color-picker','jquery' ), filemtime($file), true );



	wp_enqueue_media();


wp_register_script(parent::return_plugin_namespace().'-dashboard-admin', plugins_url( '/scripts/uploader.js', __FILE__ ), array('jquery','media-upload','thickbox'), filemtime(plugin_dir_path( __FILE__ ) . '/scripts/uploader.js'));

wp_enqueue_script(parent::return_plugin_namespace().'-dashboard-admin');




}
    
    
public function load_admin_scripts(){
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
    add_action( 'admin_enqueue_scripts', array($this,'enqueue_admin_scripts'));
    }

    
public function plugin_menu() {
    
    


$menu = add_options_page('LH Web Application', 'Web Application', 'manage_options', parent::return_file_name(), array($this,"plugin_options"));

        // Load the JS conditionally
        add_action( 'load-' . $menu, array($this,'load_admin_scripts') );
        




}


public function plugin_options() {


if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

if( isset($_POST[ parent::return_plugin_namespace()."-nonce" ]) && wp_verify_nonce($_POST[ parent::return_plugin_namespace()."-nonce" ], parent::return_plugin_namespace()."-nonce" )) {
    

if ($_POST[ $this->short_name_field_name ] != ""){
$options[ $this->short_name_field_name ] = sanitize_text_field($_POST[ $this->short_name_field_name]);
}


if (isset($_POST[ $this->manifest_icon_attachment_id ]) && !empty($_POST[ $this->manifest_icon_attachment_id ]) && wp_attachment_is_image($_POST[ $this->manifest_icon_attachment_id ])){



if (update_option( $this->manifest_icon_attachment_id, $_POST[ $this->manifest_icon_attachment_id ] )){
    
    
    ?>
<div class="updated"><p><strong><?php _e('Manifest Icon ID Updated', parent::return_plugin_namespace() ); ?></strong></p></div>
<?php
    
    
}



}

if (isset($_POST[ $this->ios_icon_attachment_id ]) && !empty($_POST[ $this->ios_icon_attachment_id ]) && wp_attachment_is_image($_POST[ $this->ios_icon_attachment_id ])){



if (update_option( $this->ios_icon_attachment_id, $_POST[ $this->ios_icon_attachment_id ] )){
    
    
    ?>
<div class="updated"><p><strong><?php _e('Ios Icon ID Updated', parent::return_plugin_namespace() ); ?></strong></p></div>
<?php
    
    
}



}



if (isset($_POST[ parent::return_is_maskable_icon_name() ]) ){



if (update_option( parent::return_is_maskable_icon_name(), sanitize_text_field(trim($_POST[ parent::return_is_maskable_icon_name() ] )))){
    
?><div class="updated"><p><strong><?php _e('Maskable value updated', parent::return_plugin_namespace() ); ?></strong></p></div><?php
    
}



}

if (isset($_POST[ parent::return_shortcut_menu_name() ]) ){



if (update_option( parent::return_shortcut_menu_name(), sanitize_text_field(trim($_POST[ parent::return_shortcut_menu_name() ] )))){
    
?><div class="updated"><p><strong><?php _e('Shortcut Menu updated', parent::return_shortcut_menu_name() ); ?></strong></p></div><?php
    
}



}


if (isset($_POST[ $this->ios_startup_attachment_id ]) && !empty($_POST[ $this->ios_startup_attachment_id ]) && wp_attachment_is_image($_POST[ $this->ios_startup_attachment_id ])){



if (update_option( $this->ios_startup_attachment_id, $_POST[ $this->ios_startup_attachment_id ] )){
    
    
    ?>
<div class="updated"><p><strong><?php _e('Ios Startup Image ID Updated', parent::return_plugin_namespace() ); ?></strong></p></div>
<?php
    
    
}



}




if (($_POST[ $this->display_mode_field_name ] == "browser") or ($_POST[ $this->display_mode_field_name ] == "fullscreen") or ($_POST[ $this->display_mode_field_name ] == "standalone") or ($_POST[ $this->display_mode_field_name ] == "minimal-ui")){
    

$options[ $this->display_mode_field_name ] = sanitize_text_field($_POST[ $this->display_mode_field_name]);


}


if (($_POST[$this->apple_web_app_capable_field_name] == "0") || ($_POST[$this->apple_web_app_capable_field_name] == "1")){
$options[$this->apple_web_app_capable_field_name] = $_POST[ $this->apple_web_app_capable_field_name ];
}



if (isset($_POST[ $this->offline_page_field_name ]) and ($_POST[ $this->offline_page_field_name ] != "") and ($page = get_page(sanitize_text_field($_POST[ $this->offline_page_field_name ])))){



$options[ $this->offline_page_field_name ] = sanitize_text_field($_POST[ $this->offline_page_field_name ]);

}

if (isset($_POST[ $this->theme_color_field_name]) and ($_POST[ $this->theme_color_field_name ] != "")){

$options[ $this->theme_color_field_name ] = sanitize_text_field($_POST[ $this->theme_color_field_name]);

}




if (isset($_POST[ $this->background_color_field_name]) and ($_POST[ $this->background_color_field_name ] != "")){

$options[ $this->background_color_field_name ] = sanitize_text_field($_POST[ $this->background_color_field_name]);

}



?>
<div class="updated"><p><strong><?php _e('Service worker updated', parent::return_plugin_namespace() ); ?></strong></p></div>
<?php




if (update_option( self::return_opt_name(), $options )){
    



$this->options = get_option(self::return_opt_name());




?>
<div class="updated"><p><strong><?php _e('Values saved', parent::return_plugin_namespace() ); ?></strong></p></div>
<?php




    } 




}

// Now display the settings editing screen

include ('partials/option-settings.php');


}
    
public function __construct() {
    
parent::__construct();
    
//add backend options page
add_action('admin_menu', array($this,"plugin_menu")); 
    
}
    
    
}

$lh_web_application_menu_class_instance = new LH_web_application_menu_class();


}