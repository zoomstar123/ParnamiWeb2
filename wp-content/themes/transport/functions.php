<?php
/* CONSTANTS */
global $no_container;
$no_container = false;

/* Override vc tabs */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (function_exists('vc_theme_rows_inner') && get_option('anps_vc_legacy', "0")=="on") {
    function vc_theme_rows($atts, $content) {
        $extra_class = '';
        $extra_id = '';
        $matches = array();

        global $no_container, $row_inner, $text_only;

        wp_enqueue_script('wpb_composer_front_js');

        if ( $row_inner ) {
            return vc_theme_rows_inner($atts, $content);
        }

        if ( $text_only ) {
            return wpb_js_remove_wpautop($content);
        }

        /* Check for any user added styles */

        $css = '';
        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }

        $temp = preg_match('/\.vc_custom_(.*?){/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];

            if( $temp ) {
                $extra_class .= ' vc_custom_' . $temp;
            }
        }

        /* Check for any user added classes */

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class .= ' '. $atts['el_class'];
        }

        /* Check for any user added IDs */

        if(isset($atts['id']) && $atts['id']) {
            $extra_id = 'id= "'. $atts['id'].'"';
        }

        $coming_soon = get_option('coming_soon', '0');
        if($coming_soon=="0"||is_super_admin()) {
            if(!isset($atts['has_content']) || $atts['has_content']=="true") {
                /* Content inside a container */
                $no_container = false;
                $no_top_margin = '';
                if( strpos($extra_class, 'no-top-margin') ) {
                    $no_top_margin = ' no-top-margin';
                }
                return '<section class="wpb_row container' . $no_top_margin .'"><div '.$extra_id.' class="row' . $extra_class . '">'.wpb_js_remove_wpautop($content).'</div></section>';
            }
            elseif($atts['has_content']=="inside") {
                $no_container = false;
                return '<section '.$extra_id.' class="wpb_row' . $extra_class . '"><div class="container no-margin"><div class="row">'.wpb_js_remove_wpautop($content).'</div></div></section>';
            }
            else {
                /* Fullwidth Content */
                $no_container = true;
                return '<section '.$extra_id.' class="wpb_row row no-margin' . $extra_class . '"><div class="row">'.wpb_js_remove_wpautop($content).'</div></section>';
            }
        } else {
            /* No wrappers, only when Cooming soon is active */
            $no_container = true;
            return wpb_js_remove_wpautop($content);
        }
    }
    function vc_theme_rows_inner($atts, $content) {

        /* Check for any user added styles */

        $style = "";
        $css = '';

        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }
        $extra_id = '';
        $extra_class = '';

        $temp = preg_match('/\.vc_custom_(.*?){/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];

            if( $temp ) {
                $extra_class .= ' vc_custom_' . $temp;
            }
        }

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class = ' ' . $atts['el_class'];
        }

        $temp2 = preg_match('/{(.*?)}/s', $css, $matches2);
        if(!empty($matches2)) {
            $temp2 = $matches2[1];
            if( $temp2 ) {
                $style = ' style="' . $temp2 . '"';
            }
        }

        if(isset($atts['id']) && $atts['id']) {
            $extra_id = 'id= "'. $atts['id'].'"';
        }
        return '<div '.$extra_id.' class="row' . $extra_class . '"' . $style . '>'.wpb_js_remove_wpautop($content).'</div>';
    }
    function vc_theme_columns($atts, $content = null) {
        if( !isset($atts['width']) ) {
            $width = '1/1';
        } else {
            $width = explode('/', $atts['width']);
        }

        global $no_container, $text_only;
        $extra_id = '';
        $extra_class = '';
        if($width[1] > 0) {
            $col = (12/$width[1])*$width[0];
        } else {
            $col = 12;
        }
        $css = '';
        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }

        $temp = preg_match('/\.vc_custom_(.*?){/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];

            if( $temp ) {
                $extra_class .= ' vc_custom_' . $temp;
            }
        }
        $mobile_class = "";
        if(isset($atts['offset']) && $atts['offset']) {
            $mobile_class = " ".$atts['offset'];
        }

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class = ' ' . $atts['el_class'];
        }

        if(isset($atts['id']) && $atts['id']) {
            $extra_id = 'id= "'. $atts['id'].'"';
        }

        if ( $no_container || $text_only ) {
            return '<div class="wpb_column col-md-'.$col.$extra_class.$mobile_class.'">'.wpb_js_remove_wpautop($content)."</div>";
        } else {
            return '<div '.$extra_id.' class="wpb_column col-md-' .$col.$extra_class.$mobile_class.'">'.wpb_js_remove_wpautop($content).'</div>';
        }
    }
    function vc_theme_vc_row($atts, $content = null) {
        return vc_theme_rows($atts, $content);
    }
    function vc_theme_vc_row_inner($atts, $content = null) {
        return vc_theme_rows_inner($atts, $content);
    }
    function vc_theme_vc_column($atts, $content = null) {
        return vc_theme_columns($atts, $content);
    }
    function vc_theme_vc_column_inner($atts, $content = null) {
        return vc_theme_columns($atts, $content);
    }
    function vc_theme_vc_tabs($atts, $content = null) {
        $content2 = str_replace("vc_tab", "tab", $content);
        if(!isset($atts['type'])) {
            $atts['type'] = "";
        } else {
            $atts['type'] = $atts['type'];
        }
        return do_shortcode("[tabs type='".$atts['type']."']".$content2."[/tabs]");
    }
    function vc_theme_vc_column_text($atts, $content = null) {
        $extra_class = '';

        /* Check for any user added styles */

        $css = '';
        if( isset($atts['css']) ) {
            $css = $atts['css'];
        }

        $temp = preg_match('/\.vc_custom_(.*?){/s', $css, $matches);
        if(!empty($matches)) {
            $temp = $matches[1];

            if( $temp ) {
                $extra_class .= ' vc_custom_' . $temp;
            }
        }

        /* Check for any user added classes */

        if(isset($atts['el_class']) && $atts['el_class']) {
            $extra_class .= ' '. $atts['el_class'];
        }

        return '<div class="text' . $extra_class . '">' . do_shortcode(force_balance_tags($content)) . '</div>';
    }
}

