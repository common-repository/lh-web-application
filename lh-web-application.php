<?php
/**
 * Plugin Name: LH Web Application
 * Description: Make your WordPress sites into a modern web application.
 * Plugin URI: https://lhero.org/portfolio/lh-web-application/
 * Version: 1.28
 * Author: Peter Shaw
 * Text Domain: lh_web_application
 * Author URI: https://shawfactor.com
 * License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* LH Web Application plugin class
*/



if (!class_exists('LH_web_application_plugin')) {

class LH_web_application_plugin {

var $options;

var $menu_name = 'lh_web_application-cache_menu';
var $web_application_icon_image = 'lh_web_application-icon';
var $manifest_icon_attachment_id = 'lh_web_application-manifest_icon_attachment_id';
var $ios_icon_attachment_id = 'lh_web_application-ios_icon_attachment_id';
var $ios_startup_attachment_id = 'lh_web_application-ios_startup_attachment_id';
var $short_name_field_name = 'lh_web_application-short_name_field_name';
var $offline_page_field_name = 'lh_web_application-offline_page_field_name';
var $theme_color_field_name = 'lh_web_application-theme_color_field_name';
var $background_color_field_name = 'lh_web_application-background_color_field_name';
var $display_mode_field_name = 'lh_web_application-display_mode_field_name';
var $webapp_prompt_logon_field_name = 'lh_web_application-webapp_prompt_logon_field_name';
var $apple_web_app_capable_field_name = 'lh_web_application-apple_web_app_capable';

private static $instance;

static function return_plugin_namespace(){

    return 'lh_web_application';

    }
    
    
static function return_opt_name(){

return 'lh_web_application-options';

}

static function return_file_name(){

return plugin_basename( __FILE__ );

}

    
static function return_offline_page_field_name(){

    return 'lh_web_application-offline_page_field_name';

    }
    
    
static function return_manifest_icon_name(){

    return 'lh_web_application-manifest_icon';

    }
    
static function return_ios_icon_name(){

    return 'lh_web_application-ios_icon';

    }
    
static function return_ios_startup_name(){

    return 'lh_web_application-ios_startup';

    }
    
static function return_shortcut_menu_name(){

    return 'lh_web_application-shortcut_menu';

    }
    
static function return_is_maskable_icon_name(){

    return 'lh_web_application-is_maskable_icon';

    }
    
static function return_menu_icon_name(){

    return 'lh_web_application-menu_icon';

    }

static function return_image_extensions(){
    
    $extensions = array('gif','png','jpg','jpeg');
    
    return $extensions;
    
}

static function has_elegible_image_extension($url){
    
$extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    
if (in_array($extension, self::return_image_extensions())){
    
    return true;
    
} else {
    
    
    return false;
    
}
    
    
}
    
static function return_local_host(){
         
         $url = parse_url(get_site_url());
         
         return $url['host'];
         
     }
    
static function return_cookieless_image_host(){
    

         
         $url = parse_url('https://i0.wp.com/');
         
         
         
         if (!empty($url['host'])){
             
         
         return $url['host'];
         
         } else {
             
             return false;
             
         }
         
     }
     
static function build_url(array $parts) {
    return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . 
        ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . 
        (isset($parts['user']) ? "{$parts['user']}" : '') . 
        (isset($parts['pass']) ? ":{$parts['pass']}" : '') . 
        (isset($parts['user']) ? '@' : '') . 
        (isset($parts['host']) ? "{$parts['host']}" : '') . 
        (isset($parts['port']) ? ":{$parts['port']}" : '') . 
        (isset($parts['path']) ? "{$parts['path']}" : '') . 
        (isset($parts['query']) ? "?{$parts['query']}" : '') . 
        (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
}
    
static function maybe_return_new_image_url($src, $dimensions = null){

    
    
        if (($url = parse_url($src)) && isset($url['host']) && self::has_elegible_image_extension($src) && (strpos($url['host'], self::return_local_host()) !== false)){
        
        $url['path'] = '/'.$url['host'].$url['path'];
         
        
        
        $url['host'] = str_replace( self::return_local_host() , self::return_cookieless_image_host(), $url['host'] );
        
        $url = self::build_url($url);
        
        if ((strpos(self::return_cookieless_image_host(), 'wp.com') !== false) && !empty($dimensions)){
            
        return add_query_arg( "resize", $dimensions[0].','.$dimensions[1] , $url );
            
        } else {
            
         return $url;   
            
        }
        
        
        
        
        } else {
            
            
            return false;
            
            
        }
    
    
    
    
    
}
    
static function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
    
static function get_last_modified(){
    
global $wpdb;

$wp_last_modified_date = $wpdb->get_var("SELECT GREATEST(post_modified_gmt, post_date_gmt) d FROM $wpdb->posts WHERE post_status = 'publish' ORDER BY d DESC LIMIT 1");
$wp_last_modified_date = max($wp_last_modified_date, get_lastcommentmodified('GMT'));
			
$last_modified = mysql2date('D, d M Y H:i:s', $wp_last_modified_date, 0) . ' GMT';
    
return $last_modified;   
    
    
    
}




static function isValidURL($url){ 

return (bool)parse_url($url);

}

static function return_sw_file_name(){
    
    return 'serviceworker.script';
    
}

static function return_manifest_file_name(){
    
    return 'pwa-manifest.json';
    
}

static function return_child_style_url(){
    
$theme = wp_get_theme();
    
return add_query_arg( 'version', $theme->Version , get_stylesheet_uri());   
    
    
}

static function get_script_src_by_handle($handle) {
    global $wp_scripts;
    
    if(!empty($wp_scripts->registered[$handle]->src)) {
        
        return add_query_arg('ver', $wp_scripts->registered[$handle]->ver, $wp_scripts->registered[$handle]->src);
    
    } else {
        
        return false;
        
    }
}


static function get_style_src_by_handle($handle) {
    global $wp_styles;
    
    if(!empty($wp_styles->registered[$handle]->src)) {
        
    return add_query_arg('ver', $wp_styles->registered[$handle]->ver, $wp_styles->registered[$handle]->src);
    
    } else {
        
        return false;
        
    }
}

static function get_import_scripts_array() {

$handles = array(self::return_plugin_namespace().'-fetch_handler','localforage');

$handles = apply_filters(self::return_plugin_namespace().'_import_scripts_handle_filter', $handles);

$scripts_array = array();

foreach ( $handles as $handle ) {
    
if ($src = self::get_script_src_by_handle($handle)){
    
$scripts_array[] = $src;
    
}
    
    
}

return $scripts_array;

}

static function return_precache_static_urls_as_array(){
   
    global $wp_scripts;
    
    $precache_static_urls = array();
    
     foreach( $wp_scripts->queue as $script ) {
         
         if (!empty($wp_scripts->registered[$script]->src) && self::isValidURL($wp_scripts->registered[$script]->src)){
         
     $precache_static_urls[] = apply_filters('script_loader_src', add_query_arg('ver', $wp_scripts->registered[$script]->ver, $wp_scripts->registered[$script]->src), $script);
     
         }
     
    }
    
    global $wp_styles;
    
     foreach( $wp_styles->queue as $style ) {
         
         if (!empty($wp_styles->registered[$style]->src) && self::isValidURL($wp_styles->registered[$style]->src)){
         
     $precache_static_urls[] = apply_filters('style_loader_src', add_query_arg('ver', $wp_styles->registered[$style]->ver, $wp_styles->registered[$style]->src), $style); 
     
         }
     
    }
    
    
    
    
    return apply_filters(self::return_plugin_namespace().'_precache_static_urls_filter', $precache_static_urls);
    
    
}

static function is_web_app(){
    
if (isset($_SERVER['HTTP_USER_AGENT']) and strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"iphone")) {

if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"safari")) {
     
     return false;
     
   } else {
       
      return true;

   }

    
} else {

return false;

}
    
    
    
}

