<?php

    use DynamicalWeb\DynamicalWeb;
    use sws\sws;

    if(WEB_SESSION_ACTIVE == false)
    {
        header('Location: /login');
        exit();
    }

    /** @noinspection PhpUnhandledExceptionInspection */

    /** @var sws $sws */
    $sws = DynamicalWeb::getMemoryObject('sws');
    $Cookie = $sws->WebManager()->getCookie('web_session');

    $Cookie->Data['cache_refresh'] = 0;
    $Cookie->Data['session_active'] = false;
    $Cookie->Data['account_pubid'] = null;
    $Cookie->Data['account_id'] = null;
    $Cookie->Data['account_email'] = null;
    $Cookie->Data['account_username'] = null;

    $sws->CookieManager()->updateCookie($Cookie);

    header('Location: /');
    exit();