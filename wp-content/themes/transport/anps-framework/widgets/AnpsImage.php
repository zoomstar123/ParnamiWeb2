<?php

class AnpsImage extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'AnpsImages', 'AnpsThemes - Images', array('description' => __('Choose a image to show on page', 'transport'),)
        );
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'image' => ''));

        $image = $instance['image'];
        $title = $instance['title'];
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e("Title", 'transport'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" class="widefat" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>

        <?php $images = get_children('post_type=attachment&post_mime_type=image'); ?>

        <select id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php echo esc_attr($this->get_field_name('image')); ?>">
            <option value="">Select an image</option>
            <?php foreach ($images as $item) : ?>
                <option <?php if ($item->guid == $image) {
                    echo 'selected="selected"';
                } ?> value="<?php echo esc_attr($item->guid); ?>"><?php echo esc_html($item->post_title); ?></option>
        <?php endforeach; ?>
        </select>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['image'] = $new_instance['image'];
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $title = $instance['title'];
        $image = $instance['image'];
        echo $before_widget;
        ?>

        <?php if($title) : ?>
            <h3 class="widget-title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>

        <img alt="<?php echo esc_attr($image); ?>" src="<?php echo esc_url($image); ?>">

        <?php
        echo $after_widget;
    }

}

add_action( 'widgets_init', create_function('', 'return register_widget("AnpsImage");') );
