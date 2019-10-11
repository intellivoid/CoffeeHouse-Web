<?php

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Page;
    use DynamicalWeb\Runtime;
    use sws\sws;

    Runtime::import('SecuredWebSessions');

    /** @var sws $sws */
    $sws = DynamicalWeb::setMemoryObject('sws', new sws());

    if($sws->WebManager()->isCookieValid('web_session') == false)
    {
        $Cookie = $sws->CookieManager()->newCookie('web_session', 86400, false);

        $Cookie->Data = array(
            'session_active' => false,
            'account_pubid' => null,
            'account_id' => null,
            'account_email' => null,
            'account_username' => null,
            'cache' => array(),
            'cache_refresh' => 0
        );

        $sws->CookieManager()->updateCookie($Cookie);
        $sws->WebManager()->setCookie($Cookie);

        if($Cookie->Name == null)
        {
            print('There was an issue with the security check, Please refresh the page');
            exit();
        }

        header('Refresh: 2; URL=/');
        HTML::print('Loading Web Resources');
        exit();

    }

    try
    {
        $Cookie = $sws->WebManager()->getCookie('web_session');
    }
    catch(Exception $exception)
    {
        Page::staticResponse(
            'CoffeeHouse Error',
            'Web Sessions Issue',
            'There was an issue with your Web Session, try clearing your cookies and try again'
        );
        exit();
    }

    DynamicalWeb::setMemoryObject('(cookie)web_session', $Cookie);

    define('WEB_SESSION_ACTIVE', $Cookie->Data['session_active'], false);
    define('WEB_ACCOUNT_PUBID', $Cookie->Data['account_pubid'], false);
    define('WEB_ACCOUNT_ID', $Cookie->Data['account_id'], false);
    define('WEB_ACCOUNT_EMAIL', $Cookie->Data['account_email'], false);
    define('WEB_ACCOUNT_USERNAME', $Cookie->Data['account_username'], false);