static function return_shorcut_menu_array(){
    
if (empty(get_option(self::return_shortcut_menu_name()))){
    
    return false;
    
    
}
 
$the_menu = wp_get_nav_menu_object(get_option(self::return_shortcut_menu_name())); 



$menu_items = wp_get_nav_menu_items($the_menu->term_id);

$menu_array = array();

foreach( $menu_items as $menu_item ){ 

$menu_array[] = wp_setup_nav_menu_item($menu_item);

}

$shortcut_array = array();

foreach( $menu_array as $menu_member ){ 
    
    $object_id = (int) $menu_member->object_id; //object ID.
    
    $add['name'] = $menu_member->title;
    $add['short_name'] = $menu_member->title;
    $add['description'] = $menu_member->description;
    $add['url'] = $menu_member->url;
    
    $attachment_id = get_post_meta( $menu_member->ID, '_'.self::return_menu_icon_name().'-attachment_id', true );
    
    if (!empty($attachment_id)){
        
        $mime = get_post_mime_type( $attachment_id );
       $full_url = wp_get_attachment_url($attachment_id);
        
        foreach( self::create_manifest_icon_sizes() as $size ){
            
        $icon['src'] = self::maybe_return_new_image_url($full_url, array($size['width'], $size['height']));
        $icon['sizes']= $size['width'].'x'.$size['height'];
        $icon['type'] = $mime;   
        
      $add['icons'][] = $icon;
      
      unset($icon);
            
            
        }
        
        
    }
    
  
    


$shortcut_array[] = $add;

unset($add);
    
}

if (!empty($shortcut_array)){
    
    return $shortcut_array;

    
} else {
    
    return false;
    
}
  
    
    
    
    
}



protected function return_manifest_url(){
    
return home_url('/'.self::return_manifest_file_name());
    

    
}

protected function return_manifest_icon_id(){
    
$manifest_icon_attachment_id = get_option($this->manifest_icon_attachment_id);

if (!wp_attachment_is_image($manifest_icon_attachment_id)){
    
$site_icon = get_option('site_icon');

return $site_icon;
    
    
} else {
    
    return $manifest_icon_attachment_id;
}


    
}

protected function return_ios_icon_id(){
    
$ios_icon_attachment_id = get_option($this->ios_icon_attachment_id);


if (!wp_attachment_is_image($ios_icon_attachment_id)){
    
$site_icon = get_option('site_icon');

return $site_icon;
    
    
} else {
    
    return $ios_icon_attachment_id;
}


    
}

