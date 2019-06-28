<?php

    use CoffeeHouse\Abstracts\PlanSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\Runtime;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_signatures')
        {
            update_signatures();
        }

        if($_GET['action'] == 'download_certificate')
        {
            download_certificate();
        }
    }

    function update_signatures()
    {
        $CoffeeHouse = new CoffeeHouse();

        $Plan = $CoffeeHouse->getApiPlanManager()->getPlan(PlanSearchMethod::byAccountId, WEB_ACCOUNT_ID);
        $CoffeeHouse->getApiPlanManager()->updateSignatures($Plan);
        header('Location: /');
        exit();
    }

    function download_certificate()
    {
        $CoffeeHouse = new CoffeeHouse();
        $ModularAPI = new \ModularAPI\ModularAPI();

        $Plan = $CoffeeHouse->getApiPlanManager()->getPlan(PlanSearchMethod::byAccountId, WEB_ACCOUNT_ID);
        $AccessKey = $ModularAPI->AccessKeys()->Manager->get(\ModularAPI\Abstracts\AccessKeySearchMethod::byID, $Plan->AccessKeyId);
        $Results = array(
            'certificate' => $AccessKey->Signatures->createCertificate(),
            'public_id' => $AccessKey->PublicID
        );

        header('Content-Type: application/x-x509-user-cert');
        header("Content-disposition: attachment; filename=\"" . $Results['public_id'] . ".crt\"");
        print($Results['certificate']);
        exit();
    }