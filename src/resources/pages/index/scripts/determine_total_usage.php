<?php

    use DynamicalWeb\DynamicalWeb;
    use ModularAPI\Objects\AccessKey;

    $UsageData = array(
        'CurrentMonth' => 0,
        'LastMonth' => 0
    );

    /** @var AccessKey $AccessKey */
    $AccessKey = DynamicalWeb::getMemoryObject('ACCESS_KEY');

    if($AccessKey->Analytics->CurrentMonthAvailable == true)
    {
        foreach($AccessKey->Analytics->CurrentMonthUsage as $Month => $Usage)
        {
            $UsageData['CurrentMonth'] += $Usage;
        }
    }

    if($AccessKey->Analytics->LastMonthAvailable == true)
    {
        foreach($AccessKey->Analytics->LastMonthUsage as $Month => $Usage)
        {
            $UsageData['LastMonth'] += $Usage;
        }
    }

    DynamicalWeb::setArray('CURRENT_USAGE', $UsageData);