protected function return_ios_startup_id(){
    
$ios_startup_attachment_id = get_option($this->ios_startup_attachment_id);

if (wp_attachment_is_image($ios_startup_attachment_id)){
    


return $ios_startup_attachment_id;
    
    
} else {
    
    return false;
}


    
}

protected function get_webb_app_background_colour(){
    
$theme_back = get_theme_mod( 'background_color' );
    
if (isset($this->options[ $this->background_color_field_name ]) and !empty($this->options[$this->background_color_field_name ])){
    
    return $this->options[ $this->background_color_field_name ];
    

    
} elseif (isset($theme_back) and !empty($theme_back)) {
    
   return $theme_back; 
    
    } else {
        
        return false;
        
    }
    
}

static function is_new_file($text, $target_file){
    
    
$file_contents = file_get_contents($target_file, FILE_USE_INCLUDE_PATH);

if ((wp_hash($text)) == (wp_hash($file_contents))){
    
    return false;
    
    
} else {
    
  return true;
      
    
    
}


    
    
}


static function get_queue_tablename(){    

global $wpdb;
return $wpdb->prefix."lh_web_application_subscriptions";    
    
}




static function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}




private function handle_start_url_requests(){
    
    
    

    
    
if(isset($_COOKIE['lh_web_application-ios_session']) && !is_home() && !isset($_GET['relatedposts']) && !isset($_GET['version']) && !is_feed()) {
    
        //print_r($_COOKIE);
    
setcookie(self::return_plugin_namespace().'-current_url', self::curpageurl(), time() + (86400 * 30), "/");

    
} elseif (isset($_COOKIE[self::return_plugin_namespace().'-current_url']) && !isset($_COOKIE['lh_web_application-ios_session']) && is_home() && (self::curpageurl() != $_COOKIE[self::return_plugin_namespace().'-current_url'])){
    

    
$url = $_COOKIE[self::return_plugin_namespace().'-current_url'];

if ( wp_redirect( $url ) ) {
    exit;
}
     
     
     
}   
    
    
}



protected function return_sw_url(){
    
return trailingslashit(get_site_url()).self::return_sw_file_name();
    
    
}


static function return_start_url(){
    
    //$start_url = add_query_arg( self::return_plugin_namespace().'-start_mode', $this->return_start_mode(), home_url( '/' ) );
    
   //$start_url = add_query_arg('utm_source', 'homepage', $start_url );
    
    
$start_url = home_url( '/' ).'?start_url=1';
    
//$start_url = '/';

//$start_url = home_url( '/' );
    
    return $start_url;
    
}

protected function return_start_mode(){

if (isset($this->options[$this->display_mode_field_name])){

return $this->options[$this->display_mode_field_name];

} else {

return 'browser';

}

}

protected function maybe_fix_protocol_relative_url($url){
    
   if (strpos($url,'//') === 0){
       
    $url =  'https:'.$url;
     
       
   }
 
 return $url;   

}



protected function rel2abs( $rel, $base ){
    /* return if already absolute URL */
    if( parse_url($rel, PHP_URL_SCHEME) != '' )
        return( $rel );

    /* queries and anchors */
    if( $rel[0]=='#' || $rel[0]=='?' )
        return( $base.$rel );

    /* parse base URL and convert to local variables:
       $scheme, $host, $path */
    extract( parse_url($base) );

if (isset($path)){

    /* remove non-directory element from path */
    $path = preg_replace( '#/[^/]*$#', '', $path );

    /* destroy path if relative url points to root */
    if( $rel[0] == '/' )
        $path = '';

} else {

$path = '';

}

    /* dirty absolute URL */
    $abs = '';

    /* do we have a user in our URL? */
    if( isset($user) )
    {
        $abs.= $user;

        /* password too? */
        if( isset($pass) )
            $abs.= ':'.$pass;

        $abs.= '@';
    }

    $abs.= $host;

    /* did somebody sneak in a port? */
    if( isset($port) )
        $abs.= ':'.$port;

    $abs.=$path.'/'.$rel;

    /* replace '//' or '/./' or '/foo/../' with '/' */
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for( $n=1; $n>0; $abs=preg_replace( $re, '/', $abs, -1, $n ) ) {}

    /* absolute URL is ready! */
    return( $scheme.'://'.$abs );
}


protected function IsResourceLocal($url){
    if( empty( $url ) ){ return false; }
    $urlParsed = parse_url( $url );
  
  
  if (isset($urlParsed['host'])){ $host = $urlParsed['host']; }

    if(!isset($host) or empty($host) ){ 
    /* maybe we have a relative link like: /wp-content/uploads/image.jpg */
    /* add absolute path to begin and check if file exists */
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    $maybefile = $doc_root.$url;
    /* Check if file exists */
    $fileexists = file_exists ( $maybefile );
    if( $fileexists ){
        /* maybe you want to convert to full url? */
        return true;        
        }
     }
    /* strip www. if exists */
    $host = str_replace('www.','',$host);
    $thishost = $_SERVER['HTTP_HOST'];
    /* strip www. if exists */
    $thishost = str_replace('www.','',$thishost);
    if( $host == $thishost ){
        return true;
        }
    return false;
}
  
  
  
/**
* Convert an URL to a relative path
 *
 * @param string $src URL
 *
 * @return string mixed relative path
*/

