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
        <title>CoffeeHouse</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>

        <section class="section home" id="home">
            <div class="container">
                <?PHP HTML::importScript('callbacks'); ?>

                <div class="row">
                    <div class="bg-overlay">
                        <div class="bg"></div>
                        <div class="bg bg2"></div>
                        <div class="bg bg3"></div>
                    </div>
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="home-title animated slow fadeInLeft">CoffeeHouse</h1>
                        <p class="mt-4 home-subtitle animated slow fadeInRight">Multi-purpose cloud based artificial intelligence & machine learning service for all</p>
                        <img src="/assets/images/lydia_showcase.svg" alt="CoffeeHouse's Lydia being used in Python" class="img-fluid mt-4 animated slower fadeIn">
                    </div>
                </div>
            </div>
        </section>


        <section class="section" id="features">
            <div class="container">

                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <h3>Everything simplified.</h3>
                            <p class="text-muted slogan">CoffeeHouse is a one stop solution for cloud-based artificial intelligence & machine learning processing, overtime more features are added and improved on CoffeeHouse</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-help text-custom"></i>
                            <h5 class="pt-4">Community & Official Support</h5>
                            <p class="text-gray pt-2"> We also have a great community where you can drop by in and ask any question your heart desires </p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-devices text-custom"></i>
                            <h5 class="pt-4">Open Platform</h5>
                            <p class="text-gray pt-2"> Our open source API Wrappers & Documentation is available to all, free free to contribute!</p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-sale text-custom "></i>
                            <h5 class="pt-4">Affordable & Free</h5>
                            <p class="text-gray pt-2"> We provide the service for free if you would like to use it for personal uses, need more? it's also affordable with monthly subscriptions!</p>
                        </div>
                    </div>

                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-smile-face text-custom"></i>
                            <h5 class="pt-4">Independent Technologies</h5>
                            <p class="text-gray pt-2">CoffeeHouse is not dependent upon third party services or libraries such as Tensorflow to function, everything was build from scratch.</p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-features text-custom"></i>
                            <h5 class="pt-4">Always More</h5>
                            <p class="text-gray pt-2">We are constantly working hard to add and improve this service for everyone</p>
                        </div>
                    </div>
                    <div class="col-md-4 services-box">
                        <div class="text-center p-3">
                            <i class="mbri-code text-custom"></i>
                            <h5 class="pt-4">Truly Simple</h5>
                            <p class="text-gray pt-2">No need for complicated configurations or setups, start using our services with little to no effort as a software developer</p>
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
                            <h3>Affordable Pricing</h3>
                            <p class="text-muted slogan">Choose the right plan for your necessities</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-sm-4">
                        <div class="card plan-card text-center">
                            <div class="card-body">
                                <div class="pt-3 pb-3">
                                    <h1><i class="ion-trophy plan-icon bg-dark"></i></h1>
                                    <h6 class="text-uppercase text-dark">Personal Use</h6>
                                </div>
                                <div>
                                    <h1 class="plan-price text-success">FREE</h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p>Free Official Support</p>
                                    <p>Limited Resources</p>
                                    <p>For personal use only</p>
                                    <p>No hidden fees/trials</p>
                                    <a href="<?PHP HTML::print($FreeLocation); ?>" class="btn btn-custom">Get License</a>
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
                                    <h6 class="text-uppercase text-dark">Basic Plan</h6>
                                </div>
                                <div>
                                    <h1 class="plan-price"><?PHP HTML::print($Text); ?><sup class="text-muted">USD Per Month</sup></h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p>Free Official Support</p>
                                    <p>More Resources</p>
                                    <p>For personal use only</p>
                                    <p>No extra costs/hidden fees</p>
                                    <a href="<?PHP HTML::print($BasicLocation); ?>" class="btn btn-custom">Get License</a>
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
                                    <h6 class="text-uppercase text-dark">Enterprise Plan</h6>
                                </div>
                                <div>
                                    <h1 class="plan-price"><?PHP HTML::print($Text); ?><sup class="text-muted">USD Per month</sup></h1>
                                    <div class="plan-div-border"></div>
                                </div>
                                <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                    <p>Free Official Support</p>
                                    <p>Unlimited Resources</p>
                                    <p>For personal uses</p>
                                    <p>For commercial uses</p>
                                    <a href="<?PHP HTML::print($EnterpriseLocation); ?>" class="btn btn-custom">Get License</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div>
        </section>

        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>