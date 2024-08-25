<?php
function clean_custom_menus($logo_url = null)
{
	$menu_name = 'main-menu';
	$menu_list = '';
	if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
		$menu = wp_get_nav_menu_object($locations[$menu_name]);

		if ($menu) {
			$menu_items = wp_get_nav_menu_items($menu->term_id);

			if (!empty($menu_items)) {
				$menu_list = '<nav class="navbar navbar-expand-lg">' . "\n"; // Add navbar class for Bootstrap styling
				$menu_list .= '<a class="navbar-brand" href="' . esc_url(home_url('/')) . '">';

				if ($logo_url) {
					$menu_list .= '<img loading="lazy"  src="' . esc_url($logo_url) . '" alt="Logo" class="d-inline-block align-top" />'; // Display the logo
				} else {
					$menu_list .= get_bloginfo('name'); // Fallback to site name if no logo is set
				}

				$menu_list .= '</a>' . "\n";

				$menu_list .= "\t\t\t\t" . '<ul class="navbar-nav">' . "\n"; // Align menu to the right

				foreach ($menu_items as $menu_item) {
					if ($menu_item->menu_item_parent == 0) {
						$parent = $menu_item->ID;
						$menu_array = [];

						foreach ($menu_items as $submenu) {
							if ($submenu->menu_item_parent == $parent) {
								$menu_array[] = '<a class="dropdown-item ' . esc_attr(implode(' ', $submenu->classes)) . '" target="' . esc_attr($submenu->target) . '" href="' . esc_url($submenu->url) . '">' . esc_html($submenu->title) . '</a>' . "\n";
							}
						}

						if (count($menu_array) > 0) {
							$menu_list .= '<li class="nav-item dropdown">' . "\n";
							$menu_list .= '<a class="nav-link dropdown-toggle ' . esc_attr(implode(' ', $menu_item->classes)) . '" target="' . esc_attr($menu_item->target) . '" href="#" id="navbardrop" data-bs-toggle="dropdown">' . esc_html($menu_item->title) . '</a>' . "\n";
							$menu_list .= '<div class="dropdown-menu text-center">' . "\n";
							$menu_list .= implode("\n", $menu_array);
							$menu_list .= '</div>' . "\n";
						} else {
							$menu_list .= '<li class="nav-item">' . "\n";
							$menu_list .= '<a class="nav-link ' . esc_attr(implode(' ', $menu_item->classes)) . '" target="' . esc_attr($menu_item->target) . '" href="' . esc_url($menu_item->url) . '">' . esc_html($menu_item->title) . '</a>' . "\n";
						}
					}

					$menu_list .= '</li>' . "\n";
				}

				$menu_list .= "\t\t\t\t" . '</ul>' . "\n";
				$menu_list .= "\t\t\t" . '</nav>' . "\n";
			} else {
				$menu_list = '<nav><ul><li>No menu items found!</li></ul></nav>';
			}
		} else {
			$menu_list = '<nav><ul><li>Menu not created. Go to Appearance -> Menus and create one.</li></ul></nav>';
		}
	} else {
		$menu_list = '<nav><ul><li>Menu not created. Go to Appearance -> Menus and create one.</li></ul></nav>';
	}

	return $menu_list;
}