protected function url_to_relative_path($src) {
  
  if ($this->IsResourceLocal($src)){
		    return '//' === substr($src, 0, 2) ? preg_replace('/^\/\/([^\/]*)\//', '/', $src) : preg_replace('/^http(s)?:\/\/[^\/]*/', '', $src);
	
  } else {
	
	return false;
	
  }
		}


protected function djb_hash($str) {
  for ($i = 0, $h = 5381, $len = strlen($str); $i < $len; $i++) {
    $h = (($h << 5) + $h + ord($str[$i])) & 0x7FFFFFFF;
  }
  return $h;
}

protected function get_image_sizes( $size = '' ) {

        global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {

                if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

                        $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                        $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                        $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

                } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

                        $sizes[ $_size ] = array( 
                                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                        );

                }

        }

        // Get only 1 size if found
        if ( $size ) {

                if( isset( $sizes[ $size ] ) ) {
                        return $sizes[ $size ];
                } else {
                        return false;
                }

        }

        return $sizes;
}


protected static function create_manifest_icon_sizes() {


$icon_sizes[0] = array('height' => '16','width' => '16');
$icon_sizes[1] = array('height' => '48','width' => '48');
$icon_sizes[2] = array('height' => '128','width' => '128');
$icon_sizes[3] = array('height' => '144','width' => '144');
$icon_sizes[4] = array('height' => '192','width' => '192');
$icon_sizes[5] = array('height' => '512','width' => '512');

return $icon_sizes;

}



protected function create_apple_icon_sizes() {

$apple_icon_sizes[0] = array('height' => '57','width' => '57');
$apple_icon_sizes[1] = array('height' => '72', 'width' => '72');
$apple_icon_sizes[2] = array('height' => '114', 'width' => '114');
$apple_icon_sizes[3] = array('height' => '144', 'width' => '144');
$apple_icon_sizes[4] = array('height' => '152', 'width' => '152');
$apple_icon_sizes[5] = array('height' => '167', 'width' => '167');
$apple_icon_sizes[6] = array('height' => '180', 'width' => '180');

return $apple_icon_sizes;

}

protected function create_apple_startup_sizes() {

$apple_startup_details[0] = array('height' => '1136','width' => '640', 'media' => '(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)');
$apple_startup_details[1] = array('height' => '1294', 'width' => '750', 'media' => '(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)');
$apple_startup_details[2] = array('height' => '2148', 'width' => '1242', 'media' => '(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)');
$apple_startup_details[3] = array('height' => '2436', 'width' => '1125', 'media' => '(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)');
$apple_startup_details[4] = array('height' => '2048', 'width' => '1536', 'media' => '(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)');
$apple_startup_details[5] = array('height' => '2224', 'width' => '1668', 'media' => '(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)');
$apple_startup_details[6] = array('height' => '2732', 'width' => '2048', 'media' => '(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)');

return $apple_startup_details;

}




protected function check_image_size($id, $type, $width, $height){


$size = $type.'_'.$width.'x'.$height;



$imagedata = wp_get_attachment_image_src( $id, $size );


if (isset($imagedata[0]) && self::isValidURL($imagedata[0]) && ($width == $imagedata[1]) && ($height == $imagedata[2])){

    
return $imagedata[0];



} else {


return false;


}




}
  
  
 
  
  
protected function register_scripts_and_styles() {

if (!is_admin()){
    
if (!class_exists('LH_Register_file_class')) {
     

     
    include_once('includes/lh-register-file-class.php');
    
}





$add_array = array('defer="defer"');
$add_array[] = 'id="localforage"';

$localforage = new LH_Register_file_class( 'localforage', plugin_dir_path( __FILE__ ).'scripts/localforage.min.js', plugins_url( '/scripts/localforage.min.js', __FILE__ ), true, array(), true, $add_array);

unset($add_array);


wp_enqueue_script('localforage');




$add_array = array('id="'.self::return_plugin_namespace().'-main-js"');

$sw_url  =  add_query_arg( 'version', wp_hash(self::return_service_worker_as_string()), home_url('/'.self::return_sw_file_name() ));


$add_array[] = 'defer="defer"';
$add_array[] = 'data-sw-url="'.$sw_url.'"';


$lh_web_application_core_script = new LH_Register_file_class(  self::return_plugin_namespace().'-script', plugin_dir_path( __FILE__ ).'scripts/lh-web-application-main.js',plugins_url( '/scripts/lh-web-application-main.js', __FILE__ ), true, array(), true, $add_array);

unset($add_array);

wp_enqueue_script( self::return_plugin_namespace().'-script');


$add_array = array('id="'.self::return_plugin_namespace().'-addtohomescreen_script"','defer="defer"');


$lh_web_application_addtohomescreen_js = new LH_Register_file_class(self::return_plugin_namespace().'-addtohomescreen_script',plugin_dir_path( __FILE__ ).'scripts/addtohomescreen.js', plugins_url( '/scripts/addtohomescreen.js', __FILE__ ), true, array(), true, $add_array);

unset($add_array);





$add_array = array('id="'.self::return_plugin_namespace().'-fetch_handler"');

$lh_web_application_fetch_handler_script = new LH_Register_file_class(self::return_plugin_namespace().'-fetch_handler',plugin_dir_path( __FILE__ ).'scripts/fetch-handler.js', plugins_url( '/scripts/fetch-handler.js', __FILE__ ), true, array(), true, $add_array);

unset($add_array);



$add_array = array();


$lh_web_application_addtohomescreen_css = new LH_Register_file_class(self::return_plugin_namespace().'-addtohomescreen_css', plugin_dir_path( __FILE__ ).'styles/addtohomescreen.css', plugins_url( '/styles/addtohomescreen.css', __FILE__ ), false, array(), true, $add_array);


unset($add_array);



}	

}


