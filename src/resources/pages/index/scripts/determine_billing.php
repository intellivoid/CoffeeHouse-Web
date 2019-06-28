<?php

    use CoffeeHouse\Objects\ApiPlan;
    use DynamicalWeb\DynamicalWeb;

    $BillingDetails = array(
        'monthly_calls' => TEXT_MONTHLY_CALLS_UNKNOWN
    );

    /** @var ApiPlan $Plan */
    $Plan = DynamicalWeb::getMemoryObject('COFFEE_HOUSE_PLAN');

    if($Plan->MonthlyCalls == 0)
    {
        $BillingDetails['monthly_calls'] = TEXT_MONTHLY_CALLS_UNLIMITED;
    }
    else
    {
        $BillingDetails['monthly_calls'] = number_format($Plan->MonthlyCalls);
    }

    $BillingDetails['next_billing_cycle'] = $Plan->NextBillingCycle;

    switch($Plan->PlanType)
    {
        case \CoffeeHouse\Abstracts\APIPlan::Free:
            $BillingDetails['plan_type'] = TEXT_PLAN_TYPE_FREE;
            break;

        case \CoffeeHouse\Abstracts\APIPlan::Basic:
            $BillingDetails['plan_type'] = TEXT_PLAN_TYPE_BASIC;
            break;

        case \CoffeeHouse\Abstracts\APIPlan::Enterprise:
            $BillingDetails['plan_type'] = TEXT_PLAN_TYPE_ENTERPRISE;
            break;

        default:
            $BillingDetails['plan_type'] = TEXT_PLAN_TYPE_UNKNOWN;
            break;
    }

    $BillingDetails['billing_price'] = $Plan->PricePerCycle;

    DynamicalWeb::setArray('BILLING_DETAILS', $BillingDetails);