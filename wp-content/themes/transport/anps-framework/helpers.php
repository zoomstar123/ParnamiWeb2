<?php
/* Wrap font with quotes */
if (!function_exists('anps_wrap_font')) {
    function anps_wrap_font($font) {
        $temp = explode(', ', $font);
        $return = '';

        if (count($temp) > 1) {
            foreach ($temp as $name) {
                if ($return === '') {
                    $return .= "'$name'";
                } else {
                    $return .= ", $name";
                }
            }
        } else {
            $return = "'$font'";
        }

        return $return;
    }
}
/* Header image, video, gallery (blog, portfolio) */
function anps_header_media($id, $image_class="") {
    if(has_post_thumbnail($id)) {
        $header_media = get_the_post_thumbnail($id, $image_class);
    }
    elseif(get_post_meta($id, $key ='anps_featured_video', $single = true )) {
        $header_media = do_shortcode(get_post_meta($id, $key ='anps_featured_video', $single = true ));
    }
    else {
        $header_media = "";
    }
    return $header_media;
}
/* Header image, video, gallery (single blog/portfolio) */
function anps_header_media_single($id, $image_class="") {
    if(has_post_thumbnail($id) && !get_post_meta($id, $key ='gallery_images', $single = true )) {
        $header_media = get_the_post_thumbnail($id, $image_class);
    }
    elseif(get_post_meta($id, $key ='anps_featured_video', $single = true )) {
        $header_media = do_shortcode(get_post_meta($id, $key ='anps_featured_video', $single = true ));
    }
    elseif(get_post_meta($id, $key ='gallery_images', $single = true )) {
        $gallery_images = explode(",",get_post_meta($id, $key ='gallery_images', $single = true ));

        foreach($gallery_images as $key=>$item) {
            if($item == '') {
                unset($gallery_images[$key]);
            }
        }
        $number_images = count($gallery_images);
        $header_media = "";
        $header_media .= "<div id='carousel' class='carousel slide'>";
        if($number_images>"1") {
            $header_media .= "<ol class='carousel-indicators'>";
            for($i=0;$i<count($gallery_images);$i++) {
                if($i==0) {
                    $active_class = "active";
                } else {
                    $active_class = "";
                }
                $header_media .= "<li data-target='#carousel' data-slide-to='".$i."' class='".$active_class."'></li>";
            }
            $header_media .= "</ol>";
        }
        $header_media .= "<div class='carousel-inner'>";
        $j=0;
        foreach($gallery_images as $item) {
            $image_src = wp_get_attachment_image_src($item, $image_class);
            $image_title = get_the_title($item);
            if($j==0) {
                $active_class = " active";
            } else {
                $active_class = "";
            }
            $header_media .= "<div class='item$active_class'>";
            $header_media .= "<img alt='".$image_title."'  src='".$image_src[0]."'>";
            $header_media .= "</div>";
            $j++;
        }
        $header_media .= "</div>";
        if($number_images>"1") {
            $header_media .= "<a class='left carousel-control' href='#carousel' data-slide='prev'>
                                <span class='fa fa-chevron-left'></span>
                              </a>
                              <a class='right carousel-control' href='#carousel' data-slide='next'>
                                <span class='fa fa-chevron-right'></span>
                              </a>";
        }
        $header_media .= "</div>";
    }
    else {
        $header_media = "";
    }
    return $header_media;
}
if( !function_exists('anps_get_header') ) {
    function anps_get_header() {
        global $anps_page_data, $anps_options_data;
        /* Get fullscreen page option */
        $page_heading_full = '';
        if (get_option('anps_menu_type', '2')!='5' && get_option('anps_menu_type', '2')!='6') {
            $page_heading_full = get_post_meta(get_queried_object_id(), $key ='anps_page_heading_full', $single = true );
        }
        if( is_404() ) {
            $page_heading_full = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='anps_page_heading_full', $single = true );
        }
        //Let's get menu type
        if (anps_get_option('', '0','vertical-menu')!= '0') {
            $anps_menu_type = "2";
        } else if (is_front_page() == "true" ) {
            $anps_menu_type = get_option('anps_menu_type', '2');
        } else {
            $anps_menu_type = "2";
        }

        $anps_full_screen = get_option('anps_full_screen', '');

        $menu_type_class = ' site-header-style-normal';
        $header_position_class = '';
        $header_bg_style_class = '';
        $absoluteheader = 'false';

        //Header classes and variables
        if($anps_menu_type == "1" || (isset($page_heading_full)&&$page_heading_full=="on")) {
            $menu_type_class = "";
            $header_position_class = "";
            $header_bg_style_class = " site-header-style-transparent";
            $absoluteheader = "true";
        } elseif($anps_menu_type == "3") {
            $menu_type_class = "";
            $header_position_class = " site-header-position-bottom";
            $header_bg_style_class = " site-header-style-transparent";
            $absoluteheader = "true";
        } elseif($anps_menu_type == "4") {
            $menu_type_class = " site-header-style-normal";
            $header_position_class = "";
            $header_bg_style_class = "";
            $absoluteheader = "false";
        }

        if(get_option('anps_menu_type', '2')=='5') {
            $menu_type_class = " site-header-style-full-width";
            $header_position_class = "";
            $anps_menu_type = '5';
        }
        if(get_option('anps_menu_type', '2')=='6') {
            $menu_type_class = " site-header-style-boxed";
            $header_position_class = "";
            $anps_menu_type = '6';
        }

        //Top menu style
        $topmenu_style = anps_get_option('', '1','topmenu_style');

        //left, right and center menu styles:
        $menu_center = anps_get_option('', '', 'menu_center');
        if ($menu_center != "" && ($anps_menu_type == "2" || $anps_menu_type == "4")) {
          $menu_type_class .= " site-header-layout-center";
        } else if($anps_menu_type == "5") {
          $menu_type_class .= "";
        } else {
          $menu_type_class .=" site-header-layout-normal";
        }

        //sticky menu
        $sticky_menu = anps_get_option('', '', 'sticky_menu');
        $sticky_menu_class = "";
        if ($sticky_menu=="1" || $sticky_menu=="on") {
            $sticky_menu_class = " site-header-sticky";
        }
        //if coming soon page is enabled
        $coming_soon = anps_get_option('', '0', 'coming_soon');
        if($coming_soon=="0"||is_super_admin()) :

        //check for topmenu_style and add class depends on that value (mobile/desktop on/off)
        $hide_topmenu = '';
        if($topmenu_style=='4') {
            $hide_topmenu = ' hidden-xs hidden-sm';
        } elseif($topmenu_style=='2') {
            $hide_topmenu .= ' hidden-md hidden-lg';
        }
        /* Single page top bar on/off */
        $top_bar_site = get_post_meta(get_queried_object_id(), $key ='anps_header_options_top_bar', $single = true );
        //added option for transparent top bar menu type 1 (24.2.2015)
        if (($anps_menu_type == '1' || (isset($page_heading_full) && $page_heading_full!=''))
                && ((anps_get_option('', '', 'topmenu_style') != '3' && isset($top_bar_site) && $top_bar_site != '1')
                    || (anps_get_option('', '', 'topmenu_style') == '3' && isset($top_bar_site) && $top_bar_site == '2'))
        ) :
            $top_bar_bg_color = get_option('anps_front_topbar_bg_color', '');
            $transparent_class = '';
            if ((!isset($top_bar_bg_color) || $top_bar_bg_color=='')
                    || (isset($page_heading_full) && $page_heading_full!='')
            ) {
                $transparent_class = 'transparent ';
            } ?>
            <div class="<?php echo esc_attr($transparent_class);?>top-bar<?php echo esc_attr($hide_topmenu); ?>"><?php anps_get_top_bar(); ?></div>
        <?php endif;
        //topmenu
        if ($anps_menu_type != '1'
                && ((anps_get_option('', '', 'topmenu_style') != '3' && isset($top_bar_site) && $top_bar_site != '1')
                    || (anps_get_option('', '', 'topmenu_style') == '3' && isset($top_bar_site) && $top_bar_site == '2'))
                && (!isset($page_heading_full) || $page_heading_full=='')
        ) : ?>
            <div class="top-bar<?php echo esc_attr($hide_topmenu); ?>"><?php anps_get_top_bar(); ?></div>
        <?php endif;
        // load shortcode from theme options textarea if needed
        if ($anps_menu_type=='3' || $anps_menu_type=='4') {
            echo do_shortcode($anps_full_screen);
        }

        global $anps_media_data;
        $has_sticky_class= '';

        $anps_header_styles = esc_attr($sticky_menu_class) . esc_attr($menu_type_class) . esc_attr($header_position_class) . esc_attr($header_bg_style_class) . esc_attr($has_sticky_class);
        /* Check for vertical */
        $is_vertical = anps_get_option($anps_options_data, 'vertical_menu') == '1';
        $header_style = '';
        if ($is_vertical) {
            $anps_header_styles = ' site-header-vertical-menu';
        }
        $header_bg_image = '';
        if (anps_get_option($anps_options_data, 'custom-header-bg-vertical') != "")
            {
            $header_bg_image = esc_attr(anps_get_option($anps_options_data, 'custom-header-bg-vertical'));
            $header_style = ' style= "background: transparent url('. $header_bg_image .') no-repeat scroll center 0 / 100% auto;"';
            }
        ?>
        <header class="site-header<?php echo esc_attr($anps_header_styles); ?><?php if(get_option('anps_main_menu_selection', '0')=='0' && !$is_vertical) { echo ' site-header-divider'; } ?>"<?php echo $header_style;?>>
            <?php if(get_option('anps_menu_type', '2')!='5' && get_option('anps_menu_type', '2')!='6' || $is_vertical) : ?>
            <div class="nav-wrap">
                <div class="container"><?php anps_get_site_header();?>
                </div>
            </div>
            <?php else : ?>
            <div class="container preheader-wrap">
                <div class="site-logo"><?php anps_get_logo(); ?></div>
                <?php if((is_active_sidebar( 'large-above-menu')) && !$is_vertical) : ?>
                    <?php
                        $class = 'large-above-menu';

                        if( get_option('anps_large_above_menu_style', '1') == '1' ) {
                            $class .= ' large-above-menu-style-1';
                        } else {
                            $class .= ' large-above-menu-style-2';
                        }
                    ?>
                    <div class="<?php echo esc_attr($class); ?>"><?php do_shortcode(dynamic_sidebar( 'large-above-menu' ));?></div>
                <?php endif;
                    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                        $shopping_cart_header = anps_get_option('', 'shop_only', 'shopping_cart_header');
                        if (($shopping_cart_header == 'shop_only' &&  is_woocommerce() ) || $shopping_cart_header == 'always' ) {
                            echo "<div class='hidden-md hidden-lg cartwrap'>";
                            anps_woocommerce_header();
                            echo "</div>";
                        }
                    }
                ?>
            </div>
            <div class="header-wrap">
                <div class="container">
                    <?php echo anps_get_menu(); ?>
                </div>
            </div>
            <?php endif; ?>
            <?php
            if( ($is_vertical != '') && (is_active_sidebar( 'vertical-bottom-widget')) ) : ?>
                <div class="vertical-bottom-sidebar">
                    <div class="vertical-bottom">
                        <?php do_shortcode(dynamic_sidebar( 'vertical-bottom-widget' ));?>
                    </div>
                </div>
            <?php endif;?>
        </header>
        <?php
            $disable_single_page = get_post_meta(get_queried_object_id(), $key ='anps_disable_heading', $single = true );
            if(!$disable_single_page=="1" && (!isset($page_heading_full) || $page_heading_full=="")) :
                if(is_front_page()==false && anps_get_option($anps_options_data, 'disable_heading')!="1") :
                    global $anps_media_data;
                    $style = "";
                    $class = "";
                    $single_page_bg = get_post_meta(get_queried_object_id(), $key ='heading_bg', $single = true );

                    /* Single page BG color */
                    $single_page_bg_color = get_post_meta(get_queried_object_id(), $key ='anps_heading_bg_color', $single = true );
                    if( $single_page_bg_color != '' ) {
                        $single_page_bg_color = ' background-color: ' . $single_page_bg_color . ';';
                    }

                    /* Theme Options BG color */
                    $anps_heading_bg_color = get_option('anps_page_heading_bg_color', '');
                    if( $anps_heading_bg_color != '' ) {
                        $anps_heading_bg_color = ' background-color: ' . $anps_heading_bg_color . ';';
                    }

                    if(is_search()) {
                        if(anps_get_option($anps_media_data, 'search_heading_bg')) {
                            $style = ' style="background-image: url('.esc_url(anps_get_option($anps_media_data, 'search_heading_bg')).');' . $anps_heading_bg_color . '"';
                        } elseif( $anps_heading_bg_color != '' ) {
                            $style = ' style="' . $anps_heading_bg_color . '"';
                        } else {
                            $class = "style-2";
                        }
                    } else if( is_404() ) {
                        $error_page_bg = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='heading_bg', $single = true );
                        $error_page_bg_color = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='anps_heading_bg_color', $single = true );

                        if( $error_page_bg_color != '' ) {
                            $error_page_bg_color = ' background-color: ' . $error_page_bg_color . ';';
                        } else if( $anps_heading_bg_color != '' ) {
                            $error_page_bg_color = $anps_heading_bg_color;
                        }

                        $style = ' style="background-image: url('.esc_url($error_page_bg).');' . $error_page_bg_color . '"';
                    } else {
                        $anps_heading_bg = anps_get_option($anps_media_data, 'heading_bg');

                        if( $single_page_bg_color != '' ) {
                            $anps_heading_bg_color = $single_page_bg_color;
                        }

                        if($single_page_bg) {
                            $style = ' style="background-image: url('.esc_url($single_page_bg).');' . $anps_heading_bg_color . '"';
                        }
                        elseif($anps_heading_bg && isset($anps_heading_bg)) {
                            $style = ' style="background-image: url('.esc_url($anps_heading_bg).');' . $anps_heading_bg_color . '"';
                        } elseif( $anps_heading_bg_color != '' ) {
                            $style = ' style="' . $anps_heading_bg_color . '"';
                        } else {
                            $class = "style-2";
                        }
                    }

                    if(get_option('anps_title_breadcrumbs', '1') == '1') : ?>
                        <div class='page-heading <?php echo esc_attr($class); ?>' <?php echo $style; ?>>
                            <div class='container'>
                                <?php echo anps_site_title(); ?>
                                <?php if(anps_get_option($anps_options_data, 'breadcrumbs') !='1') { echo anps_the_breadcrumb(); } ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="page-header text-center page-header-sm" <?php echo $style; ?>">
                            <?php echo anps_site_title(); ?>
                        </div>
                        <?php if(anps_get_option($anps_options_data, 'breadcrumbs') !='1') :?>
                            <div class="row breadcrumb">
                                <div class="container text-left">
                                    <?php echo anps_the_breadcrumb(); ?>
                                </div>
                            </div>
                        <?php endif;?>
                    <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php if(isset($page_heading_full)&&$page_heading_full=='on') : ?>
        <?php
            $heading_value = get_post_meta(get_queried_object_id(), $key ='heading_bg', $single = true );

            if( is_404() ) {
                $heading_value = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='heading_bg', $single = true );
            }

            /* Page heading BG color */
            $anps_heading_bg_color = get_option('anps_page_heading_bg_color', '');

            if( is_404() ) {
                $anps_heading_meta_bg_color = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='heading_bg', $single = true );
            } else {
                $anps_heading_meta_bg_color = get_post_meta(get_queried_object_id(), $key ='anps_heading_bg_color', $single = true );
            }

            if( $anps_heading_meta_bg_color != '' ) {
                $anps_heading_bg_color = $anps_heading_meta_bg_color;
            }

            if( $anps_heading_bg_color != '' ) {
                $anps_heading_bg_color = ' background-color: ' . $anps_heading_bg_color . ';';
            }
        ?>

        <?php if( get_option('anps_menu_type', '2')=='5'|| get_option('anps_menu_type', '2')=='6' ): ?>
            <?php
                $height_value = get_post_meta(get_queried_object_id(), $key ='anps_full_height', $single = true );

                if( $height_value ) {
                    $height_value = 'height: ' . $height_value . 'px; ';
                }
                ?>

            <div class="paralax-header parallax-window" data-type="background" data-speed="2" style="<?php echo esc_attr($height_value); ?>background-image: url(<?php echo esc_url($heading_value); ?>);<?php echo $anps_heading_bg_color; ?>">
        <?php endif; ?>

        <div class='page-heading'>
            <div class='container'>
                <?php echo anps_site_title(); ?>
                <?php if(anps_get_option($anps_options_data, 'breadcrumbs')!='1') { echo anps_the_breadcrumb(); } ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php
    endif;
    }
}

