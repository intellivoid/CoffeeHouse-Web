<?php

    if(isset($_GET['callback']))
    {
        switch((int)$_GET['callback'])
        {
            case 100:
                render_alert('The promotion code is invalid', 'danger', 'alert-circle');
                break;

            case 101:
                render_alert('The promotion code is not available', 'danger', 'alert-circle');
                break;

            case 102:
                render_alert('The promotion code has expired', 'danger', 'alert-circle');
                break;

            case 103:
                render_alert('The promotion code is not applicable to this plan', 'danger', 'alert-circle');
                break;
        }
    }