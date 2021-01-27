<?php

    use DynamicalWeb\DynamicalWeb;
    use IntellivoidSubscriptionManager\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
use IntellivoidSubscriptionManager\Exceptions\DatabaseException;
use IntellivoidSubscriptionManager\Exceptions\InvalidSearchMethodException;
use IntellivoidSubscriptionManager\Exceptions\SubscriptionPlanNotFoundException;
use IntellivoidSubscriptionManager\IntellivoidSubscriptionManager;
    use IntellivoidSubscriptionManager\Objects\Subscription;
use IntellivoidSubscriptionManager\Utilities\Converter;

    /**
     * Checks if an update is required
     *
     * @param Subscription $subscription \
     * @return bool
     * @throws DatabaseException
     * @throws InvalidSearchMethodException
     * @throws SubscriptionPlanNotFoundException
     */
    function us_update_required(Subscription $subscription): bool
    {
        /** @var IntellivoidSubscriptionManager $IntellivoidSubscriptionManager */
        $IntellivoidSubscriptionManager = DynamicalWeb::getMemoryObject('intellivoid_subscription_manager');

        $SubscriptionPlan =  $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlan(
            SubscriptionPlanSearchMethod::byId, $subscription->SubscriptionPlanID
        );

        $plan_features = Converter::featuresToSA($SubscriptionPlan->Features);
        $current_features = Converter::featuresToSA($subscription->Properties->Features);

        /** @var Subscription\Feature $feature */
        foreach($plan_features as $feature_name => $value)
        {
            if(in_array($feature_name, $current_features) == false)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Updates the existing subscription plan
     *
     * @param Subscription $subscription
     * @return Subscription
     * @throws DatabaseException
     * @throws InvalidSearchMethodException
     * @throws SubscriptionPlanNotFoundException
     */
    function us_update_subscription(Subscription $subscription)
    {
        /** @var IntellivoidSubscriptionManager $IntellivoidSubscriptionManager */
        $IntellivoidSubscriptionManager = DynamicalWeb::getMemoryObject('intellivoid_subscription_manager');

        $SubscriptionPlan =  $IntellivoidSubscriptionManager->getPlanManager()->getSubscriptionPlan(
            SubscriptionPlanSearchMethod::byId, $subscription->SubscriptionPlanID
        );

        $plan_features = Converter::featuresToSA($SubscriptionPlan->Features);

        foreach($plan_features as $feature_name => $value)
        {
            $feature_already_exists = false;

            /** @var Subscription\Feature $feature */
            foreach($subscription->Properties->Features as $feature)
            {
                if($feature->Name == $feature_name)
                {
                    $feature_already_exists = true;
                    break;
                }
            }

            if($feature_already_exists == false)
            {
                $Feature = new Subscription\Feature();
                $Feature->Name = $feature_name;
                $Feature->Value = $value;
                $subscription->Properties->addFeature($Feature);
            }
        }

        $IntellivoidSubscriptionManager->getSubscriptionManager()->updateSubscription($subscription);

        return $subscription;
    }