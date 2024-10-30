<h1><?php echo esc_html(get_admin_page_title()); ?> Settings</h1>

Your manifest is <a href="<?php echo self::return_manifest_url(); ?>"><?php echo self::return_manifest_url(); ?></a>

<form name="form1" method="post" action="">
<?php wp_nonce_field( parent::return_plugin_namespace()."-nonce", parent::return_plugin_namespace()."-nonce", false ); ?>


<input type="hidden" name="<?php echo $this->manifest_icon_attachment_id; ?>"  id="<?php echo $this->manifest_icon_attachment_id; ?>" value="<?php echo parent::return_manifest_icon_id(); ?>" size="10" />
<input type="hidden" name="<?php echo $this->ios_icon_attachment_id; ?>"  id="<?php echo $this->ios_icon_attachment_id; ?>" value="<?php echo parent::return_ios_icon_id(); ?>" size="10" />
<input type="hidden" name="<?php echo $this->ios_startup_attachment_id; ?>"  id="<?php echo $this->ios_startup_attachment_id; ?>" value="<?php echo parent::return_ios_startup_id(); ?>" size="10" />

<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->short_name_field_name; ?>"><?php _e('A short name for your Web App: ', parent::return_plugin_namespace()); ?></label></th>
<td><input type="text" name="<?php echo $this->short_name_field_name; ?>" id="<?php echo $this->short_name_field_name; ?>" value="<?php echo $this->options[$this->short_name_field_name]; ?>"  />
</td>
</tr>


<tr valign="top">
<th scope="row"><label for="<?php echo parent::return_manifest_icon_name(); ?>-attachment_url"><?php _e("Manifest Icon:", parent::return_plugin_namespace()); ?></label></label></th>
<td><input type="url" name="<?php echo parent::return_manifest_icon_name(); ?>-attachment_url" id="<?php echo parent::return_manifest_icon_name(); ?>-attachment_url" value="<?php echo wp_get_attachment_url(parent::return_manifest_icon_id()); ?>" size="50" />
<input type="button" class="button" name="<?php echo parent::return_manifest_icon_name(); ?>-upload_button" id="<?php echo parent::return_manifest_icon_name(); ?>-upload_button" value="Upload/Select Image" />
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="<?php echo parent::return_is_maskable_icon_name(); ?>"><?php _e("Is Maskable:", parent::return_plugin_namespace()); ?></label></th>
<td>
<select name="<?php echo parent::return_is_maskable_icon_name(); ?>" id="<?php echo parent::return_is_maskable_icon_name(); ?>">
<option value="0" <?php  if (empty(get_option(parent::return_is_maskable_icon_name()))){ echo 'selected="selected"'; }  ?>><?php _e("No", parent::return_plugin_namespace()); ?></option>
<option value="1" <?php  if (get_option(parent::return_is_maskable_icon_name()) == 1){ echo 'selected="selected"';}  ?>><?php _e("Yes", parent::return_plugin_namespace()); ?></option>
</select>
</td>


<tr valign="top">
<th scope="row"><label for="<?php echo parent::return_ios_icon_name(); ?>-attachment_url"><?php _e("Ios Icon:", parent::return_plugin_namespace()); ?></label></label></th>
<td><input type="url" name="<?php echo parent::return_ios_icon_name(); ?>-attachment_url" id="<?php echo parent::return_ios_icon_name(); ?>-attachment_url" value="<?php echo wp_get_attachment_url(parent::return_ios_icon_id()); ?>" size="50" />
<input type="button" class="button" name="<?php echo parent::return_ios_icon_name(); ?>-upload_button" id="<?php echo parent::return_ios_icon_name(); ?>-upload_button" value="Upload/Select Image" />
</td>
</tr>


<tr valign="top">
<th scope="row"><label for="<?php echo parent::return_ios_startup_name(); ?>-attachment_url"><?php _e("Ios Startup Image:", parent::return_plugin_namespace()); ?></label></label></th>
<td><input type="url" name="<?php echo parent::return_ios_startup_name(); ?>-attachment_url" id="<?php echo parent::return_ios_startup_name(); ?>-attachment_url" value="<?php echo wp_get_attachment_url(parent::return_ios_startup_id()); ?>" size="50" />
<input type="button" class="button" name="<?php echo parent::return_ios_startup_name(); ?>-upload_button" id="<?php echo parent::return_ios_startup_name(); ?>-upload_button" value="Upload/Select Image" />
</td>
</tr>


