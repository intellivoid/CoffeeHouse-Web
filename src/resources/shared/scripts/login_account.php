<?php

    use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\HTML;
use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\AccountSuspendedException;
    use IntellivoidAccounts\Exceptions\ConfigurationNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\Exceptions\IncorrectLoginDetailsException;
    use IntellivoidAccounts\Exceptions\InvalidIpException;
    use IntellivoidAccounts\Exceptions\InvalidLoginStatusException;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Validate;
    use sws\sws;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('check_plan');

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        try
        {
            LoginAccount();
        }
        catch(Exception $e)
        {
            header('Location: login?callback=103');
            exit();
        }
    }

    /**
     * @return mixed
     */
    function getClientIP()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Determines the proper redirect location based of the given parameters
     *
     * @return string
     */
    function getRedirectLocation()
    {
        return '/login?';
    }


    /**
     * Returns the location to redirect the user to on success
     *
     * @return string
     */
    function getSuccessLocation()
    {
        return '/';
    }

    /**
     * @throws ConfigurationNotFoundException
     * @throws DatabaseException
     * @throws InvalidIpException
     * @throws InvalidLoginStatusException
     * @throws InvalidSearchMethodException
     */
    function LoginAccount()
    {
        if(isset($_POST['username_email']) == false)
        {
            header('Location: ' . getRedirectLocation() . 'callback=100');
            exit();
        }

        if(isset($_POST['password']) == false)
        {
            header('Location: ' . getRedirectLocation() . 'callback=100');
            exit();
        }

        if(verify_recaptcha() == false)
        {
            header('Location: ' . getRedirectLocation() . 'callback=104');
            exit();
        }

        if(Validate::username($_POST['username_email']) == false)
        {
            if(Validate::email($_POST['username_email']) == false)
            {
                header('Location: ' . getRedirectLocation() . 'callback=101');
                exit();
            }
        }

        if(Validate::password($_POST['password']) == false)
        {
            header('Location: ' . getRedirectLocation() . 'callback=101');
            exit();
        }

        if(isset(DynamicalWeb::$globalObjects['intellivoid_accounts']) == false)
        {
            /** @var IntellivoidAccounts $IntellivoidAccounts */
            $IntellivoidAccounts = DynamicalWeb::setMemoryObject('intellivoid_accounts', new IntellivoidAccounts());
        }
        else
        {
            /** @var IntellivoidAccounts $IntellivoidAccounts */
            $IntellivoidAccounts = DynamicalWeb::getMemoryObject('intellivoid_accounts');
        }

        try
        {
            $IntellivoidAccounts->getAccountManager()->checkLogin($_POST['username_email'], $_POST['password']);

            $Account = null;
            if(Validate::email($_POST['username_email']) == true)
            {
                $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
                    AccountSearchMethod::byEmail, $_POST['username_email']
                );
            }
            else
            {
                $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
                    AccountSearchMethod::byUsername, $_POST['username_email']
                );
            }

            $IntellivoidAccounts->getLoginRecordManager()->createLoginRecord(
                $Account->ID, getClientIP(), LoginStatus::Successful, 'CoffeeHouse Web Application'
            );

            if(planExists($Account->ID) == false)
            {
                header('Location: ' . getRedirectLocation() . 'callback=108');
                exit();
            }

            /** @var sws $sws */
            $sws = DynamicalWeb::getMemoryObject('sws');

            $Cookie = $sws->WebManager()->getCookie('web_session');
            $Cookie->Data['session_active'] = true;
            $Cookie->Data['account_pubid'] = $Account->PublicID;
            $Cookie->Data['account_id'] = $Account->ID;
            $Cookie->Data['account_email'] = $Account->Email;
            $Cookie->Data['account_username'] = $Account->Username;

            // Force refresh cache
            if(isset($Cookie->Data['cache_refresh']) == true)
            {
                $Cookie->Data['cache_refresh'] = 0;
            }

            $sws->CookieManager()->updateCookie($Cookie);
            $sws->WebManager()->setCookie($Cookie);

            header('Location: ' . getSuccessLocation());
            exit();
        }
        catch(IncorrectLoginDetailsException $incorrectLoginDetailsException)
        {
            try
            {
                $Account = null;

                if(Validate::email($_POST['username_email']))
                {
                    $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
                        AccountSearchMethod::byEmail, $_POST['username_email']
                    );
                }
                else
                {
                    $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
                        AccountSearchMethod::byUsername, $_POST['username_email']
                    );
                }

                $IntellivoidAccounts->getLoginRecordManager()->createLoginRecord(
                    $Account->ID, getClientIP(), LoginStatus::IncorrectCredentials, 'OpenBlu Web Application'
                );
            }
            catch(AccountNotFoundException $accountNotFoundException)
            {
                // Ignore this exception
            }

            header('Location: ' . getRedirectLocation() . 'callback=101');
            exit();
        }
        catch(AccountSuspendedException $accountSuspendedException)
        {
            header('Location: ' . getRedirectLocation() . 'callback=102');
            exit();
        }
        catch(Exception $exception)
        {
            header('Location: ' . getRedirectLocation() . 'callback=103');
            exit();
        }
    }