protected function return_cache_menu_items(){

$items = array();
 
if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $this->menu_name ] ) ) {

    $menu = wp_get_nav_menu_object( $locations[ $this->menu_name ] );
 
    $menu_items = wp_get_nav_menu_items($menu->term_id);

foreach ( (array) $menu_items as $key => $menu_item ) {

$items[] = $menu_item->url;

}
}
    return $items;
}

protected function return_manifest_json_as_var(){
    
$json['name'] = get_bloginfo('name');


$json['description'] = get_bloginfo('description');
$json['orientation'] = 'any';



$json['scope'] = '/';

$json['display'] = $this->return_start_mode();


if(isset($this->options[$this->short_name_field_name])){ $json['short_name'] = $this->options[$this->short_name_field_name]; }


//record the start_url with appropriate flag to indicate web app



$json['start_url'] = self::return_start_url();


  if (!empty($this->options[ $this->theme_color_field_name ])){
      
      $json['theme_color'] = $this->options[ $this->theme_color_field_name ];
      

      
  }
  
if (!empty($this->get_webb_app_background_colour())){
     
   $json['background_color'] = $this->get_webb_app_background_colour();  
     
     
 } else {
     
     
$background_color = get_background_color();

if (!empty($background_color)){


$json['background_color'] = 'Seashell';

}
     
     
 }
  
  
  





$mime = get_post_mime_type( $this->return_manifest_icon_id() );



$i = 0;




foreach( self::create_manifest_icon_sizes() as $size ){



if ($href = $this->check_image_size($this->return_manifest_icon_id(),self::return_manifest_icon_name(), $size['width'], $size['height'])){

$json['icons'][$i]['src'] = $href;
$json['icons'][$i]['sizes']= $size['width'].'x'.$size['height'];
$json['icons'][$i]['type'] = $mime;

if (get_option(self::return_is_maskable_icon_name()) == 1){
    
$json['icons'][$i]['purpose'] = "any maskable";
    
}

$i++;


}



}

if (self::return_shorcut_menu_array()){
    
$json['shortcuts'] = self::return_shorcut_menu_array();  
    
    
    
}



return apply_filters( 'lh_web_application_manifest_json_filter', $json);


    

}

protected function return_service_worker_as_string(){
    
ob_start();

include ('partials/sw4.php'); 

$contents = ob_get_contents();

ob_end_clean();
        
return $contents;
    
}


  public function register_menu() {
  register_nav_menu($this->menu_name,__( 'Service worker cache list' ));
}





public function add_meta_to_head() {



echo "\n<!-- Start LH lh Web Application Meta -->\n";

echo "<meta name=\"viewport\" content=\"viewport-fit=cover,width=device-width, initial-scale=1.0\" />\n";


if (!empty($this->options[$this->apple_web_app_capable_field_name]) && $this->options[$this->apple_web_app_capable_field_name] == 1){

echo '<meta name="apple-mobile-web-app-capable" content="yes" />';

echo "\n";

}


if (!empty($this->options[ $this->theme_color_field_name ])){
    
echo '<meta name="theme-color" content="'.$this->options[ $this->theme_color_field_name ].'" />';

echo "\n";
    
    
}


if (isset($this->options[$this->apple_web_app_capable_field_name]) and $this->options[$this->apple_web_app_capable_field_name] == 1){
  


echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />';
echo "\n";
  
  }
 
  
  
echo '<link rel="manifest" href="'.$this->return_manifest_url().'" crossorigin="use-credentials" />';

echo "\n";

foreach( $this->create_apple_icon_sizes() as $apple_size ){
    
    
    if ($href = $this->check_image_size($this->return_ios_icon_id(),self::return_ios_icon_name(), $apple_size['width'], $apple_size['height'])){
    
    
    echo '<link rel="apple-touch-icon" sizes="'.$apple_size['width'].'x'.$apple_size['height'].'" href="'.$href.'" />';
    
    echo "\n";
    
    }
    
    
}

foreach( $this->create_apple_startup_sizes() as $startup_size ){
    
    
    if ($href = $this->check_image_size($this->return_ios_startup_id(),self::return_ios_startup_name(), $startup_size['width'], $startup_size['height'])){
    
    
    echo '<link rel="apple-touch-startup-image" href="'.$href.'" media="'.$startup_size['media'].'" />';
    
    echo "\n";
    
    }
    
    
}

echo "<!-- End LH Web Application Meta -->\n\n";



}


public function on_activate($network_wide) {

    if ( is_multisite() && $network_wide ) { 

    $args = array('number' => 500, 'fields' => 'ids');
        
    $sites = get_sites($args);
    
            foreach ($sites as $blog_id) {
            switch_to_blog($blog_id);
wp_schedule_single_event(time(), 'lh_web_application_initial_run');
            restore_current_blog();
        } 

    } else {

wp_schedule_single_event(time(), 'lh_web_application_initial_run');

}

}





