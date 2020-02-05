<?PHP

    use CoffeeHouse\Abstracts\UserSubscriptionSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\UserSubscriptionNotFoundException;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
    use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
    use IntellivoidAPI\IntellivoidAPI;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionSearchMethod;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionNotFoundException;
    use IntellivoidSubscriptionManager\IntellivoidSubscriptionManager;
    use IntellivoidSubscriptionManager\Objects\Subscription\Feature;

    Runtime::import('CoffeeHouse');
    Runtime::import('IntellivoidSubscriptionManager');
    Runtime::import('IntellivoidAPI');

    HTML::importScript('require_auth');
    HTML::importScript('check_subscription');

    if(WEB_SESSION_ACTIVE == false)
    {
        if(isset($_GET['access_token']))
        {
            HTML::importScript('authenticate_coa');
        }
    }

    require_authentication('dashboard');


    if(WEB_SUBSCRIPTION_ACTIVE == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('index') . '#pricing');
    }

    // DASHBOARD LOGIC
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

    if(isset(DynamicalWeb::$globalObjects['intellivoid_subscription_manager']) == false)
    {
        /** @var IntellivoidSubscriptionManager $IntellivoidSubscriptionManager */
        $IntellivoidSubscriptionManager = DynamicalWeb::setMemoryObject('intellivoid_subscription_manager', new IntellivoidSubscriptionManager());
    }
    else
    {
        /** @var IntellivoidSubscriptionManager $IntellivoidSubscriptionManager */
        $IntellivoidSubscriptionManager = DynamicalWeb::getMemoryObject('intellivoid_subscription_manager');
    }

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

    try
    {
        $UserSubscription = $CoffeeHouse->getUserSubscriptionManager()->getUserSubscription(
            UserSubscriptionSearchMethod::byAccountID, WEB_ACCOUNT_ID
        );
    }
    catch (UserSubscriptionNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_us_not_found'
        )));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_us_cluster_error'
        )));
    }


    try
    {
        $Subscription = $IntellivoidSubscriptionManager->getSubscriptionManager()->getSubscription(
            SubscriptionSearchMethod::byId, $UserSubscription->SubscriptionID
        );
    }
    catch (SubscriptionNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_s_not_found'
        )));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_s_cluster_error'
        )));
    }

    try
    {
        $AccessRecord = $IntellivoidAPI->getAccessKeyManager()->getAccessRecord(
            AccessRecordSearchMethod::byId, $UserSubscription->AccessRecordID
        );
    }
    catch (AccessRecordNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_ar_not_found'
        )));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_ar_cluster_error'
        )));
    }

    $ConfiguredLydiaSessions = "Unknown";
    $UsedLydiaSessions = "Unknown";

    /** @var Feature $feature */
    foreach($Subscription->Properties->Features as $feature)
    {
        switch($feature->Name)
        {
            case 'LYDIA_SESSIONS':
                $ConfiguredLydiaSessions = (int)$feature->Value;
                break;
        }
    }

    if(isset($AccessRecord->Variables['LYDIA_SESSIONS']))
    {
        $UsedLydiaSessions = (int)$AccessRecord->Variables['LYDIA_SESSIONS'];

        if($UsedLydiaSessions > $ConfiguredLydiaSessions)
        {
            $UsedLydiaSessions = $ConfiguredLydiaSessions;
        }
    }

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>CoffeeHouse Dashboard</title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>

        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <h4 class="page-title"><?PHP HTML::print("CoffeeHouse Dashboard"); ?></h4>
                        </div>
                    </div>
                </div>

                <?PHP HTML::importScript('render_widgets'); ?>
            </div>
        </div>

        <?PHP HTML::importSection('footer'); ?>
    </body>
    <?PHP HTML::importSection('jquery'); ?>
    <script src="/assets/vendors/morris/morris.min.js"></script>
    <script src="/assets/vendors/raphael/raphael-min.js"></script>
</html>
