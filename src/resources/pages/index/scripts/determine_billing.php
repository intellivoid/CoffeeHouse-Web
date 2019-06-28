<?php

    use CoffeeHouse\Objects\ApiPlan;
    use DynamicalWeb\DynamicalWeb;

    $BillingDetails = array(
        'monthly_calls' => 'Unknown'
    );

    /** @var ApiPlan $Plan */
    $Plan = DynamicalWeb::getMemoryObject('COFFEE_HOUSE_PLAN');

    if($Plan->MonthlyCalls == 0)
    {
        $BillingDetails['monthly_calls'] = 'Unlimited';
    }
    else
    {
        $BillingDetails['monthly_calls'] = $Plan->MonthlyCalls;
    }

    $BillingDetails['next_billing_cycle'] = $Plan->NextBillingCycle;

    switch($Plan->PlanType)
    {
        case \CoffeeHouse\Abstracts\APIPlan::Free:
            $BillingDetails['plan_type'] = "Free";
            break;

        case \CoffeeHouse\Abstracts\APIPlan::Basic:
            $BillingDetails['plan_type'] = "Basic";
            break;

        case \CoffeeHouse\Abstracts\APIPlan::Enterprise:
            $BillingDetails['plan_type'] = "Enterprise";
            break;

        default:
            $BillingDetails['plan_type'] = "Unknown";
            break;
    }

    $BillingDetails['billing_price'] = $Plan->PricePerCycle;

    DynamicalWeb::setArray('BILLING_DETAILS', $BillingDetails);