public function add_subsizes($new_sizes, $image_meta, $attachment_id){
    
if ($attachment_id == $this->return_manifest_icon_id()){
    
foreach( self::create_manifest_icon_sizes() as $size ){
    
    $new_sizes[self::return_manifest_icon_name().'_'.$size['width'].'x'.$size['height']]['width'] = $size['width'];
    
    $new_sizes[self::return_manifest_icon_name().'_'.$size['width'].'x'.$size['height']]['height'] = $size['height'];
    
    $new_sizes[self::return_manifest_icon_name().'_'.$size['width'].'x'.$size['height']]['crop'] = 1;
    
}

//wp_mail( 'shawfactor@gmail.com', 'new manifest sizes', print_r($new_sizes, true));

}


if ($attachment_id == $this->return_ios_icon_id()){


foreach( $this->create_apple_icon_sizes() as $apple_icon_size ){
    
        $new_sizes[self::return_ios_icon_name().'_'.$apple_icon_size['width'].'x'.$apple_icon_size['height']]['width'] = $apple_icon_size['width'];
    
        $new_sizes[self::return_ios_icon_name().'_'.$apple_icon_size['width'].'x'.$apple_icon_size['height']]['height'] = $apple_icon_size['height'];
    
        $new_sizes[self::return_ios_icon_name().'_'.$apple_icon_size['width'].'x'.$apple_icon_size['height']]['crop'] = 1;
    
    
}

//wp_mail( 'shawfactor@gmail.com', 'new ios icon sizes', print_r($new_sizes, true));



}

if ($attachment_id == $this->return_ios_startup_id()){
    
    foreach( $this->create_apple_startup_sizes() as $apple_startup_size ){
        
        
        $new_sizes[self::return_ios_startup_name().'_'.$apple_startup_size['width'].'x'.$apple_startup_size['height']]['width'] = $apple_startup_size['width'];
    
        $new_sizes[self::return_ios_startup_name().'_'.$apple_startup_size['width'].'x'.$apple_startup_size['height']]['height'] = $apple_startup_size['height'];
    
        $new_sizes[self::return_ios_startup_name().'_'.$apple_startup_size['width'].'x'.$apple_startup_size['height']]['crop'] = 1;
        
        
        
        
    }
    
//wp_mail( 'shawfactor@gmail.com', 'new ios startup sizes', print_r($new_sizes, true));    



}





    

  
  return $new_sizes;
    
    
}

	/** Add public query vars
	*	@param array $vars List of current public query vars
	*	@return array $vars 
	*/
	public function add_query_vars($vars){
		$vars[] = '__lh_web_application-serviceworker';
		$vars[] = '__lh_web_application-manifest';
		return $vars;
	}



	/**	Sniff Requests
	*	This is where we hijack all API requests
	* 	If $_GET['__lh_web_application-serviceworker'] is set, we kill WP and serve up serviceworker awesomeness
	*	@return die if API request
	*/
public function sniff_requests(){
		global $wp;
		global $wp_super_cache_comments;

if (isset($wp->query_vars['__lh_web_application-serviceworker'])){
    
$wp_super_cache_comments = false;
$string = $this->return_service_worker_as_string();
$hash = wp_hash($string);
    
header('Content-Type: application/javascript');

header("Etag: $hash"); 
    
echo $string;
    

exit;

} elseif (isset($wp->query_vars['__lh_web_application-manifest'])){
    
$wp_super_cache_comments = false;

add_filter( 'wp_cache_eof_tags', array($this, 'cache_request'));

header('Content-type: application/manifest+json');
header("Access-Control-Allow-Origin: *");

echo wp_json_encode($this->return_manifest_json_as_var());

exit;    
    
}


	}
	
/** Add API Endpoint
	*	This is where the magic happens - brush up on your regex skillz
	*	@return void
	*/

public function add_endpoint(){

add_rewrite_rule('serviceworker\.script$','index.php?__lh_web_application-serviceworker=1','top');
add_rewrite_rule('pwa-manifest\.json$','index.php?__lh_web_application-manifest=1','top');

}


public function init_hook() {
    
    $this->handle_start_url_requests();
    
}

public function cache_request( $eof_pattern){
    
   
    global $wp_super_cache_comments;

    //if ( defined( 'JSON_REQUEST' ) && JSON_REQUEST ) {
        // Accept a JSON-formatted string as an end-of-file marker, so that the page will be cached
        $json_object_pattern     = '^[{].*[}]$';
        $json_collection_pattern = '^[\[].*[\]]$';

        $eof_pattern = str_replace(
            '<\?xml',
            sprintf( '<\?xml|%s|%s', $json_object_pattern, $json_collection_pattern ),
            $eof_pattern
        );

        // Don't append HTML comments to the JSON output, because that would invalidate it
        $wp_super_cache_comments = false;
    //}



    return $eof_pattern;
}







