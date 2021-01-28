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
            case "deepanalytics.locale":
                da_get_locale();
                break;

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

    /**
     * Returns the locale data defined in the language file
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    function da_get_locale()
    {
        $Results = array(
            'status' => true,
            'payload' => array(
                'DEEPANALYTICS_NO_DATA_ERROR' => TEXT_DEEPANALYTICS_NO_DATA_ERROR,
                'DEEPANALYTICS_GENERIC_ERROR' => TEXT_DEEPANALYTICS_GENERIC_ERROR,
                'DEEPANALYTICS_MONTHLY_USAGE' => TEXT_DEEPANALYTICS_MONTHLY_USAGE,
                'DEEPANALYTICS_DAILY_USAGE' => TEXT_DEEPANALYTICS_DAILY_USAGE,
                'DEEPANALYTICS_DATA_SELECTOR' => TEXT_DEEPANALYTICS_DATA_SELECTOR,
                'DEEPANALYTICS_DATE_SELECTOR' => TEXT_DEEPANALYTICS_DATE_SELECTOR,
                'DEEPANALYTICS_DATA_ALL' => TEXT_DEEPANALYTICS_DATA_ALL
            )
        );

        \DynamicalWeb\Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        \DynamicalWeb\Response::finishRequest();
        exit(0);
    }

    /**
     * Returns the hourly data which is used to be rendered in the linechart
     *
     * @noinspection DuplicatedCode
     */
    function da_get_hourly_data()
    {
        if(isset($_POST['year']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 10,
                'message' => 'Missing parameter \'year\''
            );

            \DynamicalWeb\Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            \DynamicalWeb\Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            \DynamicalWeb\Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            \DynamicalWeb\Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['day']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 12,
                'message' => 'Missing parameter \'day\''
            );

            \DynamicalWeb\Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            \DynamicalWeb\Response::finishRequest();
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
                "intellivoid_api", "requests", $AccessRecord->ID, true,
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
                "coffeehouse_api", "created_sessions", $AccessRecord->ID, true,
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
                "coffeehouse_api", "ai_responses", $AccessRecord->ID, true.
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

        \DynamicalWeb\Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        \DynamicalWeb\Response::finishRequest();
        exit(0);
    }

    /**
     * Returns the monthly data that's used to render the linechart
     */
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
                "intellivoid_api", "requests", $AccessRecord->ID, true,
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
                "coffeehouse_api", "created_sessions", $AccessRecord->ID, true,
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
                "coffeehouse_api", "ai_responses", $AccessRecord->ID, true,
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

        \DynamicalWeb\Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        \DynamicalWeb\Response::finishRequest();
        exit(0);
    }

    /**
     * Returns the data range that's available
     */
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
                "text" => TEXT_DATA_TYPE_REQUESTS
            ),
            "created_sessions" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "created_sessions", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "created_sessions", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_LYDIA_SESSIONS_CREATED
            ),
            "ai_responses" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "ai_responses", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "ai_responses", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_LYDIA_THOUGHTS_PROCESSED
            )
        );

        \DynamicalWeb\Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        \DynamicalWeb\Response::finishRequest();
        exit(0);
    }