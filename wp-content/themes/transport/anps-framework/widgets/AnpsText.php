<?php

class AnpsText extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'AnpsText', __('AnpsThemes - Text and icon', 'transport'), array('description' => __('Enter text and/or icon to show on page. Can only be used in the Top bar widget areas.', 'transport'),)
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'anps_enqueue_scripts' ) );
        add_action( 'admin_footer-widgets.php', array( $this, 'anps_print_scripts' ), 9999 );
    }

    function anps_enqueue_scripts( $hook_suffix ) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    function anps_print_scripts() {
        ?>
        <script>
            ( function( $ ){
                function initColorPicker( widget ) {
                    widget.find( '.anps-color-picker' ).wpColorPicker();
                }

                function onFormUpdate( event, widget ) {
                    initColorPicker( widget );
                }

                $( document ).on( 'widget-added widget-updated', onFormUpdate );
                $( document ).ready( function() {
                    $( '#widgets-right .widget:has(.anps-color-picker)' ).each( function () {
                        initColorPicker( $( this ) );
                    } );
                } );
            }( jQuery ) );
        </script>
        <?php
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('icon' => '', 'text'=>'', 'icon_color'=>''));

        $icon = $instance['icon'];
        $icon = 'fa-' . trim($icon);
        $icon = str_replace('fa-fa-', 'fa-', $icon);
        $text = htmlentities($instance['text']);
        ?>
        <p>
            <div class="anps-iconpicker">
                <i class="fa <?php echo esc_attr($icon); ?>"></i>
                <input type="text" value="<?php echo esc_attr($icon); ?>" id="<?php echo esc_attr($this->get_field_id('icon')); ?>" name="<?php echo esc_attr($this->get_field_name('icon')); ?>">
                <button type="button"><?php esc_html_e('Select icon', 'transport'); ?></button>
            </div>
        </p>
        <!-- Icon color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_color')); ?>"><?php _e("Icon color", 'transport'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo $this->get_field_id('icon_color'); ?>" name="<?php echo $this->get_field_name('icon_color'); ?>" type="text" value="<?php echo esc_attr($instance['icon_color']); ?>" />
        </p>
        <p>
            <input id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text" class="widefat" value="<?php echo esc_attr($text); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['icon'] = $new_instance['icon'];
        $instance['text'] = $new_instance['text'];
        $instance['icon_color'] = $new_instance['icon_color'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $icon = $instance['icon'];
        $icon = 'fa-' . trim($icon);
        $icon = str_replace('fa-fa-', 'fa-', $icon);
        $text = $instance['text'];
        echo $before_widget;
        ?>

        <span class="fa <?php echo esc_attr($icon);?>"<?php if(isset($instance['icon_color'])&&$instance['icon_color']!=''):?> style="color: <?php echo esc_attr($instance['icon_color']);?>"<?php endif; ?>></span>
        <?php echo wp_kses_post($text); ?>
        <?php
        echo $after_widget;
    }

}

add_action( 'widgets_init', create_function('', 'return register_widget("AnpsText");') );
