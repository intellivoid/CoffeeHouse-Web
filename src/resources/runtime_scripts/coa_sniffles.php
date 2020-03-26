<?php

    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;

    Runtime::import('COASniffle');

    /**
     * This script defines the COA Application and registers the constructed objects into
     * DynamicalWeb Memory Objects, this is a runtime script that is executed before anything
     */

    $COASniffle = new COASniffle();
    $ApplicationConfiguration = DynamicalWeb::getConfiguration('coasniffle');

    $COASniffle->defineApplication(
        $ApplicationConfiguration['PUBLIC_APPLICATION_ID'],
        $ApplicationConfiguration['SECRET_KEY'],
        ApplicationType::Redirect
    );

    DynamicalWeb::setMemoryObject('coasniffle', $COASniffle);
    DynamicalWeb::setMemoryObject('application_configuration', $ApplicationConfiguration);