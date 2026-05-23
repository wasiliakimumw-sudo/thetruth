<?php
/**
 * Menu Functions
 */

class GlobalNews_Walker_Nav extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $classes = array('sub-menu');
        if ($depth === 0) {
            $classes[] = 'mega-menu';
        }
        $class_names = implode(' ', $classes);
        $output .= "\n$indent<ul class=\"" . $class_names . "\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        if ($args->walker->has_children) {
            $classes[] = 'menu-item-has-children';
        }
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        $output .= $indent . '<li' . $id . $class_names . '>';
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        if ($args->walker->has_children) {
            $item_output .= '<svg class="dropdown-icon" width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 1l4 4 4-4"/></svg>';
        }
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

function globalnews_mobile_menu_fallback() {
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_class'     => 'mobile-menu-list',
        'container'      => false,
        'fallback_cb'    => false,
        'depth'          => 2,
        'walker'         => new GlobalNews_Walker_Nav(),
    ));
}

function globalnews_primary_menu_fallback() {
    $categories = get_categories(array('hide_empty' => false, 'orderby' => 'name', 'number' => 8));
    if (!empty($categories)) : ?>
        <ul class="main-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'globalnews-media'); ?></a></li>
            <?php foreach ($categories as $cat) : ?>
                <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->name); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif;
}
