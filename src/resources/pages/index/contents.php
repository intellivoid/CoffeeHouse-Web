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

    HTML::importScript('determine_total_usage');
    HTML::importScript('determine_billing');


?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <link rel="stylesheet" href="/assets/vendors/morris/morris.css">
        <?PHP HTML::importSection('header'); ?>
        <title>CoffeeHouse - Dashboard</title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>

        <div class="wrapper">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <h4 class="page-title">CoffeeHouse Dashboard</h4>
                        </div>
                    </div>
                </div>

                <?PHP HTML::importScript('render_widgets'); ?>

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="header-title">Usage Analytics</h4>
                                <div id="api-usage-chart" class="morris-charts" style="height: 300px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="header-title">Test1</h4>
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
</html>
