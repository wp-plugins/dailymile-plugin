<?php
/*
 *	RunMapCode dailymile profile badge
 *      Either orange or grey badges are supported at the moment
*/

//  Exit the file if the WP_Widget class doesnt exist
if( !class_exists('WP_Widget')){ exit; }

class dailymile_profile_badge_widget extends WP_Widget{

    private $dailymile_plugin_img_url = '';

    function dailymile_profile_badge_widget(){
        $widget_ops = array(
            'classname' => 'dailymile_profile_badge_widget',
            'description' => 'Display your dailymile profile badge'
        );
        $this->WP_Widget('dailymile_profile_badge_widget', 'Dailymile Profile Badge', $widget_ops);
        $this->dailymile_plugin_img_url = WP_PLUGIN_URL . '/dailymile-plugin/images/';
    }

    // widget front-end output
    function widget($args, $instance){
        extract($args, EXTR_SKIP);

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $dailymile_options = get_option('dailymile_plugin_options');
        $dailymile_profile_name = esc_html($dailymile_options['dailymile_profile']);
        if(!$dailymile_profile_name){ return false; }

        echo $before_widget;

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        echo '<a href="http://www.dailymile.com/people/' . $dailymile_profile_name . '?utm_medium=api&utm_source=training_widget" title="Running Training Log"><img src="http://www.dailymile.com/images/badges/dailymile_badge_143x56_' . $instance['badgecolor'] . '.png" width="143" height="56" alt="Running Training Log" border="0" /></a>';

        echo $after_widget;
    }

    //  save widget settings
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = wp_filter_nohtml_kses($new_instance['title']);

        switch($new_instance['badgecolor']){
            case 'orange':
                $instance['badgecolor'] = 'orange';
                break;
            case 'blue':
                $instance['badgecolor'] = 'blue';
                break;
            case 'grey':
                $instance['badgecolor'] = 'grey';
                break;
            default:
                $instance['badgecolor'] = 'orange';
        }
        
        return $instance;
    }

    //  widget backend form
    function form($instance){
        $defaults = array(
            'title' => '',
            'badgecolor' => 'orange'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $badgecolor = $instance['badgecolor'];
        $dailymile_options = get_option('dailymile_plugin_options');
        $dailymile_profile_name = esc_html($dailymile_options['dailymile_profile']);
        if(!$dailymile_profile_name){ ?>
            <p style="background:#ffdede;color:#ff0000;padding:5px;font-size:0.9em;">
                Please set your dailymile username.
            </p>
        <?php } ?>
        <!-- title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br />
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php esc_html_e($instance['title']); ?>" />
        </p>
        <!-- badge color radio group -->
        <table>
            <tr>
                <td><input type="radio" <?php if($badgecolor == 'orange'){ echo 'checked="checked"'; } ?> id="badgeorange" name="<?php echo $this->get_field_name('badgecolor'); ?>" value="orange" /></td>
                <td><img src="<?php echo $this->dailymile_plugin_img_url ?>dailymile_badge_143x56_orange.png" width="143" height="56" alt="Dailymile orange badge" /></td>
            </tr>
            <tr>
                <td><input type="radio" <?php if($badgecolor == 'blue'){ echo 'checked="checked"'; } ?> id="badgeblue" name="<?php echo $this->get_field_name('badgecolor'); ?>" value="blue" /></td>
                <td><img src="<?php echo $this->dailymile_plugin_img_url ?>dailymile_badge_143x56_blue.png" width="143" height="56" alt="Dailymile blue badge" /></td>
            </tr>
            <tr>
                <td><input type="radio" <?php if($badgecolor == 'grey'){ echo 'checked="checked"'; } ?> id="badgegrey" name="<?php echo $this->get_field_name('badgecolor'); ?>" value="grey" /></td>
                <td><img src="<?php echo $this->dailymile_plugin_img_url ?>dailymile_badge_143x56_grey.png" width="143" height="56" alt="Dailymile grey badge" /></td>
            </tr>
        </table>
        <?php
    }

}
?>