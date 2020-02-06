<?php

use COASniffle\Abstracts\AvatarResourceName;
use COASniffle\Handlers\COA;
use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\HTML;
?>

<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="<?PHP DynamicalWeb::getRoute('dashboard', array(), true); ?>" class="logo">
                    <img src="/assets/images/logo-sm.png" alt="CoffeeHouse Logo" height="30">
                </a>
            </div>
            <div class="menu-extras topbar-custom">
                <ul class="list-inline float-right mb-0">
                    <li class="list-inline-item dropdown notification-list hide-phone">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="modal" data-target="#change-language-dialog" href="#" role="button">
                            <i class="mdi mdi-translate noti-icon"></i>
                        </a>
                    </li>

                    <!-- User-->
                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="<?PHP HTML::print(COA::getAvatarUrl(AvatarResourceName::Normal, WEB_ACCOUNT_PUBID)); ?>" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('logout', array(), true); ?>"><i class="dripicons-exit text-muted"></i><?PHP HTML::print(TEXT_LOGOUT_BUTTON); ?></a>
                        </div>
                    </li>
                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                </ul>
            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <!-- MENU Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <li>
                        <a href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>">
                            <i class="mdi mdi-home"></i> <?PHP HTML::print("Home"); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?PHP DynamicalWeb::getRoute('dashboard', array('action' => 'generate_access_key'), true); ?>">
                            <i class="mdi mdi-refresh"></i> <?PHP HTML::print("Generate Access Key"); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://gist.github.com/Netkas/d3617e5b5b66c7851c728d3c0073529a" target="_blank">
                            <i class="mdi mdi-book"></i> <?PHP HTML::print("API Documentation"); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>