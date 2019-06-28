<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    $UsageData = DynamicalWeb::getArray('CURRENT_USAGE');
    $BillingDetails = DynamicalWeb::getArray('BILLING_DETAILS');

?>
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-purple mr-0 float-right">
                <i class="mdi mdi-calendar-today"></i>
            </span>
            <div class="mini-stat-info">
                <span class="counter text-purple"><?PHP HTML::print(number_format($UsageData['CurrentMonth'])); ?></span>
                <?PHP HTML::print(TEXT_WIDGET_MONTHLY_CALLS); ?>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"><?PHP HTML::print(str_ireplace('%s', number_format($UsageData['LastMonth']), TEXT_WIDGET_MONTHLY_CALLS_SUB)); ?></p>

        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-brown mr-0 float-right"><i class="mdi mdi-chart-pie"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-brown"><?PHP HTML::print($BillingDetails['monthly_calls']); ?></span>
                <?PHP HTML::print(TEXT_WIDGET_BILLING_DETAILS); ?>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"><?PHP HTML::print(str_ireplace('%s', gmdate("Y-m-d", $BillingDetails['next_billing_cycle']), TEXT_WIDGET_BILLING_DETAILS_SUB)); ?></p>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-blue-grey mr-0 float-right"><i class="mdi mdi-currency-usd"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-blue-grey"><?PHP HTML::print($BillingDetails['plan_type']); ?></span>
                <?PHP HTML::print(TEXT_WIDGET_SUBSCRIPTION); ?>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"><?PHP HTML::print(str_ireplace('%s', "$" . $BillingDetails['billing_price'] . " U.S.", TEXT_WIDGET_SUBSCRIPTION_SUB)); ?></p>
        </div>
    </div>
</div>