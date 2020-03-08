<?php get_header(); ?>

<div class="container search-page">
    <?php if ( have_posts() ) : $num = wp_count_posts(); ?>
        <ol class="search-posts">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php
                    $first_item = true;
                    $categories = '';

                    foreach(wp_get_post_categories(get_the_ID()) as $c) {
                        $cat = get_category($c);

                        if(!$first_item) {
                            $first_item = false;
                        }

                        
                        $categories .= "<a href='".get_category_link($c)."'>" . $cat->name . "</a>";
                    }

                    if( $categories ) {
                        $categories = __('in', 'transport') . ' ' . $categories;
                    }

                    $date = get_the_date();
                ?>

                <li class="search-post">
                    <a href="<?php echo the_permalink(); ?>">
                        <h2 class="search-post-title"><?php the_title(); ?></h2>
                    </a>
                    <div class="search-post-meta">
                        <?php _e('By', 'transport') ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a> <?php _e('on', 'transport') ?> <?php echo $date; ?> <?php echo $categories; ?>
                    </div>
                    <p class="search-post-desc">
                        <?php the_excerpt(); ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ol>
        <?php  get_template_part('includes/pagination'); ?>
    <?php else : ?>
        <h2 class="no-results"><?php _e('No results found for:', "transport"); ?> <span><?php echo esc_attr($_GET['s']); ?></span></h2>
    <?php endif; ?>
</div>
<?php get_footer();