/* Title tag theme support */
add_theme_support('title-tag');

/* Image sizes */
add_theme_support('post-thumbnails');

/* team */
add_image_size('team-3', 370, 360, false);
// Blog views
add_image_size('blog-grid', 720, 412, true);
add_image_size('blog-full', 1200);
add_image_size('blog-masonry-3-columns', 360, 0, false);
// Recent blog, portfolio
add_image_size('post-thumb', 360, 267, true);
// Portfolio random grid
add_image_size('portfolio-random-width-2', 554, 202, true);
add_image_size('portfolio-random-height-2', 262, 433, true);
add_image_size('portfolio-random-width-2-height-2', 554, 433, true);
add_image_size('anps-featured', 722, 368, true);

if(!is_admin()) {
    include_once get_template_directory().'/anps-framework/classes/Options.php';
    include_once get_template_directory().'/anps-framework/classes/Contact.php';
    $anps_page_data = $options->get_page_setup_data();
    $anps_options_data = $options->get_page_data();
    $anps_media_data = $options->get_media();
    $anps_social_data = $options->get_social();
    $anps_contact_data = $contact->get_data();
    $anps_shop_data = $options->get_shop_setup_data();
}
function anps_get_option($class, $value, $name='') {
    if($name=='') {
        if(isset($class[$value])) {
            return get_option('anps_'.$value, $class[$value]);
        } else {
            return get_option('anps_'.$value, '');
        }
    } else {
        return get_option('anps_'.$name, get_option($name, $value));
    }
}
if(is_admin()) {
    /* Checking google fonts subsets for each font in admin */
    include_once 'anps-framework/classes/gfonts_ajax.php';
}

