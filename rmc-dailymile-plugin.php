<?php
/*
Plugin Name: dailymile training log Plugin
Plugin URI: http://wordpress.org/#
Description: Adds dailymile widgets to your blog
Author: Ryan Meier
Version: 0.2
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

//	add plugin options menu under 'Settings' tab
add_action('admin_menu','dailymile_plugin_options_menu');
//	add register settings function
add_action('admin_init','dailymile_register_settings');
//	add dailymile widget
add_action('widgets_init','rmc_dailymile_log_widget_init');
//  register short code for log
add_shortcode('dailymile_log', 'rmc_dailymile_log');

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
include_once(WP_PLUGIN_DIR . '/rmc-dailymile-plugin/classes/rmc-dailymile-plugin-log-widget.php');
//  include the dailymile badge widget
include_once(WP_PLUGIN_DIR . '/rmc-dailymile-plugin/classes/rmc-dailymile-plugin-badge-widget.php');

//  reigster widget init function
function rmc_dailymile_log_widget_init(){
    //  register dailymile log widget if exists
    if(class_exists('rmc_dailymile_log_widget')){
        register_widget('rmc_dailymile_log_widget');
    }

    //  register dailymile badge widget if exists
    if(class_exists('rmc_dailymile_profile_badge_widget')){
        register_widget('rmc_dailymile_profile_badge_widget');
    }
}

function rmc_dailymile_log($atts){
    extract(shortcode_atts(array(
        'width' => '175',
        'float' => 'left'
    ), $atts));

    $width = (is_numeric($width)) ? (int)$width : 300;

    $float = ($float == 'left') ? 'left' : 'right';
    switch($float){
        case 'left':
        case 'right':
        case 'none':
            break;
        default:
            $float = 'left';
    }
    
    $dailymile_options = dailymile_plugin_validate(get_option('dailymile_plugin_options'));
    $out = '<div style="width:' . $width . 'px;float:' . $float . ';">';
    $out .= '<script src="http://www.dailymile.com/people/' . $dailymile_options['dailymile_profile'] . '/training/widget.js" type="text/javascript"></script><noscript><a href="http://www.dailymile.com/people/' . $dailymile_options['dailymile_profile'] . '?utm_medium=api&utm_source=training_widget" title="Running Training Log"><img alt="Running Training Log" src="http://www.dailymile.com/images/badges/dailymile_badge_180x60_orange.gif" style="border: 0;" /></a></noscript>';
    $out .= '</div>';
    return $out;
}
?>