public function run_initial_processes(){

flush_rewrite_rules();

//self::maybe_create_table();

if (!wp_attachment_is_image($this->return_manifest_icon_id())){
    
$site_icon = get_option('site_icon');

update_option( $this->manifest_icon_attachment_id, $site_icon ); 

$this->manifest_icon = $site_icon;
    
    
}

$fullsizepath = get_attached_file($this->return_manifest_icon_id());

include( ABSPATH . 'wp-admin/includes/image.php' );



$attach_data = wp_generate_attachment_metadata( $this->return_manifest_icon_id(), $fullsizepath );
wp_update_attachment_metadata( $this->return_manifest_icon_id(),  $attach_data );








}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == self::return_file_name() ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).self::return_file_name().'">Settings</a>';
	}
	return $links;
}

  
  










public function filesystem_service_worker_write($text){

$target_file =  ABSPATH.self::return_sw_file_name();

if (self::is_new_file($text, $target_file)){
    
    echo $this->return_sw_url();
    file_put_contents( $target_file, $text );


} else {

echo "not a new file, do nothing";

}


}

public function redirect_to_login() {
    
    if (!is_user_logged_in() && self::is_web_app() && (!empty($this->options[$this->webapp_prompt_logon_field_name])) && ($this->options[$this->webapp_prompt_logon_field_name] == 1) and (!isset($_COOKIE[self::return_plugin_namespace()."-login_prompted"]))){
        
        // cookie will expire when the browser close
        setcookie(self::return_plugin_namespace()."-login_prompted", "true");
        
       $location = wp_login_url($_SERVER["REQUEST_URI"]);
       wp_safe_redirect($location);
      exit;

        
        
    }
    
    
}


public function plugins_loaded(){


load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 

}

public function theme_metatags($meta_tags) {
    
 $return = array();
 
 foreach( $meta_tags as $meta_tag ) {
     
     if (strpos($meta_tag, 'apple-touch-icon-precomposed') !== false) {
         
     } else {
         
      $return[] = $meta_tag;  
         
     }
     
     }
     
     return $return;
}


public function has_prompt_output($atts, $content = "") {
    
    
if (!empty($content)){




$return = '<style>

div.lh_web_application-prompt_div {

display: none;

}

.lh_web_application-prompt_stashed div.lh_web_application-prompt_div {

display: block;

}


</style>';    
    
$return .= '<div class="lh_web_application-prompt_div">'.$content.'</div>'; 

return $return;


} else {
    
return '';    
    
}
    
}

public function register_shortcodes(){


//register the main form shortcode
add_shortcode('lh_web_application_has_prompt', array($this,'has_prompt_output'));


}

public function add_get_header_hooks(){
    

    if (is_singular()){
        
        $options = get_option(self::return_opt_name());
        
        $post_object = get_queried_object();
        
        
        
        if (!empty($post_object->ID) && !empty($options[ self::return_offline_page_field_name() ]) && ($post_object->ID == $options[ self::return_offline_page_field_name() ])){
            
            
            
            
            remove_action('wp_head', 'rel_canonical');
            
        }
        
        
        
    }
    

    
}


public function register_core_scripts(){
    
// Load JavaScript and stylesheets
$this->register_scripts_and_styles();
    
    
    
}


public function add_wp_body_open_hooks(){
    
wp_enqueue_script( self::return_plugin_namespace().'-addtohomescreen_script');

wp_enqueue_style(self::return_plugin_namespace().'-addtohomescreen_css');   
    
}


public function enqueue_nav_scripts(){
        // Isn't it nice to use dependencies and the already registered core js files?



	wp_enqueue_media();


wp_register_script(self::return_plugin_namespace().'-dashboard-admin', plugins_url( '/includes/scripts/uploader.js', __FILE__ ), array('jquery','media-upload','thickbox'), filemtime(plugin_dir_path( __FILE__ ) . '/includes/scripts/uploader.js'));

wp_enqueue_script(self::return_plugin_namespace().'-dashboard-admin');




}
  

public function add_menu_icon_uploader($item_id, $item, $depth, $args, $id ){
    
  global $nav_menu_selected_id;
  


$menu = get_term_by( 'slug', get_option(self::return_shortcut_menu_name()), 'nav_menu');

if (!empty($menu->term_id) && !empty($nav_menu_selected_id) && ($menu->term_id == $nav_menu_selected_id)){
    
    echo '<p>';
    
    wp_nonce_field( self::return_menu_icon_name().'-nonce', self::return_menu_icon_name().'-'.$item_id.'-nonce' );
	$attachment_id = get_post_meta( $item_id, '_'.self::return_menu_icon_name().'-attachment_id', true );
    
    ?>
    <label for="<?php echo self::return_menu_icon_name().'-'.$item_id; ?>-attachment_url">Menu URL:</label>
    <input type="hidden" class="<?php echo self::return_menu_icon_name(); ?>-attachment_id" name="<?php echo self::return_menu_icon_name(); ?>-attachment_id[<?php echo $item_id ;?>]"  id="<?php echo self::return_menu_icon_name().'-'.$item_id; ?>-attachment_id" value="<?php echo $attachment_id; ?>" size="10" />
    <input type="url" class="<?php echo self::return_menu_icon_name(); ?>-attachment_url" name="<?php echo self::return_menu_icon_name(); ?>-attachment_url[<?php echo $item_id ;?>]" id="<?php echo self::return_menu_icon_name().'-'.$item_id; ?>-attachment_url" value="<?php echo wp_get_attachment_url($attachment_id); ?>" size="50" />
    <input type="button" class="button <?php echo self::return_menu_icon_name(); ?>-upload_button" name="<?php echo self::return_menu_icon_name(); ?>-upload_button" id="<?php echo self::return_menu_icon_name().'-'.$item_id; ?>-upload_button" value="Upload/Select Image" />
    
   <?php
   
  //print_r($menu);
  
   echo '</p>';
    
}
    
}


