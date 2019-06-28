<?php

    use CoffeeHouse\Abstracts\PlanSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\Runtime;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_signatures')
        {
            update_signatures();
            header('Location: /');
            exit();
        }
    }

    function update_signatures()
    {
        Runtime::import('CoffeeHouse');
        $CoffeeHouse = new CoffeeHouse();

        $Plan = $CoffeeHouse->getApiPlanManager()->getPlan(PlanSearchMethod::byAccountId, WEB_ACCOUNT_ID);
        $CoffeeHouse->getApiPlanManager()->updateSignatures($Plan);
    }