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
                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('logout', array(), true); ?>"><i class="dripicons-exit text-muted"></i><?PHP HTML::print(TEXT_NAVBAR_AVATAR_DROPDOWN_LOGOUT); ?></a>
                        </div>
                    </li>
                    <li class="menu-item list-inline-item">
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <li>
                        <a href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>">
                            <i class="mdi mdi-home"></i> <?PHP HTML::print(TEXT_NAVBAR_HOME); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?PHP DynamicalWeb::getRoute('dashboard', array('action' => 'generate_access_key'), true); ?>">
                            <i class="mdi mdi-refresh"></i> <?PHP HTML::print(TEXT_NAVBAR_GENERATE_ACCESS_KEY); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://docs.intellivoid.net/coffeehouse/v1/introduction" target="_blank">
                            <i class="mdi mdi-book"></i> <?PHP HTML::print(TEXT_NAVBAR_DOCS); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>