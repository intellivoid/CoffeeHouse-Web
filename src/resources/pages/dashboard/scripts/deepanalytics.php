<?php


    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAPI\Objects\AccessRecord;

    if(isset($_GET['action']))
    {
        switch($_GET['action'])
        {
            case "deepanalytics.get_range":
                da_get_range();
        }
    }

    function da_get_range()
    {
        /** @var CoffeeHouse $CoffeeHouse */
        $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');

        /** @var AccessRecord $AccessRecord */
        $AccessRecord = DynamicalWeb::getMemoryObject('access_record');

        $CoffeeHouse->getee
    }