/* Include helper.php */
include_once get_template_directory().'/anps-framework/helpers.php';
if (!isset($content_width)) {
    $content_width = 967;
}
add_filter('widget_text', 'do_shortcode');
/* Widgets */
include_once 'anps-framework/widgets/widgets.php';
/* Shortcodes */
include_once 'anps-framework/shortcodes.php';
if (is_admin()) {
    include_once 'shortcodes/shortcodes_init.php';
}
/* Include Customizer class */
include_once(get_template_directory() . '/anps-framework/classes/Customizer.php');
/* On setup theme */
add_action('after_setup_theme', 'anps_register_custom_fonts');
function anps_register_custom_fonts() {
    if (!isset($_GET['stylesheet'])) {
        $_GET['stylesheet'] = '';
    }
    $theme = wp_get_theme($_GET['stylesheet']);
    if (!isset($_GET['activated'])) {
        $_GET['activated'] = '';
    }
    if ($_GET['activated'] == 'true' && $theme->get_template() == "transport") {
        include_once get_template_directory().'/anps-framework/classes/Options.php';
        include_once get_template_directory().'/anps-framework/classes/Style.php';
        /* Add google fonts*/
        if(get_option('anps_google_fonts', '')=='') {
            $style->update_gfonts_install();
        }
        /* Add custom fonts to options */
        $style->get_custom_fonts();
        /* Add default fonts */
        if(get_option('font_type_1', '')=='') {
            update_option("font_type_1", "Montserrat");
        }
        if(get_option('font_type_2', '')=='') {
            update_option("font_type_2", "PT+Sans");
        }
    }
    $fonts_installed = get_option('fonts_intalled');

    if($fonts_installed==1)
        return;

    /* Get custom font */
    include_once get_template_directory().'/anps-framework/classes/Style.php';
    $fonts = $style->get_custom_fonts();
    /* Update custom font */
    foreach($fonts as $name=>$value) {
        $arr_save[] = array('value'=>$value, 'name'=>$name);
    }
    update_option('anps_custom_fonts', $arr_save);
    update_option('fonts_intalled', 1);
}
/* Show excerpt by default */
add_filter('default_hidden_meta_boxes','anps_hide_meta_box',10,2);
function anps_hide_meta_box($hidden, $screen) {
    //make sure we are dealing with the correct screen
    if ('post' == $screen->base){
        //lets hide everything
        $hidden = array('slugdiv','postcustom','trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv');
    }
    return $hidden;
}
/* Team metaboxes */
include_once get_template_directory() . '/anps-framework/team_meta.php';
/* Portfolio metaboxes */
include_once get_template_directory() . '/anps-framework/portfolio_meta.php';
/* Portfolio metaboxes */
include_once get_template_directory() . '/anps-framework/metaboxes.php';
/* Menu metaboxes */
include_once get_template_directory() . '/anps-framework/menu_meta.php';
/* Heading metaboxes */
include_once get_template_directory() . '/anps-framework/heading_meta.php';
/* Featured video metabox */
include_once get_template_directory() . '/anps-framework/featured_video_meta.php';
/* Header options page meta box */
include_once get_template_directory() . '/anps-framework/header_options_meta.php';

//install paralax slider
include_once 'anps-framework/install_plugins.php';
add_editor_style('css/editor-style.php');
/* Admin bar theme options menu */
include_once 'anps-framework/classes/adminBar.php';
/* PHP header() NO ERRORS */
if (is_admin())
    add_action('init', 'anps_do_output_buffer');
function anps_do_output_buffer() {
    ob_start();
}
/* Infinite scroll 08.07.2013 */
function anps_infinite_scroll_init() {
    add_theme_support( 'infinite-scroll', array(
        'type'       => 'click',
        'footer_widgets' => true,
        'container'  => 'section-content',
        'footer'     => 'site-footer',
    ) );
}
add_action( 'init', 'anps_infinite_scroll_init' );
/* MegaMenu */
class description_walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
        $append = "";
        $prepend = "";
        if(get_post_meta($item->ID, 'anps-megamenu', true)=='1') {
            $megamenu_wrapper_class = ' megamenu-wrapper';
            unset($item->classes[0]);
        } else {
            $megamenu_wrapper_class = '';
        }

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names.$megamenu_wrapper_class ) . ' menu-item-depth-'.$depth.'"';

        $output .= $indent . '<li' . $value . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url       ) .'"' : '';

        $children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));

        /* Description */
        $description  = ! empty( $item->description ) ? '<span class="menu-item-desc">'.esc_attr( $item->description ).'</span>' : '';
        $description = do_shortcode($description);
        if($depth>0) {
            $description = "";
        }
        /* END Description */
        $locations = get_theme_mod('nav_menu_locations');
        if($locations['primary']) {
            $item_output = "";
            $item_output = $args->before;
            $item_output .= '<a'. $attributes . '>';

            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= '</a>';
            $item_output .= $description.$args->link_after;
            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth = 0, $args, $args, $current_object_id = 0 );
        }
    }
}
/* Post/Page gallery images */
include_once 'anps-framework/gallery_images.php';

