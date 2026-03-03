<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree" data-animation-speed="100">
            <li class="header">Menu</li>
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>Home</span></a></li>
            <?php if ($this->session->userdata("id_grup") == "1") { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cog"></i> <span>Setting</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url('setting/index/grup-user') ?>"><i class="fa fa-circle-o"></i> Grup & User</a></li>
                        <li><a href="<?php echo site_url('setting/index/menu') ?>"><i class="fa fa-circle-o"></i> Menu</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (sizeof($sidebar_menu)) { ?>
                <li class="header"></li>
                <li class="header">Main Menu</li>
            <?php } ?>
            <?php
            foreach ($sidebar_menu as $key => $value) {
                $class_treeview = ($value['jns_menu'] == "1") ? "class=\"treeview\"" : "";
                $icon_menu      = ($value['jns_menu'] == "1") ? "fa fa-folder" : "fa fa-file";
                $menu_link      = ($value['jns_menu'] == "1") ? "javascript:void(0)" : site_url($value['link']);

                echo "<li " . $class_treeview . ">
                    <a href=\"" . $menu_link . "\">
                    <i class=\"" . $icon_menu . "\"></i> <span>" . $value['nm_menu'] . "</span>";

                if ($value['jns_menu'] == "1") {
                    echo "<span class=\"pull-right-container\"> <i class=\"fa fa-angle-left pull-right\"></i> </span>";
                }

                echo "</a>";

                if (sizeof($value['sub_menu']) > 0) {
                    echo "<ul class=\"treeview-menu\">";

                    foreach ($value['sub_menu'] as $key1 => $value1) {
                        $class_treeview = ($value1['jns_menu'] == "1") ? "class=\"treeview\"" : "";
                        $icon_menu      = ($value1['jns_menu'] == "1") ? "fa fa-folder" : "";
                        $menu_link      = ($value1['jns_menu'] == "1") ? "javascript:void(0)" : site_url($value1['link']);

                        echo "<li " . $class_treeview . ">
                            <a href=\"" . $menu_link . "\">
                            <i class=\"" . $icon_menu . "\"></i> <span>" . $value1['nm_menu'] . "</span>";

                        if ($value1['jns_menu'] == "1") {
                            echo "<span class=\"pull-right-container\"> <i class=\"fa fa-angle-left pull-right\"></i> </span>";
                        }

                        echo "</a>";

                        if (sizeof($value1['sub_menu']) > 0) {
                            echo "<ul class=\"treeview-menu\">";

                            foreach ($value1['sub_menu'] as $key2 => $value2) {
                                echo "<li>
                                    <a href=\"" . site_url($value2['link']) . "\">
                                    <i></i> <span>" . $value2['nm_menu'] . "</span>
                                    </a> </li>";
                            }

                            echo "</ul>";
                        }

                        echo "</li>";
                    }

                    echo "</ul>";
                }

                echo "</li>";
            } ?>
        </ul>
    </section>
</aside>