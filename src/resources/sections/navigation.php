<?php

use COASniffle\Abstracts\AvatarResourceName;
use COASniffle\Handlers\COA;
use DynamicalWeb\HTML;
?>

<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="/" class="logo">
                    <img src="/assets/images/logo-sm.png" alt="CoffeeHouse Logo" height="30">
                </a>
            </div>
            <div class="menu-extras topbar-custom">
                <ul class="list-inline float-right mb-0">
                    <li class="list-inline-item dropdown notification-list hide-phone">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-translate noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right language-switch">
                            <a class="dropdown-item" href="/?set_language=en">
                                <span> English </span>
                            </a>
                            <a class="dropdown-item" href="/?set_language=zh">
                                <span> 中文 </span>
                            </a>
                       </div>
                    </li>

                    <!-- User-->
                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="<?PHP HTML::print(COA::getAvatarUrl(AvatarResourceName::Normal, WEB_ACCOUNT_PUBID)); ?>" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <a class="dropdown-item" href="/logout"><i class="dripicons-exit text-muted"></i><?PHP \DynamicalWeb\HTML::print(TEXT_LOGOUT_BUTTON); ?></a>
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
                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    <li>
                        <a href="?action=update_signatures">
                            <i class="mdi mdi-refresh"></i> <?PHP \DynamicalWeb\HTML::print(TEXT_NAV_UPDATE_SIGNATURES); ?>
                        </a>
                    </li>

                    <li>
                        <a href="?action=download_certificate">
                            <i class="mdi mdi-certificate"></i> <?PHP \DynamicalWeb\HTML::print(TEXT_NAV_DOWNLOAD_CERTIFICATE); ?>
                        </a>
                    </li>

                    <li>
                        <a href="https://gist.github.com/Netkas/d3617e5b5b66c7851c728d3c0073529a" target="_blank">
                            <i class="mdi mdi-book"></i> <?PHP \DynamicalWeb\HTML::print(TEXT_NAV_API_DOCUMENTATION); ?>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>