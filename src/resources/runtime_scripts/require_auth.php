<?php

    if(WEB_SESSION_ACTIVE == false)
    {
        switch(APP_CURRENT_PAGE)
        {
            case 'login': break;
            case 'register': break;
            default:
                header('Location: /login');
                exit();
        }
    }