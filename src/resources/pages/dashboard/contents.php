<?PHP

    use CoffeeHouse\Abstracts\UserSubscriptionSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\UserSubscriptionNotFoundException;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Javascript;
    use DynamicalWeb\Runtime;
    use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
    use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
    use IntellivoidAPI\IntellivoidAPI;
    use IntellivoidAPI\Objects\AccessRecord;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionSearchMethod;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionNotFoundException;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidSubscriptionManager\IntellivoidSubscriptionManager;
    use IntellivoidSubscriptionManager\Objects\Subscription;
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
        /** @noinspection PhpUndefinedVariableInspection */
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
    /** @noinspection PhpUndefinedVariableInspection */
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

        if($ConfiguredLydiaSessions > 0)
        {
            if($UsedLydiaSessions > $ConfiguredLydiaSessions)
            {
                $UsedLydiaSessions = $ConfiguredLydiaSessions;
            }
        }

    }

    DynamicalWeb::setMemoryObject('subscription_plan', $SubscriptionPlan);
    DynamicalWeb::setMemoryObject('access_record', $AccessRecord);
    DynamicalWeb::setMemoryObject('subscription', $Subscription);

    HTML::importScript('deepanalytics');
    HTML::importScript('actions');
    HTML::importScript('alert');
    HTML::importScript('update_subscription');
    HTML::importScript('usage_widget');

    /** @noinspection PhpUnhandledExceptionInspection */
    if(us_update_required($Subscription))
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $Subscription = us_update_subscription($Subscription);

        Actions::redirect(DynamicalWeb::getRoute("dashboard", ["callback" => "102"]));
        exit();
    }

    /**
     * Gets the current usage record from the Access Key
     *
     * @param AccessRecord $access_record
     * @param string $usage_name
     * @return int
     */
    function getCurrentUsage(AccessRecord $access_record, string $usage_name): int
    {
        if(isset($access_record->Variables[$usage_name]) == false)
        {
            return 0;
        }

        return $access_record->Variables[$usage_name];
    }

    /**
     * Gets the current usage record from the Subscription
     *
     * @param Subscription $subscription
     * @param string $usage_name
     * @return int
     */
    function getAllowedUsage(Subscription $subscription, string $usage_name): int
    {
        foreach($subscription->Properties->Features as $feature)
        {
            if($feature->Name == $usage_name)
            {
                return $feature->Value;
            }
        }

        return 0;
    }

    $TotalResourceUsage = 0;
    $TotalResourcesAllocated = 0;
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/morris/morris.css">
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <h4 class="page-title"><?PHP HTML::print(TEXT_PAGE_HEADER); ?></h4>
                        </div>
                    </div>
                </div>
                <?PHP HTML::importScript('callbacks'); ?>


                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="mini-stat clearfix bg-white animated fadeInLeft">
                            <span class="mini-stat-icon bg-indigo mr-0 float-right">
                                <i class="mdi mdi-av-timer"></i>
                            </span>
                            <div class="mini-stat-info">
                                <span class="counter text-indigo"><?PHP HTML::print(TEXT_WIDGET_LAST_ACTIVITY_HEADER); ?></span>
                                <?PHP HTML::print(TEXT_WIDGET_LAST_ACTIVITY_DESCRIPTION); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20" id="calls_last_month">
                                <?PHP
                                    if($AccessRecord->LastActivity > 0)
                                    {
                                        HTML::print(gmdate("j/m/Y g:i a", $AccessRecord->LastActivity));
                                    }
                                    else
                                    {
                                        HTML::print(TEXT_WIDGET_LAST_ACTIVITY_NEVER);
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="mini-stat clearfix bg-white animated fadeInDown">
                            <span class="mini-stat-icon bg-success mr-0 float-right">
                                <i class="mdi mdi-chart-pie"></i>
                            </span>
                            <div class="mini-stat-info">
                                <span class="counter text-success"><?PHP HTML::print(TEXT_WIDGET_BILLING_CYCLE_HEADER); ?></span>
                                <?PHP HTML::print(TEXT_WIDGET_BILLING_CYCLE_DESCRIPTION); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20">
                                <?PHP
                                if((int)time() > $Subscription->NextBillingCycle)
                                {
                                    HTML::print(TEXT_BILLING_CYCLE_TODAY);
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
                                <?PHP HTML::print(str_ireplace('%s', gmdate("j/m/Y g:i a", $Subscription->CreatedTimestamp), TEXT_WIDGET_PLAN_DESCRIPTION)); ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="text-muted mb-0 m-t-20">
                                <?PHP HTML::print(str_ireplace('%s', $Subscription->Properties->CyclePrice, TEXT_WIDGET_PLAN_FOOTER)); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card m-b-20 animated flipInX">
                            <div class="card-body">
                                <div class="form-group m-b-0">
                                    <label for="api_key"><?PHP HTML::print(TEXT_ACCESS_KEY_CARD_TITLE); ?></label>
                                    <input class="form-control" type="text" value="<?PHP HTML::print($AccessRecord->AccessKey); ?>" id="api_key" name="api_key" readonly>
                                </div>
                                <button class="btn btn-info btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('dashboard', array('action' => 'generate_access_key'), true); ?>';">
                                    <i class="mdi mdi-reload pr-2"></i> <?PHP  HTML::print(TEXT_GENERATE_NEW_ACCESS_KEY_BUTTON); ?>
                                </button>
                            </div>
                        </div>
                        <div class="card m-b-20 animated flipInX">
                            <div class="card-body">
                                <h5 class="header-title mb-3">Resource Usage</h5>
                                <?PHP
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "LYDIA_SESSIONS"),
                                        getAllowedUsage($Subscription, "LYDIA_SESSIONS"),
                                        TEXT_DATA_TYPE_LYDIA_SESSIONS_CREATED, "#3bc3e9"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "NFW_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_NSFW_CHECKS"),
                                        TEXT_DATA_TYPE_NSFW_CLASSIFICATIONS, "#ea553d"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "POS_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_POS_CHECKS"),
                                        TEXT_DATA_TYPE_POS_CHECKS, "#e83e8c"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "SENTIMENT_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_SENTIMENT_CHECKS"),
                                        TEXT_DATA_TYPE_SENTIMENT_CHECKS, "#007bff"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "EMOTION_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_EMOTION_CHECKS"),
                                        TEXT_DATA_TYPE_EMOTION_CHECKS, "#20c997"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "SPAM_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_SPAM_CHECKS"),
                                        TEXT_DATA_TYPE_CHATROOM_SPAM_PREDICTIONS, "#ffc107"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "LANGUAGE_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_LANGUAGE_CHECKS"),
                                        TEXT_DATA_TYPE_LANGUAGE_DETECTION, "#dc3545"
                                    );
                                    generateUsageWidget(
                                        getCurrentUsage($AccessRecord, "NER_CHECKS"),
                                        getAllowedUsage($Subscription, "MAX_NER_CHECKS"),
                                        TEXT_DATA_TYPE_NER_CHECKS, "#6f42c1"
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card m-b-20 animated bounceInRight">
                            <div class="card-body">
                                <h4 class="header-title"><?PHP HTML::print(TEXT_API_USAGE_CARD_TITLE); ?></h4>
                                <div id="deepanalytics_viewer">
                                   <span><?PHP HTML::print(TEXT_API_USAGE_CARD_LOADING_PLACEHOLDER) ?></span>
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
    <?PHP Javascript::importScript('rpage'); ?>
    <?PHP Javascript::importScript('deepanalytics'); ?>
    <?PHP Javascript::importScript('excanvas'); ?>
    <?PHP Javascript::importScript('knobbet'); ?>
    <?PHP Javascript::importScript('peity'); ?>
    <?PHP Javascript::importScript('dashboard'); ?>
</html>