<tr valign="top">
<th scope="row"><label for="<?php echo $this->display_mode_field_name; ?>"><?php _e("Display mode:", parent::return_plugin_namespace()); ?></label></th>
<td>
<select name="<?php echo $this->display_mode_field_name; ?>" id="<?php echo $this->display_mode_field_name; ?>">
<option value="browser" <?php  if ($this->options[$this->display_mode_field_name] == 'browser'){ echo 'selected="selected"'; }  ?>>Browser</option>
<option value="fullscreen" <?php  if ($this->options[$this->display_mode_field_name] == 'fullscreen'){ echo 'selected="selected"';}  ?>>Fullscreen</option>
<option value="standalone" <?php  if ($this->options[$this->display_mode_field_name] == 'standalone'){ echo 'selected="selected"';}  ?>>Standalone</option>
<option value="minimal-ui" <?php  if ($this->options[$this->display_mode_field_name] == 'minimal-ui'){ echo 'selected="selected"';}  ?>>Minimal-ui</option>
</select>
</td>


<tr valign="top">
<th scope="row"><label for="<?php echo $this->apple_web_app_capable_field_name; ?>"><?php _e("Apple Web App Capable:", parent::return_plugin_namespace()); ?></label></th>
<td>
    <select name="<?php echo $this->apple_web_app_capable_field_name; ?>" id="<?php echo $this->apple_web_app_capable_field_name; ?>">
<option value="1" <?php if ($this->options[$this->apple_web_app_capable_field_name] == 1){ echo 'selected="selected"'; } ?> >Yes</option>
<option value="0" <?php if ($this->options[$this->apple_web_app_capable_field_name] == 0){ echo 'selected="selected"'; } ?> >No</option>
</select> - <?php  _e("Set this to yes if you want too to make apple web app capable", parent::return_plugin_namespace() );  ?></td>
</tr>


<tr valign="top">
<th scope="row">
<label for="<?php echo parent::return_offline_page_field_name(); ?>">Offline Page:</label>
</th>
<td>
<?php 

$args = array(
    'selected'              => $this->options[ parent::return_offline_page_field_name() ],
    'echo'                  => 1,
    'name'                  => parent::return_offline_page_field_name(),
    'show_option_none'      => __( '&mdash; Select &mdash;' ) // string
); 

wp_dropdown_pages( $args );

?>
<a href="<?php echo get_permalink($this->options[ parent::return_offline_page_field_name() ]); ?>">Link</a>
</td>
</tr>



<tr valign="top">
<th scope="row">
<label for="<?php echo parent::return_shortcut_menu_name(); ?>">Shortcut Menu:</label>
</th>
<td>
<select name="<?php echo parent::return_shortcut_menu_name(); ?>" id="<?php echo parent::return_shortcut_menu_name(); ?>">
<option value="0">None</option>
<?php
$menus = wp_get_nav_menus();

foreach( $menus as $menu ){ ?>
<option value="<?php echo $menu->slug; ?>" <?php  if (get_option(parent::return_shortcut_menu_name()) == $menu->slug){ echo 'selected="selected"';}  ?>><?php echo $menu->name; ?></option>
<?php } ?>
</select>
</td>
</tr>


<tr valign="top">
<th scope="row">
<label for="<?php echo $this->theme_color_field_name; ?>"><?php _e("Theme Colour:", parent::return_plugin_namespace()); ?></label>
</th>
<td><input type="text" name="<?php echo $this->theme_color_field_name; ?>" id="<?php echo $this->theme_color_field_name; ?>" value="<?php echo $this->options[ $this->theme_color_field_name ]; ?>" class="color-picker" size="10" />
</td>
</tr>

<tr valign="top">
<th scope="row">
<label for="<?php echo $this->background_color_field_name; ?>">Background Color:</label>
</th>
<td><input type="text" name="<?php echo $this->background_color_field_name; ?>" id="<?php echo $this->background_color_field_name; ?>" value="<?php echo $this->get_webb_app_background_colour(); ?>" class="color-picker" size="10" /> 
</td>
</tr>

</table>
<?php submit_button( 'Save Changes' ); ?>
</form>

<?php

//print_r(parent::return_manifest_json_as_var());

//$i = 0;




//foreach( self::create_manifest_icon_sizes() as $size ){



//print_r($size);

//echo $this->return_manifest_icon_id();

//echo parent::return_manifest_icon_name();

//if ($href = $this->check_image_size($this->return_manifest_icon_id(),parent::return_manifest_icon_name(), $size['width'], $size['height'])){
    
//echo "foobar";    
    
//}



//}

//print_r(LH_Multi_member_plugin::return_roles());

?>