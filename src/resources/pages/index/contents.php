<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Page;
    use DynamicalWeb\Runtime;
    use IntellivoidSubscriptionManager\Exceptions\SubscriptionPlanNotFoundException;
    use IntellivoidSubscriptionManager\IntellivoidSubscriptionManager;

    if(WEB_SESSION_ACTIVE == false)
    {
        if(isset($_GET['access_token']))
        {
            HTML::importScript('authenticate_coa');
        }
    }

    HTML::importScript('check_subscription');
    Runtime::import('IntellivoidSubscriptionManager');

    $IntellivoidSubscriptionManager = new IntellivoidSubscriptionManager();
    $ApplicationConfiguration = DynamicalWeb::getConfiguration('coasniffle');

    try
    {
        $FreeSubscriptionPlan = $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlanByName(
            $ApplicationConfiguration['APPLICATION_INTERNAL_ID'], "Free"
        );
    }
    catch (SubscriptionPlanNotFoundException $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'FREE' is not configured properly"
        );
        exit();
    }
    catch(Exception $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'FREE' raised an unknown error"
        );
        exit();
    }

    try
    {
        $BasicSubscriptionPlan = $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlanByName(
            $ApplicationConfiguration['APPLICATION_INTERNAL_ID'], "Basic"
        );
    }
    catch (SubscriptionPlanNotFoundException $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'BASIC' is not configured properly"
        );
        exit();
    }
    catch(Exception $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'BASIC' raised an unknown error"
        );
        exit();
    }

    try
    {
        $EnterpriseSubscriptionPlan = $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlanByName(
            $ApplicationConfiguration['APPLICATION_INTERNAL_ID'], "Enterprise"
        );
    }
    catch (SubscriptionPlanNotFoundException $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'ENTERPRISE' is not configured properly"
        );
        exit();
    }
    catch(Exception $e)
    {
        Page::staticResponse(
            "Configuration Error", "Intellivoid Accounts Error",
            "The subscription plan for 'ENTERPRISE' raised an unknown error"
        );
        exit();
    }

    $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
    $Protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';

    $FreeLocation = '';
    $BasicLocation = '';
    $EnterpriseLocation = '';

    if(WEB_SESSION_ACTIVE == false)
    {
        $FreeLocation = $COASniffle->getCOA()->getAuthenticationURL(
            $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index', array(
                'redirect' => 'confirm_purchase', 'plan' => 'free'
            ))
        );
        $BasicLocation = $COASniffle->getCOA()->getAuthenticationURL(
            $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index', array(
                'redirect' => 'confirm_purchase', 'plan' => 'basic'
            ))
        );
        $EnterpriseLocation = $COASniffle->getCOA()->getAuthenticationURL(
            $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index', array(
                'redirect' => 'confirm_purchase', 'plan' => 'enterprise'
            ))
        );
    }
    else
    {
        $FreeLocation = DynamicalWeb::getRoute('purchase', array('plan' => 'free'));
        $BasicLocation = DynamicalWeb::getRoute('purchase', array('plan' => 'basic'));
        $EnterpriseLocation = DynamicalWeb::getRoute('purchase', array('plan' => 'enterprise'));
    }

    HTML::importScript('alert');
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section home" id="home">
            <div class="container">
                <?PHP HTML::importScript('callbacks'); ?>
                <?PHP
                render_alert("We are no longer providing Lydia as a service anymore and it will completely be removed from CoffeeHouse soon, thank you for understanding.", 'danger', 'alert-circle');
                ?>
                <div class="row">
                    <div class="bg-overlay">
                        <div class="bg"></div>
                        <div class="bg bg2"></div>
                        <div class="bg bg3"></div>
                    </div>
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="home-title animated slow fadeInLeft"><?PHP HTML::print(TEXT_INTRODUCTION_TITLE); ?></h1>
                        <p class="mt-4 home-subtitle animated slow fadeInRight"><?PHP HTML::print(TEXT_INTRODUCTION_DESCRIPTION); ?></p>
                        <img src="/assets/images/lydia_showcase.svg" alt="<?PHP HTML::print(TEXT_INTRODUCTION_IMAGE_ALT); ?>" class="img-fluid mt-4 animated slower fadeIn">
                    </div>
                </div>
            </div>
        </section>
        <section class="section" id="features">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <h3><?PHP HTML::print(TEXT_FEATURES_HEADER); ?></h3>
                            <p class="text-muted slogan"><?PHP HTML::print(TEXT_FEATURES_DESCRIPTION); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-help text-custom"></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_1_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_1_DESCRIPTION); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-devices text-custom"></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_2_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_2_DESCRIPTION); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-sale text-custom "></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_3_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_3_DESCRIPTION); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-smile-face text-custom"></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_4_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_4_DESCRIPTION); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-features text-custom"></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_5_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_5_DESCRIPTION); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-code text-custom"></i>
                            <h5 class="pt-4"><?PHP HTML::print(TEXT_FEATURE_6_TITLE); ?></h5>
                            <p class="text-gray pt-2"><?PHP HTML::print(TEXT_FEATURE_6_DESCRIPTION); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section" id="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <h3><?PHP HTML::print(TEXT_PRICING_HEADER); ?></h3>
                            <p class="text-muted slogan"><?PHP HTML::print(TEXT_PRICING_DESCRIPTION); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-4">
                        <div class="card plan-card text-center">
                            <div class="card-body">
                                <div class="pt-3 pb-3">
                                    <h1><i class="ion-trophy plan-icon bg-dark"></i></h1>
                                    <h6 class="text-uppercase text-dark"><?PHP HTML::print(TEXT_PRICING_FEATURE_PERSONAL_USAGE); ?></h6>
                                </div>
                                <div>
                                    <h1 class="plan-price text-success"><?PHP HTML::print(TEXT_PLAN_FREE_TITLE); ?></h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_FREE_SUPPORT); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_LIMITED_RESOURCES); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_PERSONAL_USE_ONLY); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_NO_HIDDEN_TRIALS); ?></p>
                                    <a href="<?PHP HTML::print($FreeLocation); ?>" class="btn btn-custom"><?PHP HTML::print(TEXT_GET_LICENSE_BUTTON); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card plan-card text-center">
                            <div class="card-body">
                                <div class="pt-3 pb-3">
                                    <?PHP
                                        $Text = "$%s";
                                        $Text = str_ireplace('%s', $BasicSubscriptionPlan->CyclePrice, $Text);
                                    ?>
                                    <h1><i class="ion-trophy plan-icon bg-dark"></i></h1>
                                    <h6 class="text-uppercase text-dark"><?PHP HTML::print(TEXT_PLAN_BASIC_TITLE); ?></h6>
                                </div>
                                <div>
                                    <h1 class="plan-price"><?PHP HTML::print($Text); ?><sup class="text-muted"><?PHP HTML::print(TEXT_PRICE_PER_MONTH); ?></sup></h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_FREE_SUPPORT); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_MORE_RESOURCES); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_PERSONAL_USE_ONLY); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_NO_HIDDEN_FEES); ?></p>
                                    <a href="<?PHP HTML::print($BasicLocation); ?>" class="btn btn-custom"><?PHP HTML::print(TEXT_GET_LICENSE_BUTTON); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card plan-card text-center">
                            <div class="card-body">
                                <div class="pt-3 pb-3">
                                    <?PHP
                                        $Text = "$%s";
                                        $Text = str_ireplace('%s', $EnterpriseSubscriptionPlan->CyclePrice, $Text);
                                    ?>
                                    <h1><i class="ion-trophy plan-icon bg-dark"></i></h1>
                                    <h6 class="text-uppercase text-dark"><?PHP HTML::print(TEXT_PLAN_ENTERPRISE_TITLE); ?></h6>
                                </div>
                                <div>
                                    <h1 class="plan-price"><?PHP HTML::print($Text); ?><sup class="text-muted"><?PHP HTML::print(TEXT_PRICE_PER_MONTH); ?></sup></h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_FREE_SUPPORT); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_UNLIMITED_RESOURCES); ?></p>
                                    <p><?PHP HTML::print(TEXT_PRICING_FEATURE_PERSONAL_AND_COMMERCIAL_USE); ?></p>
                                    <a href="<?PHP HTML::print($EnterpriseLocation); ?>" class="btn btn-custom"><?PHP HTML::print(TEXT_GET_LICENSE_BUTTON); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>