function anps_page_full_screen_style() {
    $full_color_top_bar = get_post_meta(get_queried_object_id(), $key ='anps_full_color_top_bar', $single = true );
    $full_color_title = get_post_meta(get_queried_object_id(), $key ='anps_full_color_title', $single = true );
    $full_hover_color = get_post_meta(get_queried_object_id(), $key ='anps_full_hover_color', $single = true );
    if(!isset($full_color_top_bar) || $full_color_top_bar=="") {
        $top_bar_color = anps_get_option('', '', 'top_bar_color');
    } else {
        $top_bar_color = $full_color_top_bar;
    }
    if(!isset($full_color_title) || $full_color_title=="") {
        $title_color = anps_get_option('', '', 'menu_text_color');
    } else {
        $title_color = $full_color_title;
    }
    if(!isset($full_hover_color) || $full_hover_color=="") {
        $hover_color = anps_get_option('', '', 'hovers_color');
    } else {
        $hover_color = $full_hover_color;
    }

    ?>
    <style>
    .paralax-header > .page-heading .breadcrumbs li a:after, .paralax-header > .page-heading h1, .paralax-header > .page-heading ul.breadcrumbs, .paralax-header > .page-heading ul.breadcrumbs a, .site-navigation ul:not(.sub-menu) > li.menu-item > a
     {color:<?php echo esc_attr($title_color); ?>;}

    .transparent.top-bar, .transparent.top-bar a
    {color:<?php echo esc_attr($top_bar_color); ?>;}

    .transparent.top-bar a:hover, .paralax-header > .page-heading ul.breadcrumbs a:hover, .site-navigation ul > li.menu-item > a:hover
     {color:<?php echo esc_attr($hover_color); ?>;}

    @media (min-width: 992px) {
     header:not(.site-header-sticky-active) .nav-bar .site-search-toggle:not(:hover):not(:focus), header:not(.site-header-sticky-active) .nav-bar .woo-header-cart .cart-contents > i
     {color:<?php echo esc_attr($title_color); ?>;}
    }

    </style>
<?php
}
function anps_site_title() {
    global $anps_page_data;

    if (is_home() && !is_front_page()) {
        $title = "<h1>".get_the_title(get_option('page_for_posts'))."</h1>";
    } else if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_shop() ) {
        $title = "<h1>".get_the_title(get_option('woocommerce_shop_page_id'))."</h1>";
    } else if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_product_category() ) {
        $title = "<h1>".single_cat_title("", false)."</h1>";
    } else if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_product_tag() ) {
        $title = "<h1>".single_cat_title("", false)."</h1>";
    } else if( is_archive() ) {
        if( is_category() ) {
            $cat = get_category(get_queried_object_id());
            $title = "<h1>".__("Archives for", "transport") . ' ' . $cat->name . " </h1>";
        }
        else if(is_author()) {
            $author = get_the_author_meta('display_name', get_query_var("author"));
            $title = "<h1>".__("Posts by", "transport") . ' ' . $author .  " </h1>";
        } elseif(is_tag()) {
            $cat = get_tag(get_queried_object_id());
            $title = "<h1>".$cat->name . "</h1>";
        }
        else {
            if( get_post_type() == 'post' ) {
                $title = "<h1>".__("Archives for", "transport") . " " . get_the_date('F') . ' ' . get_the_date('Y')."</h1>";
            } else {
                $obj = get_post_type_object( get_post_type() );
                if( $obj->has_archive ) {
                    $title = '<h1>' . $obj->labels->name . '</h1>';
                }
            }
        }
    } elseif(is_search()) {
        $title = "<h1>".__("Search results", "transport")."</h1>";
    } elseif( is_404() ) {
        if(anps_get_option($anps_page_data, 'error_page') != '0') {
            $title = "<h1>" . get_the_title(anps_get_option($anps_page_data, 'error_page')) . "</h1>";
        } else {
            $title = "<h1>" . __("Error 404", "transport") . "</h1>";
        }
    }
    else { ?>
    <?php if(get_the_title()) { $title = "<h1>".get_the_title()."</h1>"; } else { $title = "<h1>".get_the_title(anps_get_option($anps_page_data, 'error_page'))."</h1>"; } ?>
    <?php }
    return $title;
}

function anps_get_sticky_logo() {
    global $anps_media_data;
    if (anps_get_option($anps_media_data, 'sticky_logo') != '') : ?>
        <img class="logo-sticky" alt="Site logo" src="<?php echo esc_url(anps_get_option($anps_media_data, 'sticky_logo')); ?>">
    <?php endif;
}

