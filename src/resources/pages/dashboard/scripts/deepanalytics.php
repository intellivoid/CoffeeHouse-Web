<?php


    use CoffeeHouse\CoffeeHouse;
    use DeepAnalytics\Exceptions\DataNotFoundException;
    use DeepAnalytics\Utilities;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAPI\Objects\AccessRecord;

    if(isset($_GET['action']))
    {
        switch($_GET['action'])
        {
            case "deepanalytics.get_range":
                da_get_range();
                break;

            case "deepanalytics.get_monthly_data":
                da_get_monthly_data();
                break;

            case "deepanalytics.get_hourly_data":
                da_get_hourly_data();
                break;
        }
    }

    /** @noinspection DuplicatedCode */
    function da_get_hourly_data()
    {
        if(isset($_POST['year']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 10,
                'message' => 'Missing parameter \'year\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        if(isset($_POST['day']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 12,
                'message' => 'Missing parameter \'day\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        /** @var CoffeeHouse $CoffeeHouse */
        $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $SelectedDate = new \DeepAnalytics\Objects\Date();
        $SelectedDate->Year = (int)$_POST['year'];
        $SelectedDate->Month = (int)$_POST['month'];
        $SelectedDate->Day = (int)$_POST['day'];

        $Results = array();

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getHourlyData(
                "intellivoid_api", "requests", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month'], (int)$_POST['day']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateFullHourStamp(
                    $SelectedDate, $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['requests'] = null;
        }

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getHourlyData(
                "coffeehouse_api", "created_sessions", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month'], (int)$_POST['day']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateFullHourStamp(
                    $SelectedDate, $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['created_sessions'] = null;
        }

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getHourlyData(
                "coffeehouse_api", "ai_responses", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month'], (int)$_POST['day']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateFullHourStamp(
                    $SelectedDate, $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['ai_responses'] = null;
        }

        $Results = array(
            'status' => true,
            'results' => $Results
        );

        header('Content-Type: application/json');
        print(json_encode($Results));
        exit(0);
    }

    function da_get_monthly_data()
    {
        if(isset($_POST['year']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 10,
                'message' => 'Missing parameter \'year\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            header('Content-Type: application/json');
            print(json_encode($Results));
            exit(0);
        }

        /** @var CoffeeHouse $CoffeeHouse */
        $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $Results = array();

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getMonthlyData(
                "intellivoid_api", "requests", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateHourlyStamp(
                    (int)$_POST['year'], (int)$_POST['month'], $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['requests'] = null;
        }

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getMonthlyData(
                "coffeehouse_api", "created_sessions", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateHourlyStamp(
                    (int)$_POST['year'], (int)$_POST['month'], $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['created_sessions'] = null;
        }

        try
        {
            $Object = $CoffeeHouse->getDeepAnalytics()->getMonthlyData(
                "coffeehouse_api", "ai_responses", $AccessRecord->ID,
                (int)$_POST['year'], (int)$_POST['month']);

            $Results[$Object->Name] = array(
                'total' => $Object->Total,
                'data' => array()
            );

            foreach($Object->getData(true) as $key => $value)
            {
                $Results[$Object->Name]['data'][Utilities::generateHourlyStamp(
                    (int)$_POST['year'], (int)$_POST['month'], $key
                )] = $value;
            }
        }
        catch(DataNotFoundException $exception)
        {
            $Results['ai_responses'] = null;
        }

        $Results = array(
            'status' => true,
            'results' => $Results
        );

        header('Content-Type: application/json');
        print(json_encode($Results));
        exit(0);
    }

    function da_get_range()
    {
        /** @var CoffeeHouse $CoffeeHouse */
        $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $Results = array(
            "requests" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "intellivoid_api", "requests", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "intellivoid_api", "requests", $AccessRecord->ID),
                "text" => "API Requests"
            ),
            "created_sessions" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "created_sessions", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "created_sessions", $AccessRecord->ID),
                "text" => "Lydia Sessions Created"
            ),
            "ai_responses" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "ai_responses", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "ai_responses", $AccessRecord->ID),
                "text" => "Lydia Thoughts Processed"
            )
        );

        header('Content-Type: application/json');
        print(json_encode($Results));
        exit(0);
    }