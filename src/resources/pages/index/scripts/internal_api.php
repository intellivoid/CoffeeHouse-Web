<?php

    use DynamicalWeb\DynamicalWeb;
    use ModularAPI\Objects\AccessKey;


    $Update = array(
        'usage' => array(
            'current_month' => 0,
            'last_month' => 0
        )
    );

    /** @var AccessKey $AccessKey */
    $AccessKey = DynamicalWeb::getMemoryObject('ACCESS_KEY');

    if($AccessKey->Analytics->CurrentMonthAvailable == true)
    {
        foreach($AccessKey->Analytics->CurrentMonthUsage as $Month => $Usage)
        {
            $Update['usage']['current_month'] += $Usage;
        }
    }

    if($AccessKey->Analytics->LastMonthAvailable == true)
    {
        foreach($AccessKey->Analytics->LastMonthUsage as $Month => $Usage)
        {
            $Update['usage']['last_month'] += $Usage;
        }
    }

    $Update['analytics'] = array();
    if($AccessKey->Analytics->LastMonthAvailable == true)
    {
        $Update['analytics']['last_month_available'] = true;
        foreach ($AccessKey->Analytics->CurrentMonthUsage as $key => $value) {
            $Update['analytics']['data'][$key]['day'] = $key + 1;
            $Update['analytics']['data'][$key]['current_month'] = $value;
        }

        foreach ($AccessKey->Analytics->LastMonthUsage as $key => $value) {
            $Update['analytics']['data'][$key]['day'] = $key + 1;
            $Update['analytics']['data'][$key]['last_month'] = $value;
        }
    }
    else
    {
        $Update['analytics']['last_month_available'] = false;
        foreach($AccessKey->Analytics->CurrentMonthUsage as $key => $value)
        {
            $Update['analytics']['data'][$key]['day'] = $key + 1;
            $Update['analytics']['data'][$key]['current_month'] = $value;
        }
    }

    header('Content-Type: application/json');
    print(json_encode($Update, JSON_PRETTY_PRINT));
    exit();