/* Breadcrumbs */
if(!function_exists('anps_the_breadcrumb')) {
    function anps_the_breadcrumb() {
        global $anps_page_data, $post;
        $return_val = "<ul class='breadcrumbs'>";

        $return_val .= '<li><a href="' . home_url() .'">' . __("Home", "transport") . '</a></li>';
        if (is_home() && !is_front_page()) {
            $return_val .= "<li>".get_the_title(get_option('page_for_posts'))."</li>";
        } else {
            if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_woocommerce() ) {
                $return_val = "<ul class='breadcrumbs'>";
                ob_start();
                woocommerce_breadcrumb();
                $return_val .= ob_get_clean();
            } elseif (is_category() || is_single()) {
                if (is_single()) {
                    if (get_post_type() != "portfolio" && get_post_type() != "post") {
                        $obj = get_post_type_object( get_post_type() );
                        if( $obj->has_archive ) {
                            $return_val .= '<li><a href="' . get_post_type_archive_link(get_post_type()) . '">' . $obj->labels->name . '</a></li>';
                        }
                        $return_val .= '<li>' . get_the_title() . '</li>';
                    } else {
                        $custom_breadcrumbs = get_post_meta( get_the_ID(), $key = 'custom_breadcrumbs', $single = true );
                        if($custom_breadcrumbs!="" && $custom_breadcrumbs!="0") {
                            $return_val .= "<li><a href='".get_permalink($custom_breadcrumbs)."'>".get_the_title($custom_breadcrumbs)."</a></li>";
                        }
                        $return_val .= "<li>".get_the_title()."</li>";
                    }
                }
            }
            elseif (is_page()) {
                if(isset($post->post_parent) && ($post->post_parent!=0 || $post->post_parent!="")) {
                    $parent_id  = $post->post_parent;
                    while ($parent_id) {
                        $page = get_page($parent_id);
                        $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
                        $parent_id  = $page->post_parent;
                    }
                    for($i=count($breadcrumbs);$i>=0;$i--) {
                        $return_val .= isset($breadcrumbs[$i]) ? $breadcrumbs[$i] : null;
                    }
                    $return_val .= "<li>".get_the_title()."</li>";
                } else {
                    $return_val .= "<li>".get_the_title()."</li>";
                }
            } elseif (is_archive()) {
                if (is_author()) {
                    $author = get_the_author_meta('display_name', get_query_var("author"));
                    $return_val .= "<li>" . $author ."</li>";
                } elseif(is_tag()) {
                    $cat = get_tag(get_queried_object_id());
                    $return_val .= "<li>".$cat->name . "</li>";
                } else {
                    if( get_post_type() == 'post' ) {
                        $return_val .= "<li>" . __("Archives for", "transport") . " " . get_the_date('F') . ' ' . get_the_date('Y')."</li>";
                    } else {
                        $obj = get_post_type_object( get_post_type() );
                        if( $obj->has_archive ) {
                            $return_val .= '<li>' . $obj->labels->name . '</li>';
                        }
                    }

                }
            } else if(is_search()) {
                $return_val .= "<li>" . __('Search', 'transport') ."</li>";
            } else {
                if (get_search_query() != "") {
                } else {
                    if( anps_get_option($anps_page_data, 'error_page') != '' && anps_get_option($anps_page_data, 'error_page') != '0' ) {
                        query_posts('post_type=page&p=' . anps_get_option($anps_page_data, 'error_page'));

                        while(have_posts()) { the_post();
                            $return_val .= "<li>" . get_the_title() . "</li>";
                        }

                        wp_reset_query();
                    } else {
                        $return_val .= "<li>" . __("Error 404", "transport") . "</li>";
                    }
                }
            }
        }
        if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_woocommerce() ) {
        } elseif (single_cat_title("", false) != "" && !is_tag()) {
            $return_val .= "<li>" . single_cat_title("", false)."</li>";
        }
        $return_val .= "</ul>";
        return $return_val;
    }
}
/* search container */
function anps_get_search() {
    ?>
    <div class="container">
      <form role="search" method="get" class="site-search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
          <input name="s" type="text" class="site-search-input" placeholder="<?php _e("type and press &#8216;enter&#8217;", 'transport'); ?>">
      </form>
      <button class="site-search-close">&times;</button>
    </div>
<?php
}
/* top bar menu */
function anps_get_top_bar() {
    if (is_active_sidebar( 'top-bar-left') || is_active_sidebar( 'top-bar-right') ) {
        echo '<div class="container">';
            echo '<div class="top-bar-left">';
                    do_shortcode(dynamic_sidebar( 'top-bar-left' ));
            echo '</div>';
            echo '<div class="top-bar-right">';
                    do_shortcode(dynamic_sidebar( 'top-bar-right' ));
            echo '</div>';
    echo '</div>';
        }
    ?>
    <button class="top-bar-close">
      <i class="fa fa-chevron-down"></i>
      <span class="sr-only"><?php _e('Close top bar', 'transport'); ?></span>
    </button>
    <?php
}
function anps_body_style() {
    global $anps_options_data;

    if (anps_get_option($anps_options_data, 'pattern') == '0' && anps_get_option($anps_options_data, 'boxed') == '1') {
        if(anps_get_option($anps_options_data, 'type') == "custom color") {
            echo ' style="background-color: ' . esc_attr(anps_get_option($anps_options_data, 'bg_color')) . ';"';
        }else if (anps_get_option($anps_options_data, 'type') == "stretched") {
            echo ' style="background: url(' . esc_url(anps_get_option($anps_options_data, 'custom_pattern')) . ') center center fixed;background-size: cover;     -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;"';
        } else {
            echo ' style="background: url(' . esc_url(anps_get_option($anps_options_data, 'custom_pattern')) . ')"';
        }
    }
}
function anps_theme_after_styles() {
    if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );

    get_template_part("includes/shortcut_icon");
}
/* Return site logo */
function anps_get_logo() {
    global $anps_media_data, $anps_options_data;
    $first_page_logo = get_option('anps_front_logo', '');
    $menu_type = get_option('anps_menu_type');
    $page_heading_full = get_post_meta(get_queried_object_id(), $key ='anps_page_heading_full', $single = true );
    $full_screen_logo = get_post_meta(get_queried_object_id(), $key ='anps_full_screen_logo', $single = true );
    $text_logo = get_option('anps_text_logo','');
    $size_sticky = array(120, 120);
    if( ! $size_sticky ) {
        $size_sticky = array(120, 120);
    }
    $logo_width = 158;
    $logo_height = 33;
    if( anps_get_option($anps_media_data, 'logo-width') !='' ) {
        $logo_width = anps_get_option($anps_media_data, 'logo-width');
    }

    if( anps_get_option($anps_media_data, 'logo-height') != '' ) {
        $logo_height = anps_get_option($anps_media_data, 'logo-height');
    }
    if(get_option('auto_adjust_logo', 'on') !='' ) {
        $logo_height = 'auto';
        $logo_width = 'auto';
    }
    else { $logo_width .='px';
    }

    echo '<a href="' . esc_url(home_url("/")) . '">';
    if(anps_get_option($anps_options_data, 'vertical_menu') != '1' ) {
        anps_get_sticky_logo();
    }

    if(isset($page_heading_full) && $page_heading_full=="on" && isset($full_screen_logo) && $full_screen_logo!="0") : ?>
        <img style="width: <?php echo esc_attr($logo_width); ?>; height: <?php echo esc_attr($logo_height); ?>px" alt="Site logo" src="<?php echo esc_url($full_screen_logo); ?>">
    <?php else :
    if(($menu_type==1 || $menu_type==3) && $first_page_logo && (is_front_page())) : ?>
        <img style="width: <?php echo esc_attr($logo_width); ?>; height: <?php echo esc_attr($logo_height); ?>px" alt="Site logo" src="<?php echo esc_url($first_page_logo); ?>">
    <?php
    elseif (anps_get_option($anps_media_data, 'logo') != '') : ?>
        <img style="width: <?php echo esc_attr($logo_width); ?>; height: <?php echo esc_attr($logo_height); ?>px" alt="Site logo" src="<?php echo esc_url(anps_get_option($anps_media_data, 'logo')); ?>">
    <?php elseif(isset($text_logo) && $text_logo!='') : ?>
        <?php echo str_replace('\\"', '"', $text_logo); ?></a>
    <?php else: ?>
        <img style="width: <?php echo esc_attr($logo_width); ?>; height: <?php echo esc_attr($logo_height); ?>px" alt="Site logo" src="http://anpsthemes.com/transporto/wp-content/uploads/2015/03/transporto-logo-dark.png">
    <?php endif;
    endif;
    echo '</a>';
}
/* Tags and author */
function anps_tagsAndAuthor() {
    ?>
        <div class="tags-author">
    <?php echo __('posted by', "transport"); ?> <?php echo get_the_author(); ?>
    <?php
    $posttags = get_the_tags();
    if ($posttags) {
        echo " &nbsp;|&nbsp; ";
        echo __('Taged as', "transport") . " - ";
        $first_tag = true;
        foreach ($posttags as $tag) {
            if ( ! $first_tag) {
                echo ', ';
            }
            echo '<a href="' . esc_url(home_url('/')) . 'tag/' . esc_html($tag->slug) . '/">';
            echo esc_html($tag->name);
            echo '</a>';
            $first_tag = false;
        }
    }
    ?>
        </div>
    <?php
}
/* Current page url */
function anps_curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"])) $pageURL .= "s";
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80")
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    else
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

    return $pageURL;
}
/* Gravatar */
add_filter('avatar_defaults', 'anps_newgravatar');
function anps_newgravatar($avatar_defaults) {
    $myavatar = get_template_directory_uri() . '/images/move_default_avatar.jpg';
    $avatar_defaults[$myavatar] = "Anps default avatar";
    return $avatar_defaults;
}
/* Get post thumbnail src */
function anps_get_the_post_thumbnail_src($img) {
    return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}

function anps_get_menu() {
    $menu_center = anps_get_option('', '', 'menu_center');
    if( isset($_GET['header']) && $_GET['header'] == 'type-3' ) {
        $menu_center = 'on';
    }

    $menu_description = '';
    $menu_style = anps_get_option('', '1', 'menu_style');
    if( isset($_GET['header']) && $_GET['header'] == 'type-2' ) {
        $menu_style = '2';
    }

    if( $menu_style == '2' ) {
        $menu_description = ' description';
    }
    global $anps_options_data;
    //above nav bar && single above nav bar
    $above_nav_bar = get_option('anps_above_nav_bar', '');
    $above_nav_bar_site = get_post_meta(get_queried_object_id(), $key ='anps_header_options_above_menu', $single = true );
    ?>
    <div class="nav-bar-wrapper">
        <div class="nav-bar">
            <?php if (($above_nav_bar == '1' && isset($above_nav_bar_site) && $above_nav_bar_site != '1') || ($above_nav_bar == '0' && isset($above_nav_bar_site) && $above_nav_bar_site == '2')
                    && (is_active_sidebar('above-navigation-bar'))
                    && (anps_get_option($anps_options_data, 'vertical_menu') == '')
                    && (get_option('anps_menu_type', '2') != '5' && get_option('anps_menu_type', '2') != '6')
                ) : ?>
                <div class="above-nav-bar">
                    <?php do_shortcode(dynamic_sidebar('above-navigation-bar')); ?>
                </div>
            <?php endif;?>
            <nav class="site-navigation<?php echo esc_attr($menu_description); ?>">
              <?php
                  $locations = get_theme_mod('nav_menu_locations');
                  /* Check if menu is selected */
                  $walker = '';
                  $menu = '';
                  $locations = get_theme_mod('nav_menu_locations');

                  if($locations && isset($locations['primary']) && $locations['primary']) {
                      $menu = $locations['primary'];
                      if( (isset($_GET['page']) && $_GET['page'] == 'one-page') ) {
                          $menu = 21;
                      }
                      if(get_option('anps_global_menu_walker', '1')!='') {
                        $walker = new description_walker();
                      }
                  }
                  $check_for_menu = wp_get_nav_menu_items($menu);
                  if(empty($check_for_menu)) {
                      echo '<p class="nav-empty">'.esc_html__('No menu items found.', 'transport').'</p>';
                  } else {
                    wp_nav_menu( array(
                        'container' => false,
                        'menu_class' => '',
                        'echo' => true,
                        'before' => '',
                        'after' => '',
                        'link_before' => '',
                        'link_after' => '',
                        'depth' => 0,
                        'walker' => $walker,
                        'menu'=>$menu
                    ));
                  }
              ?>
            </nav>
            <?php if( anps_get_option('', '1', 'search_icon') != '' || anps_get_option('', '1', 'search_icon_mobile') != '' ):
                $search_class = '';

                if( anps_get_option('', '1', 'anps_search_icon') == '' ) {
                  $search_class = ' hidden-md hidden-lg';
                }

                if( anps_get_option('', '1', 'search_icon_mobile') == '' ) {
                  $search_class = ' hidden-xs hidden-sm';
                }
              ?>
            <button class="fa fa-search site-search-toggle<?php echo esc_attr($search_class); ?>"><span class="sr-only"><?php esc_html_e('Search', 'transport'); ?></span></button>
            <?php endif;
            if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
                $shopping_cart_header = anps_get_option('', 'shop_only', 'shopping_cart_header');
                if (($shopping_cart_header == 'shop_only' &&  is_woocommerce() ) || $shopping_cart_header == 'always' ) {
                    echo "<div class='show-md cartwrap'>";
                    anps_woocommerce_header();
                    echo "</div>";
                }
            } ?>
            <button class="navbar-toggle" type="button">
                <span class="sr-only"><?php _e('Toggle navigation', 'transport'); ?></span>
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    <?php if( get_option('anps_menu_type', '2') == '6' && get_option('anps_menu_button') == 1 && anps_get_option($anps_options_data, 'vertical_menu') != '1') : ?>
            <a href="<?php echo get_option('anps_menu_button_url', '#'); ?>" class="menu-button">
                <?php echo get_option('anps_menu_button_text', 'button'); ?>
            </a>
    <?php endif; ?>
