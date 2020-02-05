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
use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionSearchMethod;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionNotFoundException;
use IntellivoidSubscriptionManager\Exceptions\SubscriptionPlanNotFoundException;
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

        $SubscriptionPlan = $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlan(
                SubscriptionPlanSearchMethod::byId, $Subscription->SubscriptionPlanID
        );
    }
    catch (SubscriptionNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_s_not_found'
        )));
    }
    catch (SubscriptionPlanNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('service_error', array(
            'error_type' => 'rd_sp_not_found'
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

                <!-- CoffeeHouse Dashboard Widgets -->
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="mini-stat clearfix bg-white animated fadeInLeft">
                            <span class="mini-stat-icon bg-blacksalami mr-0 float-right">
                                <img alt="Lydia Logo" src="/assets/images/lydia_white_transparent.svg" class="img-fluid img-xs rounded-circle mb-3">
                            </span>
                            <div class="mini-stat-info">
                                <span class="counter text-white" id="calls_current_month"><?PHP HTML::print(number_format($UsedLydiaSessions)); ?></span>
                                <?PHP HTML::print("Lydia Sessions"); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20" id="calls_last_month"><?PHP HTML::print(str_ireplace('%s', number_format($ConfiguredLydiaSessions), "%s Total Sessions Allowed")); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="mini-stat clearfix bg-white animated fadeInDown">
                            <span class="mini-stat-icon bg-success mr-0 float-right">
                                <i class="mdi mdi-chart-pie"></i>
                            </span>
                            <div class="mini-stat-info">
                                <span class="counter text-success"><?PHP HTML::print("Billing Cycle"); ?></span>
                                <?PHP HTML::print("When your next bill is processed on"); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20">
                                <?PHP
                                if((int)time() > $Subscription->NextBillingCycle)
                                {
                                    HTML::print("Today");
                                }
                                else
                                {
                                    HTML::print(gmdate("j/m/Y", $Subscription->NextBillingCycle));
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="mini-stat clearfix bg-white animated fadeInRight">
                            <span class="mini-stat-icon bg-warning mr-0 float-right">
                                <i class="mdi mdi-shopping"></i>
                            </span>
                            <div class="mini-stat-info">
                                <span class="counter text-warning"><?PHP HTML::print($SubscriptionPlan->PlanName); ?></span>
                                <?PHP HTML::print(str_ireplace('%s', gmdate("j/m/Y g:i a", $Subscription->CreatedTimestamp), "You started on %s")); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20">
                                <?PHP
                                    HTML::print(str_ireplace('%s', $Subscription->Properties->CyclePrice, 'You pay $%s USD every month'));
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card m-b-20 animated flipInX">
                            <div class="card-body">
                                <div class="form-group m-b-0">
                                    <label for="api_key">Access Key</label>
                                    <input class="form-control" type="text" value="<?PHP HTML::print($AccessRecord->AccessKey); ?>" id="api_key" name="api_key" readonly>
                                </div>
                                <button class="btn btn-info btn-xs btn-block">
                                    <i class="mdi mdi-reload pr-2"></i> Generate new Access Key
                                </button>
                            </div>
                        </div>
                        <div class="card m-b-20 animated bounceInUp">
                            <div class="card-body">
                                <h5 class="header-title">Need Help?</h5>
                                <div class="mt-2 ml-3">
                                    <div class="row mt-3">
                                        <a class="text-white" href="https://t.me/IntellivoidDev">
                                            <i class="mdi mdi-telegram pr-2"></i>Telegram Support Group
                                        </a>
                                    </div>
                                    <div class="row mt-3">
                                        <a class="text-white" href="https://t.me/IntellivoidSupport">
                                            <i class="mdi mdi-telegram pr-2"></i>Intellivoid Support Account
                                        </a>
                                    </div>
                                    <div class="row mt-3">
                                        <a class="text-white" href="https://intellivoid.info/contact">
                                            <i class="mdi mdi-email pr-2"></i>Contact Intellivoid
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card m-b-20 animated bounceInRight">
                            <div class="card-body">
                                <h4 class="header-title">API Usage</h4>
                                <div id="api-usage-chart">
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height:50vh;">
                                        <div class="p-2 my-flex-item">
                                            <h4 class="text-muted"><?PHP HTML::print("Coming soon"); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <?PHP HTML::importSection('footer'); ?>
    </body>
    <?PHP HTML::importSection('jquery'); ?>
    <script src="/assets/vendors/morris/morris.min.js"></script>
    <script src="/assets/vendors/raphael/raphael-min.js"></script>
</html>
