<?php

    if(WEB_SESSION_ACTIVE == true)
    {
        header('Location: /');
        exit();
    }