<?php

    use COASniffle\COASniffle;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;

    function require_authentication(string $redirect_to='index', array $params=array())
    {
        if(WEB_SESSION_ACTIVE == false)
        {
            $params['redirect'] = $redirect_to;

            /** @var COASniffle $COASniffle */
            $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
            $Protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
            $RedirectURL = $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index', $params);
            $AuthenticationURL = $COASniffle->getCOA()->getAuthenticationURL($RedirectURL);

            Actions::redirect($AuthenticationURL);
        }
    }