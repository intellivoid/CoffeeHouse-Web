<?PHP

    use CoffeeHouse\Abstracts\PlanSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Objects\ApiPlan;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use ModularAPI\Abstracts\AccessKeySearchMethod;
    use ModularAPI\ModularAPI;
    use ModularAPI\Objects\AccessKey;


    Runtime::import('CoffeeHouse');
    HTML::importScript('dashboard_actions');

    $CoffeeHouse = new CoffeeHouse();
    $ModularAPI = new ModularAPI();

    /** @var ApiPlan $Plan */
    $Plan = DynamicalWeb::setMemoryObject(
            'COFFEE_HOUSE_PLAN', $CoffeeHouse->getApiPlanManager()->getPlan(
                    PlanSearchMethod::byAccountId, WEB_ACCOUNT_ID
            )
    );

    /** @var AccessKey $AccessKey */
    $AccessKey = DynamicalWeb::setMemoryObject(
            'ACCESS_KEY', $ModularAPI->AccessKeys()->Manager->get(
                AccessKeySearchMethod::byID, $Plan->AccessKeyId
            )
    );

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'get_info')
        {
            HTML::importScript('internal_api');
        }
    }

    HTML::importScript('determine_total_usage');
    HTML::importScript('determine_billing');


?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <link rel="stylesheet" href="/assets/vendors/morris/morris.css">
        <?PHP HTML::importSection('header'); ?>
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>

        <div class="wrapper">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <h4 class="page-title"><?PHP HTML::print(TEXT_PAGE_TITLE); ?></h4>
                        </div>
                    </div>
                </div>

                <?PHP HTML::importScript('render_widgets'); ?>

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="header-title"><?PHP HTML::print(TEXT_ANALYTICS_CARD_HEADER); ?></h4>
                                <div id="api-usage-chart" class="morris-charts" style="height: 300px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <div class="form-group m-b-0">
                                    <label for="api_key"><?PHP HTML::print(TEXT_AUTH_API_KEY_HEADER); ?></label>
                                    <input class="form-control" type="text" value="<?PHP HTML::print($AccessKey->PublicKey); ?>" id="api_key" name="api_key" readonly>
                                </div>
                                <div class="form-group m-b-0">
                                    <label for="certificate"><?PHP HTML::print(TEXT_AUTH_CERTIFICATE_KEY_HEADER); ?></label>
                                    <textarea class="form-control" type="text" id="certificate" name="certificate" rows="8" readonly><?PHP HTML::print($AccessKey->Signatures->createCertificate()); ?></textarea>
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
    <?PHP HTML::importScript('render_charts_js'); ?>
    <?PHP HTML::importScript('realtime_js'); ?>
</html>