function anps_scripts_and_styles() {
    wp_enqueue_script("jquery");
    //wp_enqueue_style("prettyphoto", get_template_directory_uri()  . "/css/prettyPhoto.css");
    wp_enqueue_style("font-awesome", get_template_directory_uri() . "/css/font-awesome.min.css");
    wp_enqueue_style("owl-css", get_template_directory_uri() . "/js/owl//assets/owl.carousel.css");
    wp_enqueue_style("transport", get_template_directory_uri() . "/css/transport.css");

    global $is_IE;
    if ( $is_IE ) {
        wp_enqueue_style("ie-fix", get_template_directory_uri() . '/css/ie-fix.css');
        wp_enqueue_script( "iefix", get_template_directory_uri()  . "/js/ie-fix.js", '', '', true );
    }

    wp_register_script( "fullwidth-slider", get_template_directory_uri()  . "/js/fullwidth-slider.js", '', '', true );

    wp_register_script( "anps-isotope", get_template_directory_uri()  . "/js/jquery.isotope.min.js", '', '', true );

    wp_enqueue_script( "bootstrap", get_template_directory_uri()  . "/js/bootstrap/bootstrap.min.js", '', '', true );
    wp_enqueue_script("woo_quantity", get_template_directory_uri() . "/js/quantity_woo23.js",array("jquery"), "", true);

    $google_maps_api = get_option('anps_google_maps', '');

    if( $google_maps_api != '' ) {
        $google_maps_api = '?key=' . $google_maps_api;
    }

    wp_register_script( "gmap3_link", "https://maps.google.com/maps/api/js" . $google_maps_api, '', '', true );
    wp_register_script( "gmap3", get_template_directory_uri()  . "/js/gmap3.min.js", '', '', true );
    wp_register_script( "countto", get_template_directory_uri()  . "/js/countto.js", '', '', true );
    wp_enqueue_script( "waypoints", get_template_directory_uri()  . "/js/waypoints.js", '', '', true );
    wp_enqueue_script( "parallax", get_template_directory_uri()  . "/js/parallax.js", '', '', true );
    wp_enqueue_script( "functions", get_template_directory_uri()  . "/js/functions.js", '', '', true );
    wp_localize_script( 'functions', 'anps', array(
        'search_placeholder' => __( 'Search...', 'transport' )
    ));
    wp_enqueue_script( "doubletap", get_template_directory_uri()  . "/js/doubletaptogo.js", array('jquery'), '', true );
    wp_enqueue_script("owl", get_template_directory_uri() . "/js/owl/owl.carousel.js",array("jquery"), "", true);

    if (get_option('font_source_1', "Google fonts")=='Google fonts') {
        $font1_subset = get_option("font_type_1_subsets", array("latin", "latin-ext"));
        $font1_implode_subset = implode(",", $font1_subset);
        wp_enqueue_style( "font_type_1",  'https://fonts.googleapis.com/css?family=' . get_option('font_type_1', 'Montserrat') . ':400italic,400,500,600,700,300&subset='.$font1_implode_subset);
    } else {
        wp_enqueue_style( "font_type_1",  'https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,300&subset=latin,latin-ext');
    }

    if (get_option('font_source_2', "Google fonts")=='Google fonts' && get_option('font_type_1', 'Montserrat')!=get_option('font_type_2', 'PT+Sans')) {
        $font2_subset = get_option("font_type_2_subsets", array("latin", "latin-ext"));
        $font2_implode_subset = implode(",", $font2_subset);
        wp_enqueue_style( "font_type_2",  'https://fonts.googleapis.com/css?family=' . get_option('font_type_2', 'PT+Sans') . ':400italic,400,500,600,700,300&subset='.$font2_implode_subset);
    }

    if (get_option('font_source_navigation', "Google fonts")=='Google fonts' && get_option('font_type_1', 'Montserrat')!=get_option('font_type_navigation', "Montserrat")) {
        $font3_subset = get_option("font_type_navigation_subsets", array("latin", "latin-ext"));
        $font3_implode_subset = implode(",", $font3_subset);
        wp_enqueue_style( "font_type_navigation",  'https://fonts.googleapis.com/css?family=' . get_option('font_type_navigation', 'Montserrat') . ':400italic,400,500,600,700,300&subset='.$font3_implode_subset);
    }

    if (get_option('anps_text_logo_source_1', "Google fonts")=='Google fonts' && get_option('font_type_1', 'Montserrat')!=get_option('anps_text_logo_font', 'Montserrat')) {
        wp_enqueue_style( "anps_text_logo_font",  'https://fonts.googleapis.com/css?family=' . get_option('anps_text_logo_font', '') . ':400italic,400,500,600,700,300');
    }

    wp_enqueue_style( "theme_main_style", get_bloginfo( 'stylesheet_url' ) );
    wp_enqueue_style( "anps_core", get_template_directory_uri() . "/css/core.css" );
    wp_enqueue_style( "theme_wordpress_style", get_template_directory_uri() . "/css/wordpress.css" );

    ob_start();
    anps_custom_styles();
    anps_custom_styles_buttons();
    $custom_css = ob_get_clean();

    //$custom_css = trim(preg_replace('/\s+/', ' ', $custom_css));

    wp_add_inline_style( 'theme_wordpress_style', $custom_css );

    wp_enqueue_style( "custom", get_template_directory_uri() . '/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'anps_scripts_and_styles' );

load_theme_textdomain( "transport", get_template_directory() . '/languages' );

/* Admin only scripts */

function anps_load_custom_wp_admin_scripts($hook) {
    /* Overwrite VC styling */
    wp_enqueue_style( "vc_custom", get_template_directory_uri() . '/css/vc_custom.css' );
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
    wp_enqueue_style( "wp-backend", get_template_directory_uri() . "/anps-framework/css/wp-backend.css" );

    wp_enqueue_style("anps-iconpicker-css", get_template_directory_uri()  . "/anps-framework/css/iconpicker.css");
    wp_enqueue_script('anps-iconpicker-js', get_template_directory_uri() . "/anps-framework/js/iconpicker.js", array( 'jquery' ), false, true);

    wp_register_script('ace-editor', get_template_directory_uri()  . '/js/ace.min.js', array('jquery'), '', true );

    wp_enqueue_script('hideseek_js', get_template_directory_uri() . "/anps-framework/js/jquery.hideseek.min.js", array( 'jquery' ), false, true);
    wp_enqueue_script('wp_backend_js', get_template_directory_uri() . "/anps-framework/js/wp_backend.js", array( 'jquery' ), false, true);
    wp_register_script('wp_colorpicker', get_template_directory_uri() . "/anps-framework/js/wp_colorpicker.js", array( 'wp-color-picker' ), false, true);
    if( 'appearance_page_theme_options' != $hook ) {
        return;
    }
    
    ob_start();
    anps_custom_styles_buttons();
    $custom_css = ob_get_clean();
    wp_add_inline_style( 'wp-backend', $custom_css );

    /* Theme Options Style */
    wp_register_script( "clipboard", get_template_directory_uri() . '/anps-framework/js/clipboard.min.js' , array( 'jquery' ));
    wp_enqueue_style( "admin-style", get_template_directory_uri() . '/anps-framework/css/admin-style.css' );
    if(!isset($_GET['sub_page']) || $_GET['sub_page'] == "theme_style" || $_GET['sub_page'] == "options" || $_GET['sub_page'] == "options_page_setup")
    wp_enqueue_style( "colorpicker", get_template_directory_uri() . '/anps-framework/css/colorpicker.css' );
    if (isset($_GET['sub_page']) && ($_GET['sub_page'] == "options" || $_GET['sub_page'] == "options_page"))
        wp_enqueue_script( "pattern", get_template_directory_uri() . "/anps-framework/js/pattern.js" );
    if(!isset($_GET['sub_page']) || $_GET['sub_page'] == "theme_style" || $_GET['sub_page'] == "options" || $_GET['sub_page'] == "options_page_setup") {
        wp_enqueue_script( "colorpicker_theme", get_template_directory_uri() . "/anps-framework/js/colorpicker.js" );
        wp_enqueue_script( "colorpicker_custom", get_template_directory_uri() . "/anps-framework/js/colorpicker_custom.js" );
    }
    if (isset($_GET['sub_page']) && $_GET['sub_page'] == "contact_form")
        wp_enqueue_script( "contact", get_template_directory_uri() . "/anps-framework/js/contact.js" );
    wp_enqueue_script( "theme-options", get_template_directory_uri() . "/anps-framework/js/theme-options.js" );
    wp_localize_script('theme-options', 'anps', array(
        'theme_url' => get_template_directory_uri(),
    ));

}
add_action( 'admin_enqueue_scripts', 'anps_load_custom_wp_admin_scripts' );


/*************************/
/*WOOCOMMERCE*/
/*************************/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_theme_support( 'woocommerce' );
    if (get_option('anps_product_zoom', '1') == '1') {
        add_theme_support( 'wc-product-gallery-zoom' );
    }
    if (get_option('anps_product_lightbox', '1') == '1') {
        add_theme_support( 'wc-product-gallery-lightbox' );
    }
    add_theme_support( 'wc-product-gallery-slider' );

    add_filter( 'woocommerce_enqueue_styles', '__return_false' );

    function anps_products_per_page() {
        return get_option('anps_products_per_page', '12');
    }
    add_filter( 'loop_shop_per_page', 'anps_products_per_page', 20 );


    function anps_loop_columns() {
        return get_option('anps_woo_columns', '4');
    }
    add_filter('loop_shop_columns', 'anps_loop_columns');

    function anps_woocommerce_header() {
        global $woocommerce, $anps_shop_data;
        /* old code (no option in theme options) */
        if( isset($anps_shop_data['shop_hide_cart']) && $anps_shop_data['shop_hide_cart'] == "on" ) {
            return false;
        }

        ?>
        <div class="woo-header-cart">
            <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'transport'); ?>">
                <span><?php echo $woocommerce->cart->cart_contents_count;?></span>
                <i class="fa fa-shopping-cart"></i>
            </a>
        </div>
        <?php
    }

    /* Load legacy files */
    function anps_wc_override_template_path(){
        return 'woocommerce-legacy/';
    }

    if( function_exists('WC') && WC()->version < '3.0.0' ) {
        add_filter( 'woocommerce_template_path', 'anps_wc_override_template_path' );
    }

    // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
    add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

    function woocommerce_header_add_to_cart_fragment( $fragments ) {
        global $woocommerce;

        ob_start();

        ?>
        <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'transport'); ?>">
            <span><?php echo $woocommerce->cart->cart_contents_count;?></span>
            <i class="fa fa-shopping-cart"></i>
        </a>
        <div class="mini-cart">
            <?php woocommerce_mini_cart(); ?>
        </div>
        <?php

        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }

    /* Support for WooCommerce */
    add_theme_support("woocommerce");

    define("WOOCOMMERCE_USE_CSS", false );


 function myaccount_sidebar($page) { ?>

            <div class="col-md-3 sidebar">

                <ul class="myaccount-menu">
                    <li class="widget-container widget_nav_menu">
                        <div class="menu-main-menu-container">
                            <ul class="menu">
                                <li class="menu-item<?php if($page == "myaccount"){ echo " current-menu-item page_item current_page_item current_page_parent"; } ?>"><a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"><?php _e("My Orders", "transport"); ?></a></li>
                                <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ): ?>
                                    <li class="menu-item<?php if($page == "wishlist"){ echo " current-menu-item page_item current_page_item current_page_parent"; } ?>"><a href="<?php echo get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ); ?>"><?php _e("My Wishlist", "transport"); ?></a></li>
                                <?php endif; ?>
                                <li class="menu-item<?php if($page == "billing"){ echo " current-menu-item page_item current_page_item current_page_parent"; } ?>"><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'billing' ); ?>"><?php _e("Edit Billing Address", "transport"); ?></a></li>
                                <li class="menu-item<?php if($page == "shipping"){ echo " current-menu-item page_item current_page_item current_page_parent"; } ?>"><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'shipping' ); ?>"><?php _e("Edit Shipping Address", "transport"); ?></a></li>
                                <li class="menu-item<?php if($page == "change_account"){ echo " current-menu-item page_item current_page_item current_page_parent"; } ?>"><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e("Edit Account", "transport"); ?></a></li>
                                <?php
                                    if (is_user_logged_in()) {
                                        echo '<li><a href="'. wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ) .'">' . __("Logout", "transport") . '</a></li>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </li>
                </ul>

            </div>
        <?php
    }

    add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
    function jk_related_products_args( $args ) {
        global $anps_shop_data;
        /* old code, there are no such and option in theme options */
        if(!isset($anps_shop_data['shop_related_per_page'])||$anps_shop_data['shop_related_per_page']=="") {
            $per_page = 3;
        } else {
            $per_page = $anps_shop_data['shop_related_per_page'];
        }
        $args['posts_per_page'] = $per_page;
        return $args;
    }
    
    /* Remove breadcrumbs */
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

    /* Remove page title */
    add_filter('woocommerce_show_page_title', '__return_false' );

    /* Remove sidebar */
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10, 0);

    /* Wrap result count and order in .before-loop */
    remove_action ('woocommerce_before_shop_loop', 'woocommerce_result_count', 20, 0);
    remove_action ('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30, 0);
    add_action ('woocommerce_before_shop_loop', 'anps_before_shop_loop', 20);
    
    function anps_before_shop_loop() { 
        echo '<div class="woocommerce-before-loop">';
            woocommerce_result_count();
            woocommerce_catalog_ordering();
        echo '</div>';
    }

    /* Remove add to cart button */

    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 0);

    /* Pagination */
    function filter_woocommerce_pagination_args( $array ) { 
        $array['prev_text'] = '';
        $array['next_text'] = '';
        return $array; 
    }; 
            
    add_filter( 'woocommerce_pagination_args', 'filter_woocommerce_pagination_args', 10, 1 ); 

    add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 20 );
}
/*************************/
/*END WOOCOMMERCE*/
/*************************/

