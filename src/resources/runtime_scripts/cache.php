<?php

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;
    use sws\Objects\Cookie;

    $sws = DynamicalWeb::getMemoryObject('sws');

    if($sws->WebManager()->isCookieValid('web_session') == true)
    {

        /** @var Cookie $Cookie */
        $Cookie = DynamicalWeb::getMemoryObject('(cookie)web_session');

        if(time() > $Cookie->Data['cache_refresh'])
        {

            $Cookie->Data['cache']['example_id'] = 0;
            $Cookie->Data['cache']['balance_available'] = false;
            $Cookie->Data['cache']['balance_amount'] = (float)0;

            if(defined('WEB_SESSION_ACTIVE') == true)
            {
                if(WEB_SESSION_ACTIVE == true)
                {
                    /** @noinspection PhpUnhandledExceptionInspection */
                    Runtime::import('IntellivoidAccounts');

                    $IntellivoidAccounts = new IntellivoidAccounts();

                    /** @noinspection PhpUnhandledExceptionInspection */
                    $AccountObject = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, WEB_ACCOUNT_ID);

                    $Cookie->Data['cache']['balance_available'] = true;
                    $Cookie->Data['cache']['balance_amount'] = $AccountObject->Configuration->Balance;

                    // TODO: Add plan cache here

                }
            }

            $Cookie->Data['cache_refresh'] = time() + 30;

            $sws->CookieManager()->updateCookie($Cookie);
            DynamicalWeb::setMemoryObject('(cookie)web_session', $Cookie);
        }

        define('CACHE_BALANCE_AVAILABLE', $Cookie->Data['cache']['balance_available'], false);
        define('CACHE_BALANCE_AMOUNT', $Cookie->Data['cache']['balance_amount'], false);

    }