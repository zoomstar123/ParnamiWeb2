<?php

class AnpsRecentProjects extends WP_Widget {

    function __construct() {        
        $widget_ops = array('classname' => 'anps-recent-posts', 'description' => 'Shows recent projects');
        parent::__construct('AnpsRecentProjects', 'AnpsThemes - Recent Projects', $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array(
            'title' => '', 
            'anps_number_fields' => '', 
            'anps_recent_title' => '',
            'anps_comments_title' => ''
        ));

        $title = $instance['title'];
        $anps_number_fields = $instance['anps_number_fields'];
        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title for recent projects: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

        <p><label for="<?php echo esc_attr($this->get_field_id('anps_number_fields')); ?>">Number of projects to show:</label>
            <input id="<?php echo esc_attr($this->get_field_id('anps_number_fields')); ?>" name="<?php echo esc_attr($this->get_field_name('anps_number_fields')); ?>" value="<?php echo esc_attr($anps_number_fields); ?>" type="text" value="5" size="3"></p>

        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['anps_number_fields'] = $new_instance['anps_number_fields'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $anps_number_fields = $instance['anps_number_fields'];

        echo $before_widget;

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;;
        }

        /* Popular posts */

        $tab2 = "";

        $paged = '';
        $new_query = new WP_Query();
        $new_query->query('post_type=portfolio&paged=' . $paged . '&posts_per_page=' . $anps_number_fields . '&numberposts=' . $anps_number_fields . '&orderby=id&order="DESC"');

        //The Loop
        echo '<ul class="widget_recent_entries">';

        while ($new_query->have_posts()) : $new_query->the_post(); ?>            
            
            <li>
                <a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a>
            </li>

        <?php endwhile;  

        echo '</ul>';

        wp_reset_postdata();

        echo $after_widget;
    }
}
add_action('widgets_init', create_function('', 'return register_widget("AnpsRecentProjects");'));