</div>
    <?php
}
function anps_get_site_header() {
    $menu_center = anps_get_option('', '', 'menu_center');
    if( isset($_GET['header']) && $_GET['header'] == 'type-3' ) {
        $menu_center = 'on';
    }
    ?>

    <div class="site-logo"><?php anps_get_logo(); ?></div>
    <?php anps_get_menu(); ?>

<?php }


add_filter("the_content", "anps_the_content_filter");
function anps_the_content_filter($content) {
    // array of custom shortcodes requiring the fix
    $block = join("|",array("recent_blog","section","contact", "form_item", "services", "service", "tabs", "tab", "accordion", "accordion_item", "progress", "quote", "statement", "color", "google_maps", "vimeo", "youtube", "contact_info", "contact_info_item","logos", "logo", "button", "error_404", "icon", "icon_group", "content_half", "content_third", "content_two_third", "content_quarter", "content_two_quarter", "content_three_quarter", "twitter", "social_icons", "social_icon", "data_tables", "data_thead", "data_tbody", "data_tfoot", "data_row", "data_th", "data_td", "testimonials", "testimonial"));
    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

    return $rep;

}

/* Post gallery */

// Add new image sizes
function anps_insert_custom_image_sizes( $image_sizes ) {
  // get the custom image sizes
  global $_wp_additional_image_sizes;
  // if there are none, just return the built-in sizes
  if ( empty( $_wp_additional_image_sizes ) )
    return $image_sizes;

  // add all the custom sizes to the built-in sizes
  foreach ( $_wp_additional_image_sizes as $id => $data ) {
    // take the size ID (e.g., 'my-name'), replace hyphens with spaces,
    // and capitalise the first letter of each word
    if ( !isset($image_sizes[$id]) )
      $image_sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
    }

  return $image_sizes;
}
add_filter( 'image_size_names_choose', 'anps_insert_custom_image_sizes' );



/* Post gallery */
//depreciated, left for reference
//add_filter( 'post_gallery', 'anps_my_post_gallery', 10, 2 );
function anps_my_post_gallery( $output, $attr) {
    global $post, $wp_locale;
    static $instance = 0;
    $instance++;
    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }
    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';
    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }
    if ( empty($attachments) )
        return '';
    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $size = 100/$columns;

    $output = '<div class="gallery recent-posts clearfix">';
    foreach ( $attachments as $id => $attachment ) {
        $image_full = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_image_src($id, 'full', false) : wp_get_attachment_image_src($id, 'full', false);
        $image_full = $image_full[0];

        $image_thumb = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_image_src($id, 'post-thumb', false) : wp_get_attachment_image_src($id, 'post-thumb', false);
        $image_thumb = $image_thumb[0];

        $output .= '
            <article class="post col-md-3" style="width: ' . $size . '%;">
                <header>
                    <a rel="lightbox" class="post-hover" href="' . $image_full . '">
                        <img src="' . $image_thumb . '" alt="blog-8m">
                    </a>
                </header>
            </article>';
    }
    $output .= '</div>';
    return $output;
}
//get post_type
function get_current_post_type() {
    if (is_admin()) {
        global $post, $typenow, $current_screen;
        //we have a post so we can just get the post type from that
        if ($post && $post->post_type)
            return $post->post_type;
        //check the global $typenow - set in admin.php
        elseif ($typenow)
            return $typenow;
        //check the global $current_screen object - set in sceen.php
        elseif ($current_screen && $current_screen->post_type)
            return $current_screen->post_type;
        //lastly check the post_type querystring
        elseif (isset($_REQUEST['post_type']))
            return sanitize_key($_REQUEST['post_type']);
        elseif (isset($_REQUEST['post']))
            return get_post_type($_REQUEST['post']);
        //we do not know the post type!
        return null;
    }
}
/* hide sidebar generator on testimonials and portfolio */
if (get_current_post_type() != 'testimonials' && get_current_post_type() != 'portfolio') {
    //add sidebar generator
    include_once 'sidebar_generator.php';
}
/* Admin/backend styles */
add_action('admin_head', 'backend_styles');
function backend_styles() {
    echo '<style type="text/css">
        .mceListBoxMenu {
            height: auto !important;
        }
        .wp_themeSkin .mceListBoxMenu {
            overflow: visible;
            overflow-x: visible;
        }
    </style>';
}
add_action('admin_head', 'show_hidden_customfields');
function show_hidden_customfields() {
    echo "<input type='hidden' value='" . get_template_directory_uri() . "' id='hidden_url'/>";
}
if (!function_exists('anps_admin_header_style')) :
    /*
     * Styles the header image displayed on the Appearance > Header admin panel.
     * Referenced via add_custom_image_header() in widebox_setup().
     */
    function anps_admin_header_style() {
        ?>
        <style type="text/css">
            /* Shows the same border as on front end */
            #headimg {
                border-bottom: 1px solid #000;
                border-top: 4px solid #000;
            }
        </style>
        <?php
    }
endif;
/* Filter wp title */
//add_filter('wp_title', 'anps_filter_wp_title', 10, 2);
function anps_filter_wp_title($title, $separator) {
    // Don't affect wp_title() calls in feeds.
    if (is_feed())
        return $title;
    // The $paged global variable contains the page number of a listing of posts.
    // The $page global variable contains the page number of a single post that is paged.
    // We'll display whichever one applies, if we're not looking at the first page.
    global $paged, $page;
    if (is_search()) {
        // If we're a search, let's start over:
        $title = sprintf(__('Search results for %s', "transport"), '"' . get_search_query() . '"');
        // Add a page number if we're on page 2 or more:
        if ($paged >= 2)
            $title .= " $separator " . sprintf(__('Page %s', "transport"), $paged);
        // Add the site name to the end:
        $title .= " $separator " . get_bloginfo('name', 'display');
        // We're done. Let's send the new title back to wp_title():
        return $title;
    }
    // Otherwise, let's start by adding the site name to the end:
    $title .= get_bloginfo('name', 'display');
    // If we have a site description and we're on the home/front page, add the description:
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title .= " $separator " . $site_description;

    // Add a page number if necessary:
    if ($paged >= 2 || $page >= 2)
        $title .= " $separator " . sprintf(__('Page %s', "transport"), max($paged, $page));
    // Return the new title to wp_title():
    return $title;
}
/* Page menu show home */
add_filter('wp_page_menu_args', 'anps_page_menu_args');
function anps_page_menu_args($args) {
    $args['show_home'] = true;
    return $args;
}
/* Sets the post excerpt length to 40 characters. */
add_filter('excerpt_length', 'anps_excerpt_length');
function anps_excerpt_length($length) {
    return 40;
}
/* Returns a "Continue Reading" link for excerpts */
function anps_continue_reading_link() {
    return ' <a href="' . get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', "transport") . '</a>';
}
/* Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and widebox_continue_reading_link(). */
add_filter('excerpt_more', 'anps_auto_excerpt_more');
function anps_auto_excerpt_more($more) {
    return ' &hellip;' . anps_continue_reading_link();
}
/* Adds a pretty "Continue Reading" link to custom post excerpts. */
add_filter('get_the_excerpt', 'anps_custom_excerpt_more');
function anps_custom_excerpt_more($output) {
    if (has_excerpt() && !is_attachment()) {
        $output .= anps_continue_reading_link();
    }
    return $output;
}
/* Remove inline styles printed when the gallery shortcode is used. */
add_filter('gallery_style', 'anps_remove_gallery_css');
function anps_remove_gallery_css($css) {
    return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}
/* Prints HTML with meta information for the current post-date/time and author. */
if (!function_exists('widebox_posted_on')) :
    function widebox_posted_on() {
        printf(__('<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', "transport"), 'meta-prep meta-prep-author', sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>', get_permalink(), esc_attr(get_the_time()), get_the_date()
                ), sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', get_author_posts_url(get_the_author_meta('ID')), sprintf(esc_attr__('View all posts by %s', "transport"), get_the_author()), get_the_author()
                )
        );
    }
endif;
/* Prints HTML with meta information for the current post (category, tags and permalink).*/
if (!function_exists('widebox_posted_in')) :
    function widebox_posted_in() {
        // Retrieves tag list of current post, separated by commas.
        $tag_list = get_the_tag_list('', ', ');
        if ($tag_list) {
            $posted_in = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', "transport");
        } elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
            $posted_in = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', "transport");
        } else {
            $posted_in = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', "transport");
        }
        // Prints the string, replacing the placeholders.
        printf($posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0'));
    }
endif;
/* After setup theme */
add_action('after_setup_theme', 'anps_setup');
if (!function_exists('anps_setup')):
    function anps_setup() {
        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();
        // This theme uses post thumbnails
        add_theme_support('post-thumbnails');
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        register_nav_menus(array(
            'primary' => __('Primary Navigation', "transport"),
        ));
    }
