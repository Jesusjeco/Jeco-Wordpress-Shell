<?php
function display_main_menu($logo_url = null)
{
	// Generate the home URL link
	$home_link = '<a href="' . esc_url(home_url('/')) . '">';

	// If a logo URL is provided, display the logo image
	if ($logo_url) {
		$logo_html = $home_link . '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '"></a>';
	} else {
		// Otherwise, display the site name
		$logo_html = $home_link . esc_html(get_bloginfo('name')) . '</a>';
	}

	if (has_nav_menu('main-menu')) {
		// Prepare the menu HTML using nowdoc syntax
		$menu_html = <<<HTML
			<nav>
					<div class="menu-logo">{$logo_html}</div>
		HTML;

		// Append the wp_nav_menu output to the menu HTML
		$menu_html .= wp_nav_menu(array(
			'theme_location' => 'main-menu',
			'walker'         => new My_Custom_Walker(),
			'echo'           => false, // Don't echo the menu, return it as a string
		));

		$menu_html .= <<<HTML
			</nav>
		HTML;
	} else {
		// Return the message if no menu is created yet
		return '<p>Menu not created. Go to Appearance -> Menus and create one.</p>';
	}

	// Return the complete menu HTML
	return $menu_html;
}

class My_Custom_Walker extends Walker_Nav_Menu
{
	// Start Level
	function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	// End Level
	function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	// Start Element
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = ' class="' . esc_attr($class_names) . '"';

		$output .= $indent . '<li' . $class_names . '>';

		$attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target)     . '"' : '';
		$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        . '"' : '';
		$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url)        . '"' : '';

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	// End Element
	function end_el(&$output, $item, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}
