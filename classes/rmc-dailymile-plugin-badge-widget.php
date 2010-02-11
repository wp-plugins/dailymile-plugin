<?php
/*
 *	RunMapCode dailymile profile badge
 *      Either orange or grey badges are supported at the moment
*/

//  Exit the file if the WP_Widget class doesnt exist
if( !class_exists('WP_Widget')){ exit; }

class rmc_dailymile_profile_badge_widget extends WP_Widget{

    function rmc_dailymile_profile_badge_widget(){
        $widget_ops = array(
            'classname' => 'rmc_dailymile_profile_badge_widget',
            'description' => 'Display your dailymile profile badge'
        );
        $this->WP_Widget('rmc_dailymile_profile_badge_widget', 'dailymile profile badge widget', $widget_ops);
    }

    // widget front-end output
    function widget($args, $instance){
        extract($args, EXTR_SKIP);

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $dailymile_options = dailymile_plugin_validate(get_option('dailymile_plugin_options'));

        echo $before_widget;

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        echo '<a href="http://www.dailymile.com/people/' . $dailymile_options['dailymile_profile'] . '?utm_medium=api&utm_source=training_widget" title="Running Training Log"><img src="http://www.dailymile.com/images/badges/dailymile_badge_180x60_' . $instance['badgecolor'] . '.gif" width="180" height="60" alt="Running Training Log" border="0" /></a>';

        echo $after_widget;
    }

    //  save widget settings
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = wp_filter_nohtml_kses($new_instance['title']);
        $instance['badgecolor'] = ($new_instance['badgecolor'] == 'orange') ? 'orange' : 'grey';
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
        ?>
        <!-- title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br />
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
		<!-- badge color radio group -->
        <p>
            <input type="radio" <?php if($badgecolor == 'orange'){ echo 'checked="checked"'; } ?> id="badgeorange" name="<?php echo $this->get_field_name('badgecolor'); ?>" value="orange" />
            <label for="badgeorange">Orange Badge</label><br />
            <input type="radio" <?php if($badgecolor == 'grey'){ echo 'checked="checked"'; } ?> id="badgegrey" name="<?php echo $this->get_field_name('badgecolor'); ?>" value="grey" />
            <label for="badgegrey">Grey Badge</label><br />
        </p>
        <?php
    }

}
?>