<?php // Plugin Features

if (!defined('ABSPATH')) exit;

// THANKS to Classic Editor for these "feature" functions

function disable_gutenberg_add_submenus() {
	
	if (disable_gutenberg()) return;
	
	foreach (get_post_types(array('show_ui' => true)) as $type) {
		
		$type_obj = get_post_type_object($type);
		
		if (!$type_obj->show_in_menu || !post_type_supports($type, 'editor')) continue;
		
		if ($type_obj->show_in_menu === true) {
			
			if ('post' === $type) {
				
				$parent_slug = 'edit.php';
				
			} elseif ('page' === $type) {
				
				$parent_slug = 'edit.php?post_type=page';
				
			} else {
				
				continue;
				
			}
			
		} else {
			
			$parent_slug = $type_obj->show_in_menu;
			
		}
		
		$item_name = $type_obj->labels->add_new .' '. __('(Classic)', 'disable-gutenberg');
		
		add_submenu_page($parent_slug, $type_obj->labels->add_new, $item_name, $type_obj->cap->edit_posts, 'post-new.php?post_type='. $type .'&classic-editor');
		
	}
	
}
add_action('admin_menu', 'disable_gutenberg_add_submenus');



function disable_gutenberg_page_row_actions($actions, $post) {
	
	if (disable_gutenberg()) return $actions;
	
	if (array_key_exists('classic', $actions)) return $actions;
	
	if ('trash' === $post->post_status || !post_type_supports($post->post_type, 'editor')) return $actions;
	
	$edit_url = get_edit_post_link($post->ID, 'raw');
	
	if (!$edit_url) return $actions;

	$edit_url = add_query_arg('classic-editor', '', $edit_url);
	
	$title = _draft_or_post_title($post->ID);
	
	$edit_action = array(
		
		'classic' => sprintf(
			
			'<a href="%s" aria-label="%s">%s</a>',
			
			esc_url($edit_url),
			
			esc_attr(sprintf(
				
				__('Edit &#8220;%s&#8221; with Classic Editor', 'disable-gutenberg'),
				
				$title
				
			)),
			
			__('Edit (Classic)', 'disable-gutenberg')
			
		),
		
	);
	
	$edit_offset = array_search('edit', array_keys($actions), true);
	
	array_splice($actions, $edit_offset + 1, 0, $edit_action);
	
	return $actions;
	
}
add_filter('page_row_actions', 'disable_gutenberg_page_row_actions', 15, 2);
add_filter('post_row_actions', 'disable_gutenberg_page_row_actions', 15, 2);



function disable_gutenberg_get_edit_post_link($url) {
	
	global $current_screen;
	
	if (!isset($_REQUEST['classic-editor']) && !disable_gutenberg()) return $url;
	
	$url = add_query_arg('classic-editor', '', $url);
	
	return $url;
	
}
add_filter('get_edit_post_link', 'disable_gutenberg_get_edit_post_link');



function disable_gutenberg_redirect_post_location($location) {
	
	global $current_screen;
	
	if (!isset($_REQUEST['classic-editor']) && !disable_gutenberg()) return $location;
	
	if (isset($_REQUEST['classic-editor']) || (isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'], '&classic-editor') !== false)) {
		
		$location = add_query_arg('classic-editor', '', $location);
		
	}
	
	return $location;
	
}
add_filter('redirect_post_location', 'disable_gutenberg_redirect_post_location');



function disable_gutenberg_edit_form_top() {
	
	global $current_screen;
	
	if (!isset($_GET['classic-editor']) && !disable_gutenberg()) return;
	
	?>
	
	<input type="hidden" name="classic-editor" value="" />
	
	<?php
	
}
add_action('edit_form_top', 'disable_gutenberg_edit_form_top');
