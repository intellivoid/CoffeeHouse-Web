<?php


    use CoffeeHouse\CoffeeHouse;
    use DeepAnalytics\Exceptions\DataNotFoundException;
    use DeepAnalytics\Objects\Date;
    use DeepAnalytics\Utilities;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Response;
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

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }

    /**
     * Fetches the data from the DeepAnalytics database
     *
     * @param string $name
     * @param CoffeeHouse $coffeeHouse
     * @param AccessRecord $accessRecord
     * @param Date $selectedDate
     * @return array|null
     */
    function db_hourly_data_fetch(string $source, string $name, CoffeeHouse $coffeeHouse, AccessRecord $accessRecord, Date $selectedDate): ?array
    {
        try
        {
            $hourlyDataResults = $coffeeHouse->getDeepAnalytics()->getHourlyData(
                $source, $name, $accessRecord->ID, true,
                (int)$_POST["year"], (int)$_POST["month"], (int)$_POST["day"]);

            $return_results = [
                //"name" => $hourlyDataResults->Name,
                "total" => $hourlyDataResults->Total,
                "data" =>[]
            ];

            foreach($hourlyDataResults->getData(true) as $key => $value)
            {
                $return_results["data"][Utilities::generateFullHourStamp($selectedDate, $key)] = $value;
            }

            return $return_results;
        }
        catch(DataNotFoundException)
        {
            return null;
        }
    }

    /**
     * Returns the hourly data which is used to be rendered in the linechart
     *
     * @noinspection DuplicatedCode
     * @noinspection PhpNoReturnAttributeCanBeAddedInspection
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

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['month']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 11,
                'message' => 'Missing parameter \'month\''
            );

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        if(isset($_POST['day']) == false)
        {
            $Results = array(
                'status' => false,
                'error_code' => 12,
                'message' => 'Missing parameter \'day\''
            );

            Response::setResponseType("application/json");
            print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            Response::finishRequest();
            exit(0);
        }

        /** @var CoffeeHouse $CoffeeHouse */
        $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $SelectedDate = new Date();
        $SelectedDate->Year = (int)$_POST['year'];
        $SelectedDate->Month = (int)$_POST['month'];
        $SelectedDate->Day = (int)$_POST['day'];

        $Results = array();

        $Results["requests"] = db_hourly_data_fetch("intellivoid_api", "requests", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["created_sessions"] = db_hourly_data_fetch("coffeehouse_api", "created_sessions", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["ai_responses"] = db_hourly_data_fetch("coffeehouse_api", "ai_responses", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["nsfw_classifications"] = db_hourly_data_fetch("coffeehouse_api", "nsfw_classifications", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["pos_checks"] = db_hourly_data_fetch("coffeehouse_api", "pos_checks", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["sentiment_checks"] = db_hourly_data_fetch("coffeehouse_api", "sentiment_checks", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["emotion_checks"] = db_hourly_data_fetch("coffeehouse_api", "emotion_checks", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["chatroom_spam_checks"] = db_hourly_data_fetch("coffeehouse_api", "chatroom_spam_checks", $CoffeeHouse, $AccessRecord, $SelectedDate);
        $Results["language_checks"] = db_hourly_data_fetch("coffeehouse_api", "language_checks", $CoffeeHouse, $AccessRecord, $SelectedDate);

        $Results = array(
            'status' => true,
            'results' => $Results
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }

    /**
     * Fetches the data from the DeepAnalytics database
     *
     * @param string $source
     * @param string $name
     * @param CoffeeHouse $coffeeHouse
     * @param AccessRecord $accessRecord
     * @return array|null
     */
    function db_monthly_data_fetch(string $source, string $name, CoffeeHouse $coffeeHouse, AccessRecord $accessRecord): ?array
    {

        try
        {
            $monthlyData = $coffeeHouse->getDeepAnalytics()->getMonthlyData(
                $source, $name, $accessRecord->ID, true,
                (int)$_POST["year"], (int)$_POST["month"]);

            $return_results = [
                "total" => $monthlyData->Total,
                "data" => []
            ];

            foreach($monthlyData->getData(true) as $key => $value)
            {
                $return_results['data'][Utilities::generateHourlyStamp(
                    (int)$_POST['year'], (int)$_POST['month'], $key
                )] = $value;
            }

            return $return_results;
        }
        catch(DataNotFoundException)
        {
            return null;
        }
    }

    /**
     * Returns the monthly data that's used to render the linechart
     * @noinspection PhpNoReturnAttributeCanBeAddedInspection
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

        $AnalyticalResults = array();

        $AnalyticalResults["requests"] = db_monthly_data_fetch("intellivoid_api", "requests", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["created_sessions"] = db_monthly_data_fetch("coffeehouse_api", "created_sessions", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["ai_responses"] = db_monthly_data_fetch("coffeehouse_api", "ai_responses", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["nsfw_classifications"] = db_monthly_data_fetch("coffeehouse_api", "nsfw_classifications", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["pos_checks"] = db_monthly_data_fetch("coffeehouse_api", "pos_checks", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["sentiment_checks"] = db_monthly_data_fetch("coffeehouse_api", "sentiment_checks", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["emotion_checks"] = db_monthly_data_fetch("coffeehouse_api", "emotion_checks", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["chatroom_spam_checks"] = db_monthly_data_fetch("coffeehouse_api", "chatroom_spam_checks", $CoffeeHouse, $AccessRecord);
        $AnalyticalResults["language_checks"] = db_monthly_data_fetch("coffeehouse_api", "language_checks", $CoffeeHouse, $AccessRecord);

        $Results = array(
            "status" => true,
            "results" => $AnalyticalResults
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
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
            ),

            "nsfw_classifications" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "nsfw_classifications", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "nsfw_classifications", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_NSFW_CLASSIFICATIONS
            ),

            "pos_checks" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "pos_checks", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "pos_checks", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_POS_CHECKS
            ),

            "sentiment_checks" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "sentiment_checks", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "sentiment_checks", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_SENTIMENT_CHECKS
            ),

            "emotion_checks" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "emotion_checks", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "emotion_checks", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_EMOTION_CHECKS
            ),

            "chatroom_spam_checks" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "chatroom_spam_checks", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "chatroom_spam_checks", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_CHATROOM_SPAM_PREDICTIONS
            ),

            "language_checks" => array(
                "monthly" => $CoffeeHouse->getDeepAnalytics()->getMonthlyDataRange(
                    "coffeehouse_api", "language_checks", $AccessRecord->ID),
                "hourly" => $CoffeeHouse->getDeepAnalytics()->getHourlyDataRange(
                    "coffeehouse_api", "language_checks", $AccessRecord->ID),
                "text" => TEXT_DATA_TYPE_LANGUAGE_DETECTION
            )
        );

        Response::setResponseType("application/json");
        print(json_encode($Results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Response::finishRequest();
        exit(0);
    }