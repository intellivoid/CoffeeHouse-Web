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

    header('Content-Type: application/json');
    print(json_encode($Update, JSON_PRETTY_PRINT));
    exit();
