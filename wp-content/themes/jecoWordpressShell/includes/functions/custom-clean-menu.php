<?php
// custom menu example @ https://digwp.com/2011/11/html-formatting-custom-menus/
function clean_custom_menus()
{
    $menu_name = 'main-menu'; // specify custom menu slug
    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        if ($menu) {
            $menu_items = wp_get_nav_menu_items($menu->term_id);

            $menu_list = '<nav>' . "\n";
            $menu_list .= "\t\t\t\t" . '<ul>' . "\n";

            $cont = 0;

            foreach ($menu_items as $menu_item) {

                if ($menu_item->menu_item_parent == 0) {

                    $parent = $menu_item->ID;

                    $menu_array = array();
                    foreach ($menu_items as $submenu) {
                        if ($submenu->menu_item_parent == $parent) {
                            $menu_array[] = '<a class="dropdown-item ' . implode(' ', $submenu->classes) . '" target="' . $submenu->target . '" href="' . $submenu->url . '">' . $submenu->title . '</a>' . "\n";
                        }
                    }
                    if (count($menu_array) > 0) {



                        $menu_list .= '<li class="nav-item dropdown ">' . "\n";
                        $menu_list .= '<a class="nav-link dropdown-toggle ' . implode(' ', $menu_item->classes) . '"  target="' . $menu_item->target . '" href="#" id="navbardrop" data-toggle="dropdown">' . $menu_item->title . ' </a>' . "\n";

                        $menu_list .= '<div class="dropdown-menu text-center">' . "\n";
                        $menu_list .= implode("\n", $menu_array);
                        $menu_list .= '</div>' . "\n";
                    } else {


                        $menu_list .= '<li class="nav-item ">' . "\n";
                        //Buttons. 7 = first button. 8 = second button
                        if ($cont == 7) {
                            $menu_list .= '<a class="nav-link ' . implode(' ', $menu_item->classes) . '"  target="' . $menu_item->target . '" href="' . $menu_item->url . '">' . $menu_item->title . '</a>' . "\n";
                        } else {
                            if ($cont == 8)
                                $menu_list .= '<a class="nav-link ' . implode(' ', $menu_item->classes) . '"  target="' . $menu_item->target . '" href="' . $menu_item->url . '">' . $menu_item->title . '</a>' . "\n";
                            else
                                $menu_list .= '<a class="nav-link ' . implode(' ', $menu_item->classes) . '"  target="' . $menu_item->target . '" href="' . $menu_item->url . '">' . $menu_item->title . '</a>' . "\n";
                        } //else
                    }
                }

                // end <li>
                $menu_list .= '</li>' . "\n";
            }


            $menu_list .= "\t\t\t\t" . '</ul>' . "\n";
            $menu_list .= "\t\t\t" . '</nav>' . "\n";
        } else $menu_list = 'Menu not created. Go to appereance -> menu and create one';
    } else {
        $menu_list = 'Menu not created. Go to appereance -> menu and create one';
    }
    echo $menu_list;
}
//Custom Menu