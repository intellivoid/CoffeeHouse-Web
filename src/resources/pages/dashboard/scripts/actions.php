<?php

    use CoffeeHouse\Abstracts\UserSubscriptionSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\UserSubscriptionNotFoundException;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
    use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
    use IntellivoidAPI\Exceptions\DatabaseException;
    use IntellivoidAPI\Exceptions\InvalidRateLimitConfiguration;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\IntellivoidAPI;

    if(isset($_GET['action']))
    {
        switch($_GET['action'])
        {
            case 'generate_access_key':
                try
                {
                    generate_access_key();
                    Actions::redirect(DynamicalWeb::getRoute('dashboard', array('callback' => '101')));
                    exit();
                }
                catch(Exception $exception)
                {
                    Actions::redirect(DynamicalWeb::getRoute('dashboard', array('callback' => '100')));
                    exit();
                }

                break;
        }
    }

    /**
     * @throws AccessRecordNotFoundException
     * @throws InvalidRateLimitConfiguration
     * @throws \CoffeeHouse\Exceptions\DatabaseException
     * @throws \CoffeeHouse\Exceptions\InvalidSearchMethodException
     * @throws UserSubscriptionNotFoundException
     * @throws DatabaseException
     * @throws InvalidSearchMethodException
     */
    function generate_access_key()
    {
        if(isset(DynamicalWeb::$globalObjects['coffeehouse']) == false)
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::setMemoryObject('coffeehouse', new CoffeeHouse());
        }
        else
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');
        }


        if(isset(DynamicalWeb::$globalObjects['intellivoid_api']) == false)
        {
            /** @var IntellivoidAPI $IntellivoidAPI */
            $IntellivoidAPI = DynamicalWeb::setMemoryObject('intellivoid_api', new IntellivoidAPI());
        }
        else
        {
            /** @var IntellivoidAPI $IntellivoidAPI */
            $IntellivoidAPI = DynamicalWeb::getMemoryObject('intellivoid_api');
        }

        $UserSubscription = $CoffeeHouse->getUserSubscriptionManager()->getUserSubscription(
            UserSubscriptionSearchMethod::byAccountID, WEB_ACCOUNT_ID
        );

        $AccessRecord = $IntellivoidAPI->getAccessKeyManager()->getAccessRecord(
            AccessRecordSearchMethod::byId, $UserSubscription->AccessRecordID
        );

        $IntellivoidAPI->getAccessKeyManager()->generateNewAccessKey($AccessRecord);

    }