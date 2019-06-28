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
                Calls this month
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20">Calls last month: <?PHP HTML::print(number_format($UsageData['LastMonth'])); ?></p>

        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-brown mr-0 float-right"><i class="mdi mdi-chart-pie"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-brown"><?PHP HTML::print($BillingDetails['monthly_calls']); ?></span>
                Monthly Calls
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20">Next Billing Cycle: <?PHP HTML::print(gmdate("Y-m-d", $BillingDetails['next_billing_cycle'])); ?></p>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-blue-grey mr-0 float-right"><i class="mdi mdi-currency-usd"></i></span>
            <div class="mini-stat-info">
                <span class="counter text-blue-grey"><?PHP HTML::print($BillingDetails['plan_type']); ?></span>
                API Subscription
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20">Billing Price: <?PHP HTML::print("$" . $BillingDetails['billing_price'] . " U.S."); ?></p>
        </div>
    </div>
</div>