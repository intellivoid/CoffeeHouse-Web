<?php /** @noinspection PhpUndefinedConstantInspection */

    use COASniffle\COASniffle;
    use COASniffle\Exceptions\BadResponseException;
    use COASniffle\Exceptions\CoaAuthenticationException;
    use COASniffle\Exceptions\RequestFailedException;
    use COASniffle\Exceptions\UnsupportedAuthMethodException;
    use COASniffle\Objects\SubscriptionPurchaseResults;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    HTML::importScript('check_subscription');

    if(WEB_SUBSCRIPTION_ACTIVE)
    {
        Actions::redirect(DynamicalWeb::getRoute('dashboard'));
    }

    /** @var COASniffle $COASniffle */
    $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
    HTML::importScript('alert');

    if(isset($_GET['plan']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('api'));
    }

    try
    {
        /** @var SubscriptionPurchaseResults $Subscription */
        if(isset($_GET['promotion_code']))
        {
            $Subscription = $COASniffle->getCOA()->createSubscription(WEB_ACCESS_TOKEN, $_GET['plan'], $_GET['promotion_code']);
        }
        else
        {
            $Subscription = $COASniffle->getCOA()->createSubscription(WEB_ACCESS_TOKEN, $_GET['plan']);
        }
    }
    catch (BadResponseException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'index', array('callback' => '101')
        ));
    }
    catch (CoaAuthenticationException $e)
    {
        switch($e->getCode())
        {
            case 43:
                Actions::redirect(DynamicalWeb::getRoute('index', array(
                    'callback' => '106'
                )));
                break;

            case 44:
                Actions::redirect(DynamicalWeb::getRoute('purchase', array(
                    'plan' => $_GET['plan'],
                    'callback' => '100'
                )));
                break;

            case 45:
                Actions::redirect(DynamicalWeb::getRoute('index', array(
                    'callback' => '107'
                )));
                break;

            case 46:
                Actions::redirect(DynamicalWeb::getRoute('purchase', array(
                    'plan' => $_GET['plan'],
                    'callback' => '101'
                )));
                break;

            case 47:
                Actions::redirect(DynamicalWeb::getRoute('purchase', array(
                    'plan' => $_GET['plan'],
                    'callback' => '102'
                )));
                break;

            case 48:
                Actions::redirect(DynamicalWeb::getRoute('purchase', array(
                    'plan' => $_GET['plan'],
                    'callback' => '103'
                )));
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute(
                    'index', array('callback' => '102', 'coa_error' => (string)$e->getCode())
                ));
        }
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

    HTML::importScript('alert');
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <title>CoffeeHouse - Confirm Purchase</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section generic">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="generic-title mb-5">Confirm Purchase</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <?PHP HTML::importScript('callbacks'); ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <div class="card-body">

                                    <h4 class="card-title"><?PHP HTML::print(TEXT_DETAILS_HEADER); ?></h4>
                                    <p class="card-description"><?PHP HTML::print(TEXT_DETAILS_DESC); ?></p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tbody>
                                                <?PHP
                                                foreach($Subscription->SubscriptionDetails->Features as $feature)
                                                {
                                                    switch($feature['name'])
                                                    {
                                                        case 'SERVER_CONFIGS':
                                                            ?>
                                                            <tr>
                                                                <td><?PHP HTML::print("Server Configurations"); ?></td>
                                                                <td><?PHP HTML::print($feature['value']); ?></td>
                                                            </tr>
                                                            <?PHP
                                                            break;

                                                        default:
                                                            ?>
                                                            <tr>
                                                                <td><?PHP HTML::print($feature['name']); ?></td>
                                                                <td><?PHP HTML::print($feature['value']); ?></td>
                                                            </tr>
                                                            <?PHP
                                                            break;
                                                    }
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 border-left">
                                            <div class="text-center pricing-card-head">
                                                <h3><?PHP HTML::print($Subscription->SubscriptionDetails->PlanName); ?></h3>
                                                <h1 class="font-weight-normal mb-4 text-success">$<?PHP HTML::print($Subscription->SubscriptionDetails->InitialPrice); ?> USD</h1>
                                                <p>
                                                    <?PHP
                                                    $Text = TEXT_DETAILS_PAYMENT;
                                                    $Text = str_ireplace('%bc', intval(abs($Subscription->SubscriptionDetails->BillingCycle)/60/60/24), $Text);
                                                    $Text = str_ireplace('%cp', $Subscription->SubscriptionDetails->CyclePrice, $Text);
                                                    HTML::print($Text);
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <?PHP
                                if(isset($_GET['promotion_code']) == false)
                                {
                                    ?>
                                    <div class="card-body">
                                        <h4 class="card-title"><?PHP HTML::print(TEXT_PROMOTION_HEADER); ?></h4>
                                        <p class="card-description"><?PHP HTML::print(TEXT_PROMOTION_DESC); ?></p>
                                        <form action="<?PHP DynamicalWeb::getRoute('purchase', array(), true); ?>" method="GET">
                                            <input type="hidden" name="plan" id="plan" value="<?PHP HTML::print( $_GET['plan']); ?>">
                                            <div class="form-group">
                                                <label for="promotion_code"><?PHP HTML::print(TEXT_PROMOTION_LABEL); ?></label>
                                                <input type="text" class="form-control" name="promotion_code" id="promotion_code" placeholder="<?PHP HTML::print(TEXT_PROMOTION_PLACEHOLDER); ?>">
                                            </div>
                                            <button type="submit" class="btn btn-info btn-xs mr-2"><?PHP HTML::print(TEXT_PROMOTION_SUBMIT_BUTTON); ?></button>
                                        </form>
                                    </div>
                                    <?PHP
                                }
                                ?>
                                <div class="card-body">
                                    <button type="button" onclick="window.open('<?PHP HTML::print($Subscription->ProcessTransactionURL); ?>');" class="btn btn-outline-primary float-right"><?PHP HTML::print(TEXT_CONFIRM_PURCHASE_BUTTON); ?></button>
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