endif;
/* theme options init */
add_action('admin_init', 'anps_theme_options_init');
function anps_theme_options_init() {
    register_setting('sample_options', 'sample_theme_options');
}
/* If user is admin, he will see theme options */
add_action('admin_menu', 'anps_theme_options_add_page');
function anps_theme_options_add_page() {
    global $current_user;
    if($current_user->user_level==10) {
        add_theme_page('Theme Options', 'Theme Options', 'read', 'theme_options', 'theme_options_do_page');
    }
}
function theme_options_do_page() {
    include_once "admin_view.php";
}
/* Comments */
function anps_comment($comment, $args, $depth) {
    $email = $comment->comment_author_email;
    $user_id = -1;
    if (email_exists($email)) {
        $user_id = email_exists($email);
    }
    $GLOBALS['comment'] = $comment;
    // time difference
    $today = new DateTime(date("Y-m-d H:i:s"));
    $pastDate = $today->diff(new DateTime(get_comment_date("Y-m-d H:i:s")));
    if($pastDate->y>0) {
        if($pastDate->y=="1") {
            $text = __("year ago", "transport");
        } else {
            $text = __("years ago", "transport");
        }
        $comment_date = $pastDate->y." ".$text;
    } elseif($pastDate->m>0) {
        if($pastDate->m=="1") {
            $text = __("month ago", "transport");
        } else {
            $text = __("months ago", "transport");
        }
        $comment_date = $pastDate->m." ".$text;
    } elseif($pastDate->d>0) {
        if($pastDate->d=="1") {
            $text = __("day ago", "transport");
        } else {
            $text = __("days ago", "transport");
        }
        $comment_date = $pastDate->d." ".$text;
    } elseif($pastDate->h>0) {
        if($pastDate->h=="1") {
            $text = __("hour ago", "transport");
        } else {
            $text = __("hours ago", "transport");
        }
        $comment_date = $pastDate->h." ".$text;
    } elseif($pastDate->i>0) {
        if($pastDate->i=="1") {
            $text = __("minute ago", "transport");
        } else {
            $text = __("minutes ago", "transport");
        }
        $comment_date = $pastDate->i." ".$text;
    } elseif($pastDate->s>0) {
        if($pastDate->s=="1") {
            $text = __("second ago", "transport");
        } else {
            $text = __("seconds ago", "transport");
        }
        $comment_date = $pastDate->s." ".$text;
    }
    ?>
    <li <?php comment_class(); ?>>
        <article id="comment-<?php comment_ID(); ?>">
            <div class="comment-avatar">
                <?php echo get_avatar( $comment, 86 ); ?>
            </div>

            <header>
                <h1><?php comment_author(); ?></h1>
                <span class="date"><?php echo esc_html($comment_date);?></span>
                <?php echo comment_reply_link(array_merge(array('reply_text' => 'Reply'), array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            </header>

            <div class="comment-content"><?php comment_text(); ?></div>
        </article>
    </li>
<?php }
add_filter('comment_reply_link', 'replace_reply_link_class');
function replace_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='comment-reply-link btn", $class);
    return $class;
}
/* Remove Excerpt text */
function sbt_auto_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'sbt_auto_excerpt_more', 20 );
function sbt_custom_excerpt_more( $output ) {
    return preg_replace('/<a[^>]+>Continue reading.*?<\/a>/i','',$output);
}
add_filter( 'get_the_excerpt', 'sbt_custom_excerpt_more', 20 );
function get_excerpt(){
    $excerpt = get_the_content();
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, 100);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    if( $excerpt != "" ) {
        $excerpt = $excerpt.'...';
    }
    return $excerpt;
}
add_filter('widget_tag_cloud_args','set_cloud_tag_size');
function set_cloud_tag_size($args) {
    $args['smallest'] = 12;
    $args['largest'] = 12;
    return $args;
}

function anps_boxed_or_vertical() {
    global $anps_options_data;
    $anps_classes = "";
    if (anps_get_option($anps_options_data, 'boxed') == '1') {
        $anps_classes .= ' boxed';
        if ( get_option('anps_custom_pattern')!= '0' ) {
            $anps_classes .= ' pattern-' . get_option('anps_pattern');
        }
    }
    if (anps_get_option($anps_options_data, 'vertical_menu') == '1') {
        $anps_classes .= ' vertical-menu';
    }
    $anps_classes .= ' responsive'; // for backward css compatibility
    return $anps_classes;
}

/* Custom font extenstion */

function anps_getExtCustomFonts($font) {
    $dir = get_template_directory().'/fonts';
        if ($handle = opendir($dir)) {
            $arr = array();
            // Get all files and store it to array
            while(false !== ($entry = readdir($handle))) {
                $explode_font=explode('.',$entry);
                if(strtolower($font)==strtolower($explode_font[0]))
                    $arr[] = $entry;
            }
            closedir($handle);
            // Remove . and ..
            unset($arr['.'], $arr['..']);
            return $arr;
        }
}



/* Check for header/footer margin */
if(!function_exists('anps_header_margin')) {
    function anps_header_margin() {
        $class = '';
        $header_margin = get_post_meta(get_queried_object_id(), $key ='anps_header_options_header_margin', $single = true);
        $footer_margin = get_post_meta(get_queried_object_id(), $key ='anps_header_options_footer_margin', $single = true);
        if(isset($header_margin) && $header_margin=='on') {
            $class .= ' header-spacing-off';
        }
        if(isset($footer_margin) && $footer_margin=='on') {
            $class .= ' footer-spacing-off';
        }
        return $class;
    }
}

/* Load custom font (CSS) */

function anps_custom_font($font) {
    $font_family = esc_attr($font);
    $font_src    = get_template_directory_uri() . '/fonts/' . $font_family . '.eot';
    $font_count  = count( anps_getExtCustomFonts($font) );
    $i           = 0;
    $prefix      = 'url("' . get_template_directory_uri() . '/fonts/';
    $font_srcs   = '';

    foreach(anps_getExtCustomFonts($font) as $item) {
        $explode_item = explode('.', $item);

        $name = $explode_item[0];
        $extension = $explode_item[1];
        $separator = ',';

        if( ++$i == $font_count ) {
            $separator = ';';
        }

        switch( $extension ) {
            case 'eot': $font_srcs .= $prefix . $name . '.eot?#iefix") format("embedded-opentype")' . $separator; break;
            case 'woff': $font_srcs .= $prefix . $name . '.woff") format("woff")' . $separator;  break;
            case 'otf': $font_srcs .= $prefix . $name . '.otf") format("opentype")' . $separator;  break;
            case 'ttf': $font_srcs .= $prefix . $name . '.ttf") format("ttf")' . $separator;  break;
            case 'woff2': $font_srcs .= $prefix . $name . '.woff2") format("woff2")' . $separator;  break;
        }
    } /* end foreach */
    ?>
    @font-face {
        font-family: "<?php echo strip_tags($font_family); ?>";
        src: url("<?php echo strip_tags($font_src); ?>");
        src: <?php echo strip_tags($font_srcs); ?>
    }
    <?php
}

/* Custom styles */