public function add_menu_icon_meta( $menu_id, $menu_item_db_id, $menu_item_data = array()) {
    
    	// Verify this came from our screen and with proper authorization.
	if ( ! isset( $_POST[self::return_menu_icon_name().'-'.$menu_item_db_id.'-nonce' ] ) || ! wp_verify_nonce( $_POST[self::return_menu_icon_name().'-'.$menu_item_db_id.'-nonce'], self::return_menu_icon_name().'-nonce' ) ) {
		return $menu_id;
	}
	
	
		if ( isset( $_POST[self::return_menu_icon_name().'-attachment_id'][$menu_item_db_id] ) ) {
		$sanitized_data = sanitize_text_field( $_POST[self::return_menu_icon_name().'-attachment_id'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, '_'.self::return_menu_icon_name().'-attachment_id', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, '_'.self::return_menu_icon_name().'-attachment_id' );
	}
    
    
    
}

public function load_nav_scripts(){
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
    add_action( 'admin_enqueue_scripts', array($this,'enqueue_nav_scripts'));
    }




    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }


public function __construct() {

$this->options = get_option(self::return_opt_name());


}

}

$lh_web_application_instance = LH_web_application_plugin::get_instance();
register_activation_hook(__FILE__, array($lh_web_application_instance,'on_activate'), 10, 1);
register_uninstall_hook( __FILE__, array('LH_web_application_plugin','uninstall'));


if (!class_exists('LH_web_application_menu_class')) {
    
require_once('includes/lh-web-application-menu-class.php');
    
    
    
}


}

if (!class_exists('LH_web_application_hooks_class')) {


class LH_web_application_hooks_class extends LH_web_application_plugin {
    

    

    
public function __construct() {
    
parent::__construct();
    

//add the meta tags to the head of the document
add_action('wp_head', array($this,"add_meta_to_head"),1);
add_action('embed_head', array($this,"add_meta_to_head"),1);
 
//Register a menu to list the assets to cache with the service worker
add_action( 'init', array($this,"register_menu"));

//add new image sizes to wp
//add_action( 'init', array($this,"add_new_image_sizes_to_wp"));
add_filter('intermediate_image_sizes_advanced', array($this, 'add_subsizes'), 10,3);





add_filter('query_vars', array($this, 'add_query_vars'), 0);
add_action('wp', array($this, 'sniff_requests'), PHP_INT_MAX);
add_action('init', array($this, 'add_endpoint'), 0);


//general tasks on init hook
add_action( 'init', array($this,'init_hook'));


//Hook to attach processes to initial cron job
add_action('lh_web_application_initial_run', array($this,"run_initial_processes"));

//add a settings link
add_filter('plugin_action_links', array($this,'add_settings_link'), 10, 2);




//possibly redirect to login if in a web app
add_action('template_redirect', array($this,"redirect_to_login"), 9);


//run whatever on plugins loaded (currently just translations)
add_action( 'plugins_loaded', array($this,"plugins_loaded"));

//remove redundant theme meta tags
add_filter('site_icon_meta_tags', array($this,"theme_metatags"));

            //register the key shortcodes
add_action( 'init', array($this,'register_shortcodes'));

//add others get_header so that it only runs when needed
add_action( 'get_header', array($this,'add_get_header_hooks'));

//register the core scripts
add_action( 'wp_loaded', array($this, 'register_core_scripts'), 10 );

//add others on body open so that it only runs when needed
add_action('wp_body_open', array($this,'add_wp_body_open_hooks'));



//
add_action( 'wp_nav_menu_item_custom_fields', array($this,'add_menu_icon_uploader'), 10, 5);
add_action( 'wp_update_nav_menu_item', array($this,'add_menu_icon_meta'), 10, 3 );
// Load the JS conditionally
add_action('load-nav-menus.php', array($this,'load_nav_scripts') );
 
    
}
    
    
}

$lh_web_application_hooks_class_instance = new LH_web_application_hooks_class();


}

if (basename(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) == 'serviceworker.script'){




if( !is_admin() && !defined( 'DOING_AJAX' ) && !defined( 'DOING_CRON' ) && !isset( $_REQUEST['eos_dp_preview'] ) && !isset( $_REQUEST['preview'] )&& !isset( $_REQUEST['customize_changeset_uuid'] ) && !isset( $_REQUEST['customize_theme'] ) && !isset( $_REQUEST['post'] ) && !isset( $_REQUEST['action'] ) && !isset( $_REQUEST['_locale'] ) && !isset( $_REQUEST['elementor-preview'] )){


		if( !function_exists( 'is_user_logged_in' ) ){
			function is_user_logged_in() {
				return false;
			}
		}
		
	if( !function_exists( 'wp_parse_auth_cookie' ) ){	
		function wp_parse_auth_cookie($cookie = '', $scheme = '') {
			return false;
		}
	}


    
}

}

?>