<?php
    use COASniffle\Utilities\ErrorResolver;

    if(isset($_GET['callback']))
    {
        switch((int)$_GET['callback'])
        {
            case 100:
                render_alert('There was an unexpected error while trying to process your request', 'danger', 'alert-circle');
                break;

            case 101:
                render_alert('Intellivoid Accounts returned a response that was not understood by the server', 'warning', 'alert-circle');
                break;

            case 102:
                $ErrorCode = 0;

                if(isset($_GET['coa_error']))
                {
                    $ErrorCode = (int)$_GET['coa_error'];
                }

                $ErrorMessage = ErrorResolver::resolve_error_code($ErrorCode);

                render_alert(str_ireplace('%s', $ErrorMessage, 'There was an error while trying to process your authentication: %s'), 'warning', 'alert-circle');
                break;

            case 103:
                render_alert('Intellivoid Accounts cannot be reached at this time', 'warning', 'alert-circle');
                break;

            case 104:
                render_alert('The authentication method that was used isn\'t supported by this server', 'danger', 'alert-circle');
                break;

            case 105:
                render_alert('You\'ve logged in successfully!', 'success', 'check-circle-outline');
                break;

            case 106:
                render_alert('The subscription plan was not found', 'warning', 'alert-circle');
                break;

            case 107:
                render_alert('The subscription plan is not available', 'warning', 'alert-circle');
                break;

        }
    }