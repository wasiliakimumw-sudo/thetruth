<?php
/**
 * Sidebar Template
 */

if (!is_active_sidebar('sidebar-main') && !is_active_sidebar('sidebar-sticky')) {
    return;
}
?>
<aside id="secondary" class="widget-sidebar">
    <div class="sidebar-inner">
        <?php
        if (is_active_sidebar('sidebar-sticky')) :
            dynamic_sidebar('sidebar-sticky');
        elseif (is_active_sidebar('sidebar-main')) :
            dynamic_sidebar('sidebar-main');
        endif;
        ?>
    </div>
</aside>
