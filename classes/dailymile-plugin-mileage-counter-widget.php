<?php
/*
*	dailymile racing widget
*/

//  Exit the file if the WP_Widget class doesnt exist
if( !class_exists('WP_Widget')){ exit; }

class dailymile_mileage_counter_widget extends WP_Widget{

    function dailymile_mileage_counter_widget(){
        $widget_ops = array(
            'classname' => 'dailymile_mileage_counter_widget',
            'description' => 'Display your dailymile mileage counter widget'
        );
        $this->WP_Widget('dailymile_mileage_counter_widget', 'Dailymile Mileage Counter', $widget_ops);
    }

    // widget front-end output
    function widget($args, $instance){
        extract($args, EXTR_SKIP);

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $dailymile_options = dailymile_plugin_validate(get_option('dailymile_plugin_options'));
        $dailymile_profile_name = esc_html($dailymile_options['dailymile_profile']);
        $dailymile_mileage_widget_size = esc_html($instance['mileagewidgettype']);
        if(!$dailymile_profile_name){ return false; }
        
        echo $before_widget;
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

        echo '<script src="http://www.dailymile.com/people/' . $dailymile_profile_name . '/widgets/distance/' . $dailymile_mileage_widget_size . '.js" type="text/javascript"></script><noscript><a href="http://www.dailymile.com/people/' . $dailymile_profile_name . '" title="Running Training Log"><img alt="Running Training Log" src="http://www.dailymile.com/images/badges/dailymile_badge_180x60_orange.gif" style="border: 0;" /></a></noscript>';
        
        echo $after_widget;
    }

    //  save widget settings
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = wp_filter_nohtml_kses($new_instance['title']);
        $instance['mileagewidgettype'] = ($new_instance['mileagewidgettype'] == 'large') ? 'large' : 'mini';
        return $instance;
    }

    //  widget backend form
    function form($instance){
        $defaults = array(
            'title' => '',
            'mileagewidgettype' => 'large'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $widgetsize = $instance['mileagewidgettype'];
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
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_html_e($instance['title']); ?>" />
        </p>
        <!-- mileage counter widget size radio group -->
        <p>
            <input type="radio" <?php if($widgetsize == 'large'){ echo 'checked="checked"'; } ?> id="mileagewidgetlarge" name="<?php echo $this->get_field_name('mileagewidgettype'); ?>" value="large" />
            <label for="mileagewidgetlarge">Large Counter</label><br />
            <input type="radio" <?php if($widgetsize == 'mini'){ echo 'checked="checked"'; } ?> id="mileagewidgetmini" name="<?php echo $this->get_field_name('mileagewidgettype'); ?>" value="mini" />
            <label for="mileagewidgetmini">Mini Counter</label><br />
        </p>
        <?php
    }

}
?>