function anps_custom_styles() {
    global $anps_media_data, $anps_page_data;
    /* Font Default Values */

    $font_1 = "Montserrat";
    $font_2 = 'PT Sans';
    $font_3 = "Montserrat";

    /* Font 1 */

    if( get_option('font_source_1') == 'System fonts' ||
        get_option('font_source_1') == 'Custom fonts' ||
        get_option('font_source_1') == 'Google fonts' ) {

        $font_1 = urldecode(get_option('font_type_1'));
    }

    if( get_option('font_source_1') == 'Custom fonts' ) {
        anps_custom_font($font_1);
    }

    /* Font 2 */

    if( get_option('font_source_2') == 'System fonts' ||
        get_option('font_source_2') == 'Custom fonts' ||
        get_option('font_source_2') == 'Google fonts' ) {

        $font_2 = urldecode(get_option('font_type_2'));
    }

    if( get_option('font_source_2') == 'Custom fonts' ) {
        anps_custom_font($font_2);
    }

    /* Font 3 (navigation) */

    if( get_option('font_source_navigation') == 'System fonts' ||
        get_option('font_source_navigation') == 'Custom fonts' ||
        get_option('font_source_navigation') == 'Google fonts' ) {

        $font_3 = urldecode(get_option('font_type_navigation'));
    }

    if( get_option('font_source_navigation') == 'Custom fonts' ) {
        anps_custom_font($font_3);
    }

    /* Logo font */
    $logo_font = urldecode(get_option('anps_text_logo_font'));

    if( get_option('anps_text_logo_source_1') == 'Custom fonts' ) {
        anps_custom_font($logo_font);
    }

    /* Main theme colors */
    $text_color = anps_get_option('', '#727272', 'text_color');
    $primary_color = anps_get_option('', '#292929', 'primary_color');
    $hovers_color = anps_get_option('', '#1874c1', 'hovers_color');
    $menu_text_color = anps_get_option('', '#000000', 'menu_text_color');
    $headings_color = anps_get_option('', '#000000', 'headings_color');
    $footer_bg_color = anps_get_option('', '#242424', 'footer_bg_color');
    $top_bar_color = anps_get_option('', '#c1c1c1', 'top_bar_color');
    $top_bar_bg_color = anps_get_option('', '#f9f9f9', 'top_bar_bg_color');
    $copyright_footer_text_color = get_option('anps_copyright_footer_text_color', '#c4c4c4');
    $copyright_footer_bg_color = anps_get_option('', '#0f0f0f', 'copyright_footer_bg_color');
    $nav_background_color = anps_get_option('', '#fff', 'nav_background_color');
    $footer_text_color = anps_get_option('', '#d9d9d9', 'footer_text_color');
    $footer_selected_color = get_option('anps_footer_selected_color', '');
    $footer_hover_color = get_option('anps_footer_hover_color', '');
    $footer_divider_color = get_option('anps_footer_divider_color', '#fff');
    $submenu_background_color = anps_get_option('', '#fff', 'submenu_background_color');
    $submenu_text_color = anps_get_option('', '#000', 'submenu_text_color');
    $side_submenu_background_color = anps_get_option('', '#fff', 'side_submenu_background_color');
    $side_submenu_text_color = anps_get_option('', '#000', 'side_submenu_text_color');
    $side_submenu_text_hover_color = anps_get_option('', '#1874c1', 'side_submenu_text_hover_color');
    $anps_logo_bg_color = get_option('anps_logo_bg_color', '');
    $anps_above_menu_bg_color = get_option('anps_above_menu_bg_color', 'fff');
    $anps_woo_cart_items_number_bg_color = get_option('anps_woo_cart_items_number_bg_color', $primary_color);
    $anps_woo_cart_items_number_color = get_option('anps_woo_cart_items_number_color', '#fff');
    $anps_heading_text_color = get_option('anps_heading_text_color', '#fff');
    $curent_menu_color = get_option('anps_curent_menu_color', '#153d5c');
    $page_heading_color = get_option('anps_page_heading_text_color', $headings_color);

    /* Page heading meta color */
    if( is_404() ) {
        $page_heading_single_color = get_post_meta(anps_get_option($anps_page_data, 'error_page'), $key ='anps_heading_front_color', $single = true );
    } else {
        $page_heading_single_color = get_post_meta(get_queried_object_id(), $key ='anps_heading_front_color', $single = true );
    }

    if( $page_heading_single_color != '' ) {
        $page_heading_color = $page_heading_single_color;
    }

    $main_divider_color = get_option('anps_main_divider_color', '');
    /*home-page colors*/
    $anps_front_text_color = get_option('anps_front_text_color');
    if( $anps_front_text_color == '' ) {
        $anps_front_text_color = $menu_text_color;
    }

    $anps_front_curent_menu_color = get_option('anps_front_curent_menu_color');
    if( $anps_front_curent_menu_color == '' ) {
        $anps_front_curent_menu_color = $curent_menu_color;
    }

    $anps_front_text_hover_color = get_option('anps_front_text_hover_color');
    if( $anps_front_text_hover_color == '' ) {
        $anps_front_text_hover_color = $hovers_color;
    }

    /* Only if it is full screen menu type (1) */
    if (is_front_page() && get_option('anps_menu_type', '2')=='1' && get_option('anps_front_topbar_bg_color', '') != '') {
        $top_bar_bg_color = get_option('anps_front_topbar_bg_color', '');
    }

    if (is_front_page() && get_option('anps_menu_type', '2')=='1' && get_option('anps_front_topbar_color', '') != '') {
        $top_bar_color = get_option('anps_front_topbar_color', '');
    }

    $anps_front_bg_color = get_option('anps_front_bg_color');
    $anps_front_topbar_color = get_option('anps_front_topbar_color', '#fff');
    $anps_front_topbar_bg_color = get_option('anps_front_topbar_bg_color', '');
    $anps_front_topbar_hover_color = get_option('anps_front_topbar_hover_color', '#1874c1');

    /*font-size*/
    $body_font_size = anps_get_option('', '14', 'body_font_size');
    $menu_font_size = anps_get_option('', '14', 'menu_font_size');
    $h1_font_size = anps_get_option('', '31', 'h1_font_size');
    $h2_font_size = anps_get_option('', '15', 'h2_font_size');
    $h3_font_size = anps_get_option('', '21', 'h3_font_size');
    $h4_font_size = anps_get_option('', '18', 'h4_font_size');
    $h5_font_size = anps_get_option('', '16', 'h5_font_size');
    $page_heading_h1_font_size = anps_get_option('', '24', 'page_heading_h1_font_size');
    $blog_heading_h1_font_size = anps_get_option('', '28', 'blog_heading_h1_font_size');

    /* Container width */
    $container_width = get_option('anps_container_width', '1170');
?>

::selection, .timeline-item:before
 { background-color: <?php echo esc_attr($primary_color); ?>; color: #fff; }

body,
ol.list > li > *,
.product_meta span span {
  color: <?php echo esc_attr($text_color); ?>;
}
@media (min-width: <?php echo esc_attr($container_width) + 30; ?>px) {
    .container {
        width: <?php echo esc_attr($container_width); ?>px;
    }
}
.testimonials .testimonial-owl-nav button:hover {
    background: <?php echo esc_attr($text_color); ?>;
}
/* Header colors */

@media(min-width: 992px) {
    .site-header-style-boxed,
    .site-header-style-full-width {
        background-color: <?php echo esc_attr($anps_above_menu_bg_color); ?>;
    }
    .woo-header-cart .cart-contents > i,
    .nav-wrap .site-search-toggle,
    .nav-bar .site-search-toggle {
        color: <?php echo esc_attr($menu_text_color); ?>;
    }

    .site-navigation a,
    .home .site-header-sticky-active .site-navigation .menu-item-depth-0 > a,
    .paralax-header .site-header-style-transparent.site-header-sticky-active .site-navigation .menu-item-depth-0 > a:not(:hover):not(:focus),
    .nav-empty {
      color: <?php echo esc_attr($menu_text_color); ?>;
    }
}

.site-header-style-normal .nav-wrap {
  background-color: <?php echo esc_attr($nav_background_color); ?>;
}

@media(min-width: 992px) {
  .site-navigation .sub-menu {
    background-color: <?php echo esc_attr($submenu_background_color); ?>;
  }

  .site-navigation .sub-menu a {
    color: <?php echo esc_attr($submenu_text_color); ?>;
  }
}

.heading-left.divider-sm span:before,
.heading-middle.divider-sm span:before,
.heading-left.divider-lg span:before,
.heading-middle.divider-lg span:before {
  background-color: <?php echo esc_attr($main_divider_color); ?>;
}

.site-navigation a:hover, .site-navigation a:focus,
.testimonials.style-4 ul.testimonial-wrap .jobtitle,
.testimonials.style-3 + .owl-navigation .owlnext:hover, .testimonials.style-3 + .owl-navigation .owlnext:focus,
.testimonials.style-3 + .owl-navigation .owlprev:hover, .testimonials.style-3 + .owl-navigation .owlprev:focus,
.testimonials.style-3 .testimonial-user{
  color: <?php echo esc_attr($hovers_color); ?>;
}

.site-navigation .current-menu-item > a, .home .site-navigation .current-menu-item > a,
.home .site-header.site-header-sticky-active .menu-item-depth-0.current-menu-item > a {
   color: <?php echo esc_attr($curent_menu_color); ?>;
}

@media(min-width: 992px) {
    .site-search-toggle:hover, .site-search-toggle:focus, .site-navigation ul:not(.sub-menu) > li > a:hover,
    .site-navigation ul:not(.sub-menu) > li > a:focus{
        color: <?php echo esc_attr($hovers_color); ?>;
    }

  /* Boxed header style background color */
  .site-header-style-boxed .nav-bar-wrapper {
    background-color: <?php echo esc_attr($anps_front_bg_color); ?>;
  }
}

@media(max-width: 991px) {
  .site-search-toggle:hover, .site-search-toggle:focus,
  .navbar-toggle:hover, .navbar-toggle:focus {
    background-color: <?php echo esc_attr($hovers_color); ?>;
  }

  .site-search-toggle,
  .navbar-toggle {
    background-color: <?php echo esc_attr($primary_color); ?>;
  }
}

<?php if( get_option('anps_menu_type', '2') == 1 || get_option('anps_menu_type', '2') == 3 ): ?>
/* Front Colors (transparent menus) */

@media(min-width: 992px) {
  .home .site-navigation .menu-item-depth-0 > a, .home header:not(.site-header-sticky-active) .site-search-toggle:not(:hover):not(:focus),
  .nav-empty {
    color: <?php echo esc_attr($anps_front_text_color); ?>;
  }
}

.home .site-header .menu-item-depth-0 > a:hover,
.home .site-header .menu-item-depth-0 > a:focus {
  color: <?php echo esc_attr($anps_front_text_hover_color); ?>;
}
<?php else: ?>
/* Front-Global Colors */

.site-header-style-normal .nav-wrap {
  background-color: <?php echo esc_attr($anps_front_bg_color); ?>;
}

@media(min-width: 992px) {
  .site-header-style-full-width.site-header-sticky-active .header-wrap,
  .site-header-style-full-width .header-wrap {
    background-color: <?php echo esc_attr($anps_front_bg_color); ?>;
  }

  .home .site-navigation ul:not(.sub-menu) > li > a,
  .home .nav-empty,
  .home header:not(.site-header-sticky-active) .woo-header-cart .cart-contents > i,
  .home header:not(.site-header-sticky-active) .site-search-toggle
   {
      color: <?php echo esc_attr($anps_front_text_color); ?>;
    }
}

.site-navigation a:hover,
.site-navigation a:focus,
.site-navigation .current-menu-item > a,
.home .site-navigation ul:not(.sub-menu) > li > a:hover,
.home .site-navigation ul:not(.sub-menu) > li > a:focus,
.home header:not(.site-header-sticky-active) .site-search-toggle:hover {
  color: <?php echo esc_attr($anps_front_text_hover_color); ?>;
}
<?php endif; ?>

@media(min-width: 992px) {
    .home .site-header .menu-item-depth-0.current-menu-item > a {
        color: <?php echo esc_attr($anps_front_curent_menu_color); ?>;
    }

    .home .site-search-toggle:focus,
    .home .site-search-toggle:hover {
        color: <?php echo esc_attr($anps_front_text_hover_color); ?>;
    }
}

/* Top bar colors */

.top-bar {
  background-color: <?php echo $top_bar_bg_color; ?>;
  color: <?php echo esc_attr($top_bar_color); ?>;
}
.top-bar a:not(:hover) {
    color: <?php echo esc_attr($top_bar_color); ?>;
}

<?php if( is_front_page() && $anps_front_topbar_hover_color != '' ): ?>
  .top-bar a:hover,
  .top-bar a:focus {
    color: <?php echo esc_attr($anps_front_topbar_hover_color); ?> !important;
  }
<?php endif; ?>

<?php //top bar font size ?>
.top-bar, .top-bar a {
    font-size: <?php echo esc_attr(get_option('anps_top_bar_font_size', '14'));?>px;
}

a,
.btn-link,
.error-404 h2,
.page-heading,
.statement .style-3,
.dropcaps.style-2:first-letter,
.list li:before,
ol.list,
.post.style-2 header > span,
.post.style-2 header .fa,
.page-numbers span,
.team .socialize a,
blockquote.style-2:before,
.panel-group.style-2 .panel-title a:before,
.contact-info i,
blockquote.style-1:before,
.comment-list .comment header h1,
.faq .panel-title a.collapsed:before,
.faq .panel-title a:after,
.faq .panel-title a,
.filter-style-1 button.selected,
.primary,
.search-posts i,
.counter .counter-number,
#wp-calendar th,
#wp-calendar caption,
.testimonials blockquote p:before,
.testimonials blockquote p:after,
.price,
.widget-price,
.star-rating,
.sidebar .widget_shopping_cart .quantity,
.tab-pane .commentlist .meta strong, .woocommerce-tabs .commentlist .meta strong,
.widget_recent_comments .recentcomments a:not(:hover),
.timeline-year,
.featured-has-icon:hover .featured-title i,
.featured-has-icon:focus .featured-title i,
.featured-has-icon.simple-style .featured-title i,
a.featured-lightbox-link,
.large-above-menu-style-2 .important {
  color: <?php echo esc_attr($primary_color); ?>;
}

.heading-middle span:before,
.heading-left span:before,
.featured-has-icon .featured-title:before,
.large-above-menu-style-2 .widget_anpssocial a:hover,
.large-above-menu-style-2 .widget_anpssocial a:focus, 
.woocommerce-product-gallery__trigger {
  background-color: <?php echo esc_attr($primary_color); ?>;
}

.testimonials.white blockquote p:before,
.testimonials.white blockquote p:after {
  color: #fff;
}

.site-footer, .site-footer h3, .site-footer h4 {
  color: <?php echo esc_attr($footer_text_color); ?>;
}

.site-footer .row .menu .current_page_item > a {
    color: <?php echo esc_attr($footer_selected_color); ?>;
}

.site-footer .row a:hover,
.site-footer .row a:focus {
    color: <?php echo esc_attr($footer_hover_color); ?>;
}

.site-footer .row .widget-title {
    color: <?php echo esc_attr($anps_heading_text_color);?>
}

.counter .wrapbox {
    border-color:<?php echo esc_attr($primary_color); ?>;
}


.nav .open > a:focus,
body .tp-bullets.simplebullets.round .bullet.selected,
.featured-content  {
  border-color: <?php echo esc_attr($primary_color); ?>;
}

.icon i,
.posts div a,
.progress-bar,
.nav-tabs > li.active:after,
.vc_tta-style-anps_tabs .vc_tta-tabs-list > li.vc_active:after,
.pricing-table header,
.table thead th,
.mark,
.post .post-meta button,
blockquote.style-2:after,
.panel-title a:before,
.carousel-indicators li,
.carousel-indicators .active,
.ls-michell .ls-bottom-slidebuttons a,
.site-search,
.twitter .carousel-indicators li,
.twitter .carousel-indicators li.active,
#wp-calendar td a,
body .tp-bullets.simplebullets.round .bullet,
.onsale,
.plus, .minus,
.form-submit #submit,
.testimonials blockquote header:before,
div.woocommerce-tabs ul.tabs li.active:before,
mark {
  background-color: <?php echo esc_attr($primary_color); ?>;
}

.important {
    color: <?php echo esc_attr($hovers_color); ?>;
}

.woo-header-cart .cart-contents > span {
    background-color: <?php echo esc_attr($anps_woo_cart_items_number_bg_color);?>;
}

.woo-header-cart .cart-contents > span {
    color: <?php echo esc_attr($anps_woo_cart_items_number_color);?>;
}

.testimonials.white blockquote header:before {
    background-color: #fff;
}

h1, h2, h3, h4, h5, h6,
.nav-tabs > li > a,
.nav-tabs > li.active > a,
.vc_tta-tabs-list > li > a span,
.statement,
p strong,
.dropcaps:first-letter,
.page-numbers a,
.searchform,
.searchform input[type="text"],
.socialize a,
.widget_rss .rss-date,
.widget_rss cite,
.panel-title,
.panel-group.style-2 .panel-title a.collapsed:before,
blockquote.style-1,
.comment-list .comment header,
.faq .panel-title a:before,
.faq .panel-title a.collapsed,
.filter button,
.carousel .carousel-control,
#wp-calendar #today,
.woocommerce-result-count,
input.qty,
.product_meta,
.woocommerce-review-link,
.woocommerce-before-loop .woocommerce-ordering:after,
.widget_price_filter .price_slider_amount .button,
.widget_price_filter .price_label,
.sidebar .product_list_widget li h4 a,
.shop_table.table thead th,
.shop_table.table tfoot,
.headings-color,
.product-single-header .variations label,
.tab-pane .commentlist .meta, .woocommerce-tabs .commentlist .meta {
  color: <?php echo esc_attr($headings_color); ?>;
}

.page-heading .breadcrumbs li a:after,
.page-heading ul.breadcrumbs a:not(:hover):not(:focus),
.page-heading h1,
.page-header h1 {
    color: <?php echo esc_attr($page_heading_color); ?>;
}

.ls-michell .ls-nav-next,
.ls-michell .ls-nav-prev
{
color:#fff;
}

input:not([type="radio"]):not([type="checkbox"]):focus,
textarea:focus {
  border-color: <?php echo esc_attr($headings_color); ?>!important;
  outline: none;
}

.select2-container-active.select2-drop-active,
.select2-container-active.select2-container .select2-choice,
.select2-drop-active .select2-results,
.select2-drop-active {
  border-color: <?php echo esc_attr($headings_color); ?> !important;
}


.pricing-table header h2,
.mark.style-2,
.btn.dark,
.twitter .carousel-indicators li,
.added_to_cart
{
  background-color: <?php echo esc_attr($headings_color); ?>;
}


body,
.alert .close,
.post header {
   font-family: <?php echo anps_wrap_font(esc_attr($font_2)); ?>;
   <?php if($font_2 === 'Montserrat'): ?>
       font-weight: 500;
   <?php endif; ?>
}

h1, h2, h3, h4, h5, h6,
.btn,
.page-heading,
.team em,
blockquote.style-1,
.onsale,
.added_to_cart,
.price,
.widget-price,
.woocommerce-review-link,
.product_meta,
.tab-pane .commentlist .meta, .woocommerce-tabs .commentlist .meta,
.wpcf7-submit,
.testimonial-footer span.user,
button.single_add_to_cart_button,
p.form-row input.button,
.contact-number,
.filter-style-3,
.menu-button,
.shipping-calculator-button {
  font-family: <?php echo anps_wrap_font(esc_attr($font_1)); ?>;
  <?php if($font_1 === 'Montserrat'): ?>
      font-weight: 500;
  <?php endif; ?>
}

.nav-tabs > li > a,
.site-navigation,
.vc_tta-tabs-list > li > a,
.tp-arr-titleholder {
    font-family: <?php echo anps_wrap_font(esc_attr($font_3)); ?>;
    <?php if($font_3 === 'Montserrat'): ?>
        font-weight: 500;
    <?php endif; ?>
}

.pricing-table header h2,
.pricing-table header .price,
.pricing-table header .currency,
.table thead,
h1.style-3,
h2.style-3,
h3.style-3,
h4.style-3,
h5.style-3,
h6.style-3,
.page-numbers a,
.page-numbers span,
.nav-links a,
.nav-links span,
.alert,
.comment-list .comment header,
.woocommerce-result-count,
.product_list_widget li > a,
.product_list_widget li p.total strong,
.cart_list + .total,
.shop_table.table tfoot,
.product-single-header .variations label {
    font-family: <?php echo anps_wrap_font(esc_attr($font_1)); ?>;
    <?php if($font_1 === 'Montserrat'): ?>
        font-weight: 500;
    <?php endif; ?>
}

.site-search #searchform-header input[type="text"] {
     font-family: <?php echo anps_wrap_font(esc_attr($font_1)); ?>;
     <?php if($font_1 === 'Montserrat'): ?>
         font-weight: 500;
     <?php endif; ?>
}

/* footer */

.site-footer {
  background: <?php echo esc_attr($footer_bg_color); ?>;
}
.site-footer .copyright-footer {
  color: <?php echo esc_attr($copyright_footer_text_color); ?>;
  background: <?php echo esc_attr($copyright_footer_bg_color); ?>;
}

footer.site-footer .copyright-footer > .container:before {
    background: <?php echo esc_attr($footer_divider_color); ?>;
}

div.testimonials.white blockquote.item.active p,
div.testimonials.white blockquote.item.active cite a,
div.testimonials.white blockquote.item.active cite, .wpb_content_element .widget .tagcloud a {
    color: #fff;
}

a:hover,
.icon a:hover h2,
.nav-tabs > li > a:hover,
.page-heading a:hover,
.menu a:hover, .menu a:focus,
.menu .is-active a,
.table tbody tr:hover td,
.page-numbers a:hover,
.nav-links a:hover,
.widget-categories a:hover,
.product-categories a:hover,
.widget_archive a:hover,
.widget_categories a:hover,
.widget_recent_entries a:hover,
.socialize a:hover,
.faq .panel-title a.collapsed:hover,
.carousel .carousel-control:hover,
a:hover h1, a:hover h2, a:hover h3, a:hover h4, a:hover h5,
.site-footer a:hover,
.ls-michell .ls-nav-next:hover,
.ls-michell .ls-nav-prev:hover,
body .tp-leftarrow.default:hover,
body .tp-rightarrow.default:hover,
.product_list_widget li h4 a:hover,
.cart-contents:hover i,
.icon.style-2 a:hover i,
.team .socialize a:hover,
.recentblog header a:hover h2,
.scrollup a:hover,
.hovercolor, i.hovercolor, .post.style-2 header i.hovercolor.fa,
article.post-sticky header:before,
.wpb_content_element .widget a:hover,
.star-rating,
.menu .current_page_item > a,
.page-numbers.current,
.widget_layered_nav a:hover,
.widget_layered_nav a:focus,
.widget_layered_nav .chosen a,
.widget_layered_nav_filters a:hover,
.widget_layered_nav_filters a:focus,
.widget_rating_filter .star-rating:hover,
.widget_rating_filter .star-rating:focus,
.icon > a > i, .icon.style-2 i,
.home .site-header.site-header-sticky-active .menu-item-depth-0 > a:hover,
.home .site-header.site-header-sticky-active .menu-item-depth-0 > a:focus,
#shipping_method input:checked + label::after,
.payment_methods input[type="radio"]:checked + label::after {
    color: <?php echo esc_attr($hovers_color); ?>;
}

.filter-style-1 button.selected,
.filter-style-3 button.selected {
  color: <?php echo esc_attr($hovers_color); ?>!important;
}

.scrollup a:hover {
    border-color: <?php echo esc_attr($hovers_color); ?>;
}

.tagcloud a:hover,
.twitter .carousel-indicators li:hover,
.added_to_cart:hover,
.icon a:hover i,
.posts div a:hover,
#wp-calendar td a:hover,
.plus:hover, .minus:hover,
.widget_price_filter .price_slider_amount .button:hover,
.form-submit #submit:hover,
.onsale,
.widget_price_filter .ui-slider .ui-slider-range,
.newsletter-widget .newsletter-submit,
.tnp-widget .tnp-submit,
.testimonials .testimonial-owl-nav button,
.woocommerce-product-gallery__trigger:hover,
.woocommerce-product-gallery__trigger:focus,
.large-above-menu-style-2 .widget_anpssocial a {
    background-color: <?php echo esc_attr($hovers_color); ?>;
}

body {
    font-size: <?php echo esc_attr($body_font_size); ?>px;
}

/* Heading font sizes */
h1, .h1 {
    font-size: <?php echo esc_attr($h1_font_size); ?>px;
}
h2, .h2 {
    font-size: <?php echo esc_attr($h2_font_size); ?>px;
}
h3, .h3 {
    font-size: <?php echo esc_attr($h3_font_size); ?>px;
}
h4, .h4 {
    font-size: <?php echo esc_attr($h4_font_size); ?>px;
}
h5, .h5 {
    font-size: <?php echo esc_attr($h5_font_size); ?>px;
}
.page-heading h1 {
    font-size: <?php echo esc_attr($page_heading_h1_font_size); ?>px;
    line-height: 34px;
}

article.post-sticky header .stickymark i.nav_background_color {
    color: <?php echo esc_attr($nav_background_color); ?>;
}

.triangle-topleft.hovercolor {
    border-top: 60px solid <?php echo esc_attr($hovers_color); ?>;
}

h1.single-blog, article.post h1.single-blog{
    font-size: <?php echo esc_attr($blog_heading_h1_font_size); ?>px;
}

aside.sidebar ul.menu ul.sub-menu > li > a {
    background: <?php echo esc_attr($side_submenu_background_color); ?>;
    color: <?php echo esc_attr($side_submenu_text_color); ?>;
}

aside.sidebar ul.menu ul.sub-menu > li > a:hover,
aside.sidebar ul.sub-menu li.current_page_item > a,
aside.sidebar ul.menu ul.sub-menu > li.current_page_item > a {
    color: <?php echo esc_attr($side_submenu_text_hover_color); ?>;
}


/* logo bg color */
<?php if (isset($anps_logo_bg_color) && $anps_logo_bg_color != "" && get_option('anps_logo_background') == '1') :?>
    @media(min-width: 992px) {
        .site-header .site-logo {
            color: <?php echo esc_attr($anps_logo_bg_color);?>
        }
    }
<?php endif;?>

<?php
// set top bar size
?>

<?php // set main-menu height
$anps_menu_height = get_option('anps_main_menu_height', '');
$anps_top_bar_height = get_option('anps_top_bar_height', '60');
if ($anps_top_bar_height == '') {
   $anps_top_bar_height = 60;
}
?>
@media(min-width: 992px) {
    .top-bar > .container {
        min-height: <?php echo esc_attr($anps_top_bar_height);?>px;
    }
}
<?php
if($anps_menu_height != '') : ?>
    @media(min-width: 992px) {
        <?php // header type 1  ?>
        .site-header-style-normal,
        .transparent.top-bar + .site-header-style-transparent:not(.site-header-sticky-active) .nav-wrap {
            height: <?php echo esc_attr($anps_menu_height, 'auto');?>px;
            max-height: <?php echo esc_attr($anps_menu_height, 'auto');?>px;
        }
        <?php // header type 2, 3, 4  ?>
        .site-header-style-normal:not(.site-header-sticky-active) .nav-wrap,
        .site-header-style-transparent:not(.site-header-sticky-active) .nav-wrap  {
            height: <?php echo esc_attr($anps_menu_height, 'auto');?>px;
            max-height: <?php echo esc_attr($anps_menu_height, 'auto');?>px;
            transition: height .3s ease-out;
        }

        <?php // header type 6  ?>
        .site-header-style-full-width .preheader-wrap, .site-header-style-boxed .preheader-wrap {
            height: <?php echo esc_attr($anps_menu_height, 'auto');?>px;
        }
        .site-header-style-full-width .site-logo:after, .site-header-style-boxed .site-logo:after {
            border-top: <?php echo esc_attr($anps_menu_height, 'auto');?>px solid currentColor;
        }
    }
<?php endif;

//set header dimensions
if(anps_get_option($anps_media_data, 'logo-height')!=''):

    if (anps_get_option($anps_media_data, 'auto_adjust_logo')!='' ) {
        $nav_top_heading = '0';
    } else {
        $nav_top_heading = ceil(((anps_get_option($anps_media_data, 'auto_adjust_logo')) - '72' + '23') / '2');
    }
?>
<?php endif;?>

<?php
//display search icon in menu?
$search_icon = anps_get_option('', '1', 'search_icon');
if (!$search_icon == '1') : ?>
.site-navigation .fa-search {
    display:none;
}
<?php endif; ?>

<?php if( $logo_font ): ?>
.site-logo {
    font-family: <?php echo anps_wrap_font(esc_attr($logo_font)); ?>;
}
<?php endif; ?>

<?php
echo get_option("anps_custom_css", "");
}

