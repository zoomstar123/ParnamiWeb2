<?php
class Portfolio {
    public function __construct() {
        $this->register_post_type();
        register_taxonomy('portfolio_category', 'portfolio', array('hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true));       
    }
    
    private function register_post_type() {
        $args = array(
          'labels' => array(
              'name' =>'Portfolio',
              'singular_name' => 'Portfolio',
              'add_new' => 'Add new',
              'add_new_item' => 'Add new item',
              'edit_item' => 'Edit item',
              'new_item' => 'New item',
              'view_item' => 'View portfolio',
              'search_items' => 'Search portfolio',
              'not_found' => 'No portfolio found'
          ),
            'query_var' => 'id',
            /*'rewrite' => array(
                'slug' => 'content-portfolio'
            ),*/
            'rewrite' => true,
            'public' => true,
            'supports' => array(
                            'title',
                            'thumbnail',
                            'editor',
                            'categories'
            )
        );
        
       register_post_type('portfolio', $args);
    }
}