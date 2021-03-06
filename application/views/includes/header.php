<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $pageTitle ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/main.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.min.css">
        <script src="<?= base_url() ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?= base_url() ?>assets/js/select2.min.js"></script>
    </head>
    <body class="app sidebar-mini rtl">
        <!-- Navbar-->
        <header class="app-header"><a class="app-header__logo" href="<?= base_url() ?>dashboard">Sanjog</a>
            <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
            <!-- Navbar Right Menu-->
            <ul class="app-nav">
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
                    <ul class="dropdown-menu settings-menu dropdown-menu-right">
                        <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
                        <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>dashboard/logout"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </header>
        <!-- Sidebar menu-->
        <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
        <aside class="app-sidebar">
            <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= base_url() ?>assets/images/tittle.png" alt="User Image" width="60px">
                <div>
                    <p class="app-sidebar__user-name"><?= $name; ?><?= $role; ?></p>
                    <p class="app-sidebar__user-designation"><?= $role_text; ?></p>
                </div>
            </div>
            <ul class="app-menu">
                <li><a class="app-menu__item" href="<?= base_url() ?>dashboard"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
                <?php
                $result = getRoleBasedPageAccess($role);

                foreach ($result as $row_module) {
                    ?>
                    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa <?= $row_module['moduleIcon'] ?>"></i><span class="app-menu__label"><?= $row_module['moduleName'] ?></span><i class="treeview-indicator fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                            <?php
                            foreach ($row_module['page'] as $row_page) {
                                ?>               
                                <li><a class="treeview-item" href="<?= base_url() . $row_page['pageFileName'] ?>"><i class="icon fa fa-circle-o"></i><?= $row_page['pageName'] ?></a></li>
                                        <?php
                                    }
                                    ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </aside>