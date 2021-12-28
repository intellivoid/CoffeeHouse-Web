<?php

    use DynamicalWeb\DynamicalWeb;
    use sws\sws;

    if(WEB_SESSION_ACTIVE == false)
    {
        header('Location: /');
        exit();
    }

    /** @var sws $sws */
    $sws = DynamicalWeb::getMemoryObject('sws');
    $Cookie = $sws->WebManager()->getCookie('ch_session');

    $Cookie->Data['cache_refresh'] = 0;
    $Cookie->Data['session_active'] = false;
    $Cookie->Data['account_pubid'] = null;
    $Cookie->Data['account_id'] = null;
    $Cookie->Data['account_email'] = null;
    $Cookie->Data['account_username'] = null;
    $Cookie->Data['demo_session_id'] = 0;

    $sws->CookieManager()->updateCookie($Cookie);

    header('Location: /');
    exit();