/* Custom styles for buttons */

function anps_custom_styles_buttons() {
   /*buttons*/
    $default_button_bg = anps_get_option('', '#1874c1', 'default_button_bg');
    $default_button_color = anps_get_option('', '#fff', 'default_button_color');
    $default_button_hover_bg = anps_get_option('', '#292929', 'default_button_hover_bg');
    $default_button_hover_color = anps_get_option('', '#fff', 'default_button_hover_color');

    $style_1_button_bg = anps_get_option('', '#1874c1', 'style_1_button_bg');
    $style_1_button_color = anps_get_option('', '#fff', 'style_1_button_color');
    $style_1_button_hover_bg = anps_get_option('', '#292929', 'style_1_button_hover_bg');
    $style_1_button_hover_color = get_option('', '#fff', 'style_1_button_hover_color');


    $style_slider_button_bg = anps_get_option('', '#1874c1', 'style_slider_button_bg');
    $style_slider_button_color = anps_get_option('', '#fff', 'style_slider_button_color');
    $style_slider_button_hover_bg = anps_get_option('', '#000', 'style_slider_button_hover_bg');
    $style_slider_button_hover_color = anps_get_option('', '#fff', 'style_slider_button_hover_color');

    $style_2_button_bg = anps_get_option('', '#292929', 'style_2_button_bg');
    $style_2_button_color = anps_get_option('', '#fff', 'style_2_button_color');
    $style_2_button_hover_bg = anps_get_option('', '#fff', 'style_2_button_hover_bg');
    $style_2_button_hover_color = anps_get_option('', '#fff', 'style_2_button_hover_color');

    $style_3_button_color = anps_get_option('', '#fff', 'style_3_button_color');
    $style_3_button_hover_bg = anps_get_option('', '#fff', 'style_3_button_hover_bg');
    $style_3_button_hover_color = anps_get_option('', '#1874c1', 'style_3_button_hover_color');
    $style_3_button_border_color = anps_get_option('', '#fff', 'style_3_button_border_color');

    $style_4_button_color = anps_get_option('', '#1874c1', 'style_4_button_color');
    $style_4_button_hover_color = anps_get_option('', '#94cfff', 'style_4_button_hover_color');

    $style_style_5_button_bg = anps_get_option('', '#c3c3c3', 'style_style_5_button_bg');
    $style_style_5_button_color = anps_get_option('', '#fff', 'style_style_5_button_color');
    $style_style_5_button_hover_bg = anps_get_option('', '#737373', 'style_style_5_button_hover_bg');
    $style_style_5_button_hover_color = anps_get_option('', '#fff', 'style_style_5_button_hover_color');

    $anps_header_style_6_button_bg = get_option('anps_header_style_6_button_bg', '#1874c1');
    $anps_header_style_6_button_color = get_option('anps_header_style_6_button_color', '#fff');
    $anps_header_style_6_button_hover_bg = get_option('anps_header_style_6_button_hover_bg', '#000');
    $anps_header_style_6_button_hover_color = get_option('anps_header_style_6_button_hover_color', '#fff');

 ?>

/*buttons*/

input#place_order {
     background-color: <?php echo esc_attr($default_button_bg); ?>;
}

