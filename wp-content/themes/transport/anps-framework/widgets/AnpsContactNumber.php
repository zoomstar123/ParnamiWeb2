<?php
class AnpsContactNumber extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'AnpsContactNumber', 'AnpsThemes - Contact Number', array('description' => esc_html__('Display your contact number.', 'transport'),)
        );
        add_action( 'admin_footer-widgets.php', array( $this, 'anps_print_scripts' ), 9999 );
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
        $instance = wp_parse_args((array) $instance, array(
                'text'  => '',
                'number' => '',
                'text_color' => '#d63c2c',
                'number_color' => '#e3fffa',
                'bg_color' => '#fd7062'
            )
        );

        ?>

        <!-- Text -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php esc_html_e("Text", 'transport'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text" class="widefat" value="<?php echo esc_attr($instance['text']); ?>" />
        </p>

        <!-- Number -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e("Number", 'transport'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" class="widefat" value="<?php echo esc_attr($instance['number']); ?>" />
        </p>

        <!-- Text color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text_color')); ?>"><?php esc_html_e("Text color", 'transport'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo esc_attr($this->get_field_id('text_color')); ?>" name="<?php echo esc_attr($this->get_field_name('text_color')); ?>" type="text" value="<?php echo esc_attr($instance['text_color']); ?>" />
        </p>

        <!-- Number color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_color')); ?>"><?php esc_html_e("Number color", 'transport'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo esc_attr($this->get_field_id('number_color')); ?>" name="<?php echo esc_attr($this->get_field_name('number_color')); ?>" type="text" value="<?php echo esc_attr($instance['number_color']); ?>" />
        </p>

        <!-- Background color -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('bg_color')); ?>"><?php esc_html_e("Background color", 'transport'); ?></label><br />
            <input class="anps-color-picker" id="<?php echo esc_attr($this->get_field_id('bg_color')); ?>" name="<?php echo esc_attr($this->get_field_name('bg_color')); ?>" type="text" value="<?php echo esc_attr($instance['bg_color']); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['text'] = $new_instance['text'];
        $instance['number'] = $new_instance['number'];
        $instance['text_color'] = $new_instance['text_color'];
        $instance['number_color'] = $new_instance['number_color'];
        $instance['bg_color'] = $new_instance['bg_color'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $text = '';
        if( isset($instance['text']) ) {
            $text = $instance['text'];
        }

        $number = '';
        if( isset($instance['number']) ) {
            $number = $instance['number'];
        }

        $text_color = '';
        if( isset($instance['text_color']) ) {
            $text_color = ' color: ' . $instance['text_color'];
        }

        $number_color = '';
        if( isset($instance['number_color']) ) {
            $number_color = 'color: ' . $instance['number_color'];
        }

        $bg_color = '';
        if( isset($instance['bg_color']) ) {
            $bg_color = 'background-color: ' . $instance['bg_color'];
        }

        echo $before_widget;

        echo '<div class="contact-number" style="' . esc_attr($bg_color) . '">';
        if( $text ) {
            echo '<span class="contact-number-text" style="' . esc_attr($text_color) . '">' . esc_html($text) . '</span>';
        }

        if( $number ) {
            echo '<span class="contact-number-number" style="' . esc_attr($number_color) . '">' . esc_html($number) . '</span>';
        }
        echo '</div>';

        echo $after_widget;
    }

}

add_action( 'widgets_init', create_function('', 'return register_widget("AnpsContactNumber");') );