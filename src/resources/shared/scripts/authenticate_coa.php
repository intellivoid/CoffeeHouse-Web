<?php

    use COASniffle\COASniffle;
    use COASniffle\Exceptions\BadResponseException;
    use COASniffle\Exceptions\CoaAuthenticationException;
    use COASniffle\Exceptions\RequestFailedException;
    use COASniffle\Exceptions\UnsupportedAuthMethodException;
    use CoffeeHouse\Abstracts\UserSubscriptionSearchMethod;
    use CoffeeHouse\Exceptions\UserSubscriptionNotFoundException;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
    use sws\sws;

    Runtime::import('CoffeeHouse');
    Runtime::import('IntellivoidSubscriptionManager');

    if(WEB_SESSION_ACTIVE == false)
    {
        if(isset($_GET['access_token']))
        {
            process_coa_authentication();
        }
    }

    function process_coa_authentication()
    {
        /** @var COASniffle $COASniffle */
        $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
        $CoffeeHouse = new CoffeeHouse\CoffeeHouse();

        try
        {
            $UserInformation = $COASniffle->getCOA()->getUser($_GET['access_token']);
        }
        catch (BadResponseException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '101')
            ));
        }
        catch (CoaAuthenticationException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '102', 'coa_error' => (string)$e->getCode())
            ));
        }
        catch (RequestFailedException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '103')
            ));
        }
        catch (UnsupportedAuthMethodException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '104')
            ));
        }
        catch(Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '100')
            ));
        }

        /** @var sws $sws */
        $sws = DynamicalWeb::getMemoryObject('sws');

        $Cookie = $sws->WebManager()->getCookie('ch_session');
        $Cookie->Data['session_active'] = true;
        $Cookie->Data['account_pubid'] = $UserInformation->PublicID;
        $Cookie->Data['account_id'] = $UserInformation->Tag;
        $Cookie->Data['account_username'] = $UserInformation->Username;
        $Cookie->Data['access_token'] = $_GET['access_token'];

        // Force refresh cache
        if(isset($Cookie->Data['cache_refresh']) == true)
        {
            $Cookie->Data['cache_refresh'] = 0;
        }


        try
        {
            $UserSubscription = $CoffeeHouse->getUserSubscriptionManager()->getUserSubscription(
                UserSubscriptionSearchMethod::byAccountID, $UserInformation->Tag
            );

            $Cookie->Data['subscription_active'] = true;
            $Cookie->Data['user_subscription_id'] = $UserSubscription->ID;
        }
        catch (UserSubscriptionNotFoundException $e)
        {
            $Cookie->Data['subscription_active'] = false;
            $Cookie->Data['user_subscription_id'] = 0;
        }
        catch(Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'index', array('callback' => '100')
            ));
        }

        $sws->CookieManager()->updateCookie($Cookie);
        $sws->WebManager()->setCookie($Cookie);

        $Redirect = "None";

        if(isset($_GET['redirect']))
        {
            $Redirect = $_GET['redirect'];
        }

        switch($Redirect)
        {
            case 'lydia_demo':
                Actions::redirect(DynamicalWeb::getRoute(
                    'lydia_demo', array()
                ));
                break;

            case 'confirm_purchase':
                if(isset($_GET['plan']))
                {
                    Actions::redirect(DynamicalWeb::getRoute(
                        'purchase', array('plan' => $_GET['plan'])
                    ));
                }
                break;

            case 'dashboard':
                Actions::redirect(DynamicalWeb::getRoute(
                    'dashboard', array()
                ));
                break;
        }

        Actions::redirect(DynamicalWeb::getRoute(
            'index'
        ));
    }