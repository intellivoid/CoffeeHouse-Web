<?php

    use CoffeeHouse\Abstracts\PlanSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;

    Runtime::import('CoffeeHouse');

    function planExists(int $account_id): bool
    {
        $CoffeeHouse = new CoffeeHouse();
        if($CoffeeHouse->getApiPlanManager()->accountIdExists($account_id) == true)
        {
            DynamicalWeb::setMemoryObject(
                'API_PLAN',
                $CoffeeHouse->getApiPlanManager()->getPlan(
                    PlanSearchMethod::byAccountId, $account_id
                )
            );

            /** @var \CoffeeHouse\Objects\ApiPlan $Plan */
            $Plan = DynamicalWeb::getMemoryObject('API_PLAN');
            if($Plan->PlanStarted == false)
            {
                return false;
            }

            return true;
        }

        return false;
    }
