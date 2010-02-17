<?php
/*
Plugin Name: dailymile training log Plugin
Plugin URI: http://www.runmapcode.com/plugins/dailymile-plugin/
Description: Adds dailymile widgets to your blog
Author: Ryan Meier
Version: 0.5
Author URI: http://www.runmapcode.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$dailymile_plugin_img_url = WP_PLUGIN_DIR . '/dailymile-plugin/images/';

//	add plugin options menu under 'Settings' tab
add_action('admin_menu','dailymile_plugin_options_menu');
//	add register settings function
add_action('admin_init','dailymile_register_settings');
//	add dailymile widget
add_action('widgets_init','dailymile_log_widget_init');

/*
 *      Setup plugin parts
 */

//	function to add dailymile plugin options
function dailymile_plugin_options_menu() {
    add_options_page('dailymile', 'dailymile options', 8, basename(__FILE__), 'dailymile_plugin_options_page');
}

//	function to register dailymile plugin options
function dailymile_register_settings(){
    register_setting('dailymile_plugin_settings','dailymile_plugin_options','dailymile_plugin_validate');
}

//	function to add plugin options page under 'Settings' tab
function dailymile_plugin_options_page() {
?>
    <div class="wrap">
        <h2>dailymile plugin options</h2>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'dailymile_plugin_settings' );
                $option = get_option('dailymile_plugin_options');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Dailymile profile name</th>
                    <td><input type="text" name="dailymile_plugin_options[dailymile_profile]" value="<?php echo $option['dailymile_profile']; ?>" /></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    </div>
<?php
}

//	dailymile plugin validation function
function dailymile_plugin_validate($input){
	//	dailymile profile name
	$input['dailymile_profile'] =  wp_filter_nohtml_kses($input['dailymile_profile']);
	return $input;
}

/*
 *      Grab and register widgets
 */

//  include the dailymile training log widget
if( file_exists(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-log-widget.php')){
    include_once(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-log-widget.php');
}

//  include the dailymile badge widget
if( file_exists(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-badge-widget.php') ){
    include_once(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-badge-widget.php');
}

//  include the dailymile racing widget
if( file_exists(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-racing-widget.php') ){
    include_once(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-racing-widget.php');
}

//  include the dailymile mileage counter widget
if( file_exists(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-mileage-counter-widget.php') ){
    include_once(WP_PLUGIN_DIR . '/dailymile-plugin/classes/dailymile-plugin-mileage-counter-widget.php');
}

//  reigster widget init function
function dailymile_log_widget_init(){
    //  register dailymile log widget if exists
    if(class_exists('dailymile_log_widget')){
        register_widget('dailymile_log_widget');
    }

    //  register dailymile badge widget if exists
    if(class_exists('dailymile_profile_badge_widget')){
        register_widget('dailymile_profile_badge_widget');
    }

    //  register dailymile racing widget if exists
    if(class_exists('dailymile_racing_widget')){
        register_widget('dailymile_racing_widget');
    }

    //  register dailymile mileage counter widget if exists
    if(class_exists('dailymile_mileage_counter_widget')){
        register_widget('dailymile_mileage_counter_widget');
    }
}
?>