/* Set Revolution Slider as Theme */
if(function_exists( 'set_revslider_as_theme' )){
    add_action( 'init', 'anps_set_rev_as_theme' );
    function anps_set_rev_as_theme() {
        set_revslider_as_theme();
    }
}

/* Title fallback for older versions */

if ( ! function_exists( '_wp_render_title_tag' ) ) :
    function anps_render_title() {
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
    }
    add_action( 'wp_head', 'anps_render_title' );
endif;

/* Change comment form position (WordPress 4.4) */
function anps_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'anps_comment_field_to_bottom' );

/* WooCommerce 2.5 remove link around products */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

function anps_cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'anps_cc_mime_types');

/* Remove Newsletter styling */
add_filter('newsletter_enqueue_style', '__return_false');

function anps_sidebar() {
    $meta = get_post_meta(get_option('woocommerce_shop_page_id'));
    $left_sidebar = false;
    $right_sidebar = false;

    if (isset($meta['sbg_selected_sidebar']) && isset($meta['sbg_selected_sidebar'][0]) && $meta['sbg_selected_sidebar'][0] != '0' ) {
        $left_sidebar = $meta['sbg_selected_sidebar'][0];
        $num_of_sidebars++;
    }

    if (isset($meta['sbg_selected_sidebar_replacement']) && isset($meta['sbg_selected_sidebar_replacement'][0]) && $meta['sbg_selected_sidebar_replacement'][0] != '0' ) {
        $right_sidebar = $meta['sbg_selected_sidebar_replacement'][0];
        $num_of_sidebars++;
    }
}

function anps_sidebar_html($sidebar) {
    ?>
    <aside class="sidebar col-md-3">
        <ul>
            <?php dynamic_sidebar($sidebar); wp_reset_query(); ?>
        </ul>
    </aside>  
    <?php
}

function anps_left_sidebar($id) {
    $meta = get_post_meta($id);

    if (isset($meta['sbg_selected_sidebar']) && isset($meta['sbg_selected_sidebar'][0]) && $meta['sbg_selected_sidebar'][0] != '0' ) {
        anps_sidebar_html($meta['sbg_selected_sidebar'][0]);
    }
}

function anps_right_sidebar($id) {
    $meta = get_post_meta($id);

    if (isset($meta['sbg_selected_sidebar_replacement']) && isset($meta['sbg_selected_sidebar_replacement'][0]) && $meta['sbg_selected_sidebar_replacement'][0] != '0' ) {
        anps_sidebar_html($meta['sbg_selected_sidebar_replacement'][0]);
    }
}

function anps_num_sidebars($id) {
    $meta = get_post_meta($id);
    $num_of_sidebars = 0;

    if (isset($meta['sbg_selected_sidebar']) && isset($meta['sbg_selected_sidebar'][0]) && $meta['sbg_selected_sidebar'][0] != '0' ) {
        $num_of_sidebars++;
    }

    if (isset($meta['sbg_selected_sidebar_replacement']) && isset($meta['sbg_selected_sidebar_replacement'][0]) && $meta['sbg_selected_sidebar_replacement'][0] != '0' ) {
        $num_of_sidebars++;
    }

    return $num_of_sidebars;
}