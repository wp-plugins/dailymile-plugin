<?php
/*
*	dailymile racing widget
*/

//  Exit the file if the WP_Widget class doesnt exist
if( !class_exists('WP_Widget')){ exit; }

class dailymile_racing_widget extends WP_Widget{

    function dailymile_racing_widget(){
        $widget_ops = array(
            'classname' => 'dailymile_racing_widget',
            'description' => 'Display your dailymile racing widget'
        );
        $this->WP_Widget('dailymile_racing_widget', 'Dailymile Racing Widget', $widget_ops);
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

        echo '<script src="http://www.dailymile.com/people/' . $dailymile_profile_name . '/events/widget.js" type="text/javascript"></script><noscript><a href="http://www.dailymile.com/people/' . $dailymile_profile_name . '" title="Running Training Log"><img alt="Running Training Log" src="http://www.dailymile.com/images/badges/dailymile_badge_180x60_orange.gif" style="border: 0;" /></a></noscript>';
        
        echo $after_widget;
    }

    //  save widget settings
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = wp_filter_nohtml_kses($new_instance['title']);
        return $instance;
    }

    //  widget backend form
    function form($instance){
        $defaults = array(
            'title' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
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
        <?php
    }

}
?>