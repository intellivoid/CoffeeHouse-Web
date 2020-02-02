<?php

    use COASniffle\COASniffle;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;

    if(WEB_SESSION_ACTIVE == false)
    {
        /** @var COASniffle $COASniffle */
        $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
        $Protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        $RedirectURL = $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index');
        $AuthenticationURL = $COASniffle->getCOA()->getAuthenticationURL($RedirectURL);

        Actions::redirect($AuthenticationURL);
    }