input#place_order:hover,
input#place_order:focus {
     background-color: <?php echo esc_attr($default_button_hover_bg); ?>;
}

.btn, .wpcf7-submit, .woocommerce .button, .post-password-form input[type="submit"] {
    -moz-user-select: none;
    background-image: none;
    border: 0;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    line-height: 1.5;
    margin-bottom: 0;
    text-align: center;
    text-transform: uppercase;
    text-decoration:none;
    transition: background-color 0.2s ease 0s;
    vertical-align: middle;
    white-space: nowrap;
}

.btn.btn-sm, .wpcf7-submit {
    padding: 11px 17px;
    font-size: 14px;
}

.btn, .wpcf7-submit, button.single_add_to_cart_button,
p.form-row input.button, .woocommerce .button,
.post-password-form input[type="submit"] {
  border-radius: 0;
  border-radius: 4px;
  background-color: <?php echo esc_attr($default_button_bg); ?>;
  color: <?php echo esc_attr($default_button_color); ?>;
}
.btn:hover, p.form-row input.button:hover, p.form-row input.button:focus, .btn:active, .btn:focus, .wpcf7-submit:hover, .wpcf7-submit:active, .wpcf7-submit:focus, button.single_add_to_cart_button:hover, button.single_add_to_cart_button:active, button.single_add_to_cart_button:focus, .woocommerce .button:hover, .woocommerce .button:focus {
  background-color: <?php echo esc_attr($default_button_hover_bg); ?>;
  color: <?php echo esc_attr($default_button_hover_color); ?>;
  border:0;
}

.btn.style-1, .vc_btn.style-1   {
  border-radius: 4px;
  background-color: <?php echo esc_attr($style_1_button_bg); ?>;
  color: <?php echo esc_attr($style_1_button_color); ?>!important;
}
.post-password-form input[type="submit"]:hover,
.post-password-form input[type="submit"]:focus,
.btn.style-1:hover, .btn.style-1:active, .btn.style-1:focus, .vc_btn.style-1:hover, .vc_btn.style-1:active, .vc_btn.style-1:focus  {
  background-color: <?php echo esc_attr($style_1_button_hover_bg); ?>;
  color: <?php echo esc_attr($style_1_button_hover_color); ?>!important;
}


.btn.slider  {
  border-radius: 4px;
  background-color: <?php echo esc_attr($style_slider_button_bg); ?>;
  color: <?php echo esc_attr($style_slider_button_color); ?>;
}
.btn.slider:hover, .btn.slider:active, .btn.slider:focus  {
  background-color: <?php echo esc_attr($style_slider_button_hover_bg); ?>;
  color: <?php echo esc_attr($style_slider_button_hover_color); ?>;
}




.btn.style-2, .vc_btn.style-2  {
  border-radius: 4px;
  border: 2px solid <?php echo esc_attr($style_2_button_bg); ?>;
  background-color: <?php echo esc_attr($style_2_button_bg); ?>;
  color: <?php echo esc_attr($style_2_button_color); ?>!important;
}

.btn.style-2:hover, .btn.style-2:active, .btn.style-2:focus, .vc_btn.style-2:hover, .vc_btn.style-2:active, .vc_btn.style-2:focus   {
  background-color: <?php echo esc_attr($style_2_button_hover_bg); ?>;
  color: <?php echo esc_attr($style_2_button_hover_color); ?>!important;
  border-color: <?php echo esc_attr($style_2_button_bg); ?>;
  border: 2px solid <?php echo esc_attr($style_2_button_bg); ?>;
}

.btn.style-3, .vc_btn.style-3  {
  border: 2px solid <?php echo esc_attr($style_3_button_border_color); ?>;;
  border-radius: 4px;
  background-color: transparent;
  color: <?php echo esc_attr($style_3_button_color); ?>!important;
}
.btn.style-3:hover, .btn.style-3:active, .btn.style-3:focus, .vc_btn.style-3:hover, .vc_btn.style-3:active, .vc_btn.style-3:focus  {
  border: 2px solid <?php echo esc_attr($style_3_button_border_color); ?>;
  background-color: <?php echo esc_attr($style_3_button_hover_bg); ?>;
  color: <?php echo esc_attr($style_3_button_hover_color); ?>!important;
}

.btn.style-4, .vc_btn.style-4   {
  padding-left: 0;
  background-color: transparent;
  color: <?php echo esc_attr($style_4_button_color); ?>!important;
  border: none;
}

.btn.style-4:hover, .btn.style-4:active, .btn.style-4:focus, .vc_btn.style-4:hover, .vc_btn.style-4:active, .vc_btn.style-4:focus   {
  padding-left: 0;
  background: none;
  color: <?php echo esc_attr($style_4_button_hover_color); ?>!important;
  border: none;
  border-color: transparent;
  outline: none;
}

.btn.style-5, .vc_btn.style-5   {
  background-color: <?php echo esc_attr($style_style_5_button_bg); ?>!important;
  color: <?php echo esc_attr($style_style_5_button_color); ?>!important;
  border: none;
}

.btn.style-5:hover, .btn.style-5:active, .btn.style-5:focus, .vc_btn.style-5:hover, .vc_btn.style-5:active, .vc_btn.style-5:focus   {
  background-color: <?php echo esc_attr($style_style_5_button_hover_bg); ?>!important;
  color: <?php echo esc_attr($style_style_5_button_hover_color); ?>!important;
}

site-header a.menu-button, .menu-button {
    background-color: <?php echo esc_attr($anps_header_style_6_button_bg);?>!important;
    color: <?php echo esc_attr($anps_header_style_6_button_color);?>!important;
}
site-header a.menu-button:hover, site-header a.menu-button:focus, .menu-button:hover, .menu-button:focus {
    background-color: <?php echo esc_attr($anps_header_style_6_button_hover_bg);?>!important;
    color: <?php echo esc_attr($anps_header_style_6_button_hover_color);?>!important;
}



<?php
}
