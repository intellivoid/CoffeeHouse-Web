<?php
    use DynamicalWeb\DynamicalWeb;
?>
<nav id="ch-navbar" class="navbar navbar-expand-lg navbar-inverse navbar-toggleable-md fixed-top sticky navbar-custom">
    <div class="container">
        <a class="navbar-brand logo" href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>">
            <span class="logo-text">CoffeeHouse</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
            <ul class="navbar-nav ml-auto" id="mySidenav">
                <li class="nav-item">
                    <a href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>#pricing" class="nav-link">Pricing</a>
                </li>
                <li class="nav-item">
                    <a href="<?PHP DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="nav-link">Lydia Demo</a>
                </li>
                <li class="nav-item">
                    <a href="<?PHP DynamicalWeb::getRoute('dashboard', array(), true); ?>" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="https://intellivoid.info/" class="nav-link">Intellivoid</a>
                </li>
                <?PHP
                    if(WEB_SESSION_ACTIVE)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="<?PHP DynamicalWeb::getRoute('logout', array(), true); ?>" class="nav-link">Logout</a>
                        